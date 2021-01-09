<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StyleUtilExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            //            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cssClassRandom', [$this, 'cssClassRandom']),
        ];
    }

    public function cssClassRandom()
    {
        $v = ['purple', 'green', 'yellow', 'blue', 'red', 'light-black'];

        return $v[array_rand($v, 1)];
    }
}
