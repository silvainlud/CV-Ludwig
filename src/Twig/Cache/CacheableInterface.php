<?php

namespace App\Twig\Cache;

/**
 * Interface CacheableInterface.
 *
 * @author Grafikart
 *
 * @see https://github.com/Grafikart/Grafikart.fr/blob/master/src/Core/Twig/CacheExtension/CacheableInterface.php
 */
interface CacheableInterface
{
    /**
     * @return int|string|null
     */
    public function getId();

    public function getUpdatedAt(): ?\DateTimeInterface;
}
