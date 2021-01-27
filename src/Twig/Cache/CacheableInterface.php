<?php

namespace App\Twig\Cache;

/**
 * Interface CacheableInterface.
 *
 * @author Grafikart
 * @url https://github.com/Grafikart/Grafikart.fr/blob/master/src/Core/Twig/CacheExtension/CacheableInterface.php
 */
interface CacheableInterface
{
    public function getId(): ?int;

    public function getUpdatedAt(): ?\DateTimeInterface;
}
