<?php

namespace App\Tests\Twig;

use App\Twig\Cache\CacheableInterface;

/**
 * Class FakeTestClass.
 *
 * @author Grafikart
 * https://github.com/Grafikart/Grafikart.fr/blob/master/tests/Core/Twig/FakeClass.php
 */
class FakeTestClass implements CacheableInterface
{
    public function getId(): int
    {
        return 4;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return new \DateTime('@12312312');
    }
}
