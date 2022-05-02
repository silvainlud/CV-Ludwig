<?php

namespace App\Utils\Assets;

use App\Utils\StringHelper;
use DateTime;
use Gumlet\ImageResize;
use InvalidArgumentException;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;
use Symfony\Component\Mime\MimeTypes;

class AssetsResponse
{
    public const image_width = 200;

    /**
     * @param resource|string $file
     */
    public static function MakeReponseImg($file, string $filename, string $ext = 'png', ?int $width_resize = self::image_width): Response
    {
        if (\is_string($file)) {
            $image = new ImageResize($file);
        } else {
            $stream = stream_get_contents($file);
            if (false === $stream) {
                throw new InvalidArgumentException('The var $file is not a valid resource.');
            }

            $image = ImageResize::createFromString($stream);
        }
        if (null !== $width_resize) {
            $image->resizeToWidth($width_resize);
        }
        $response = new Response((string)$image);

        $filename .= '.' . $ext;

        $mimeTypes = new MimeTypes();
        $mime = $mimeTypes->getMimeTypes($ext);
        // cache for 3600 seconds

        if (\count($mime) > 0) {
            $response->headers->set('Content-Type', $mime[0]);
            $disposition = HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_INLINE,
                $filename
            );
        } else {
            $disposition = HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $filename
            );
        }
        $date = new DateTime();
        $date->modify('+ 900 seconds');
        $response->setExpires($date);
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');
        $response->headers->addCacheControlDirective('max-age', "900");
        $response->headers->addCacheControlDirective('s-maxage', "900");
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('public', true);
        $response->headers->removeCacheControlDirective('private');

        return $response;
    }

    /**
     * @param resource|string $file
     */
    public static function ReturnImgAdapterCache(CacheItemPoolInterface $cache, $file, string $filename, ?string $ext = 'png', ?int $width_resize = self::image_width, int $expireCache = 3600): Response
    {
        $filename = StringHelper::strRemoveAccent($filename);
        $filename = str_replace(' ', '', $filename);
        $filename = str_replace('/', '_', $filename);
        $filename = str_replace('\\', '_', $filename);
        $filename = iconv('UTF-8', 'ASCII//IGNORE', $filename);

        if (false === $filename) {
            $filename = StringHelper::strRemoveAccent(StringHelper::GenreateRandomString(16));
            $expireCache = 0;
        }

        if (null === $ext && !\is_string($file)) {
            $ext = mime_content_type($file);
            if (false !== $ext && str_contains($ext, '/')) {
                $ext = explode('/', $ext)[1];
            }
        }

        if (null === $ext || false === $ext) {
            throw new InvalidArgumentException('Bad extension');
        }

        $ext = str_replace(' ', '', $ext);
        $ext = str_replace('_', '', $ext);
        if ($expireCache < 0) {
            return self::MakeReponseImg($file, $filename, $ext, $width_resize);
        }

        $cacheKey = self::CacheKey($file, $filename, $ext, $width_resize);
        $item = $cache->getItem($cacheKey);
        if (!$item->isHit()) {
            $item->expiresAfter($expireCache);
            $item->set(self::MakeReponseImg($file, $filename, $ext, $width_resize));
            $cache->save($item);
        }

        return $item->get();
    }

    /**
     * @param resource|string $file
     */
    public static function CacheKey($file, string $filename, ?string $ext, ?int $width_resize): string
    {
        if (null === $ext) {
            if (!\is_string($file)) {
                $ext = mime_content_type($file);
            } else {
                $stream = fopen('php://memory', 'r+');
                if (false !== $stream) {
                    fwrite($stream, $file);
                    rewind($stream);
                    $ext = mime_content_type($stream);
                }
            }
            if (false !== $ext && null !== $ext && str_contains($ext, '/')) {
                $ext = explode('/', $ext)[1];
            }
        }
        if (null === $ext || false === $ext) {
            throw new InvalidArgumentException('Bad extension');
        }

        $cacheKey = $filename . '_' . $ext . '_' . $width_resize;

        return StringHelper::strRemoveCacheKey($cacheKey);
    }
}
