<?php

namespace App\Twig;

use App\Twig\Cache\CacheableInterface;
use App\Twig\Cache\CacheTokenParser;
use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Twig\Extension\AbstractExtension;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Class TwigCacheExtension.
 *
 * @author Grafikart
 *
 * @see https://github.com/Grafikart/Grafikart.fr/blob/master/src/Core/Twig/TwigCacheExtension.php
 */
class TwigCacheExtension extends AbstractExtension
{
    private AdapterInterface $cache;
    private bool $active;

    public function __construct(AdapterInterface $cache, bool $active = true)
    {
        $this->cache = $cache;
        $this->active = $active;
    }

    /**
     * @return array<AbstractTokenParser>
     */
    public function getTokenParsers(): array
    {
        return [
            new CacheTokenParser(),
        ];
    }

    /**
     * @param CacheableInterface|string $item
     */
    public function getCacheValue(string $templatePath, $item): ?string
    {
        if (!$this->active) {
            return null;
        }
        /** @var CacheItem $item */
        $item = $this->cache->getItem($this->getCacheKey($templatePath, $item));

        return $item->get();
    }

    /**
     * @param array|bool|CacheableInterface|object|string|null $item
     */
    public function getCacheKey(string $templatePath, $item, bool $prefix = true): string
    {
        if (true === $prefix) {
            $prefix = (new AsciiSlugger())->slug(str_replace('.html.twig', '', $templatePath)) . '_';
        } else {
            $prefix = '';
        }

        if ($item instanceof AbstractLazyCollection || $item instanceof ArrayCollection) {
            $item = $item->getValues();
        }
        if (\is_bool($item)) {
            return $prefix . ($item ? '1' : '0');
        }
        if (empty($item)) {
            throw new \Exception('Clef de cache invalide');
        }
        if (\is_string($item)) {
            return $prefix . $item;
        }
        if (\is_array($item)) {
            return $prefix . implode('_', array_map(fn ($v) => $this->getCacheKey($templatePath, $v, false), $item));
        }

        if (!\is_object($item) || !($item instanceof CacheableInterface)) {
            throw new \Exception("TwigCache : Impossible de serialiser une variable qui n'est pas un objet ou une chaine");
        }

        try {
            $updatedAt = $item->getUpdatedAt() ?: new \DateTimeImmutable('@0');
            $id = $item->getId() ?: '0';
            $className = \get_class($item);
            $className = substr($className, strrpos($className, '\\') + 1);

            return $prefix . $id . $className . $updatedAt->getTimestamp();
        } catch (\Error $e) {
            throw new \Exception("TwigCache : Impossible de serialiser l'objet pour le cache : \n" . $e->getMessage());
        }
    }

    /**
     * @param CacheableInterface|string $item
     */
    public function setCacheValue(string $templatePath, $item, string $value): void
    {
        if (!$this->active) {
            return;
        }
        /** @var CacheItem $item */
        $item = $this->cache->getItem($this->getCacheKey($templatePath, $item));
        $item->set($value);
        $this->cache->save($item);
    }
}
