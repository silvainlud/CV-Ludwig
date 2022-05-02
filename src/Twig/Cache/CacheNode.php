<?php

namespace App\Twig\Cache;

use App\Twig\TwigCacheExtension;
use Twig\Compiler;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Node;

/**
 * Class CacheNode.
 *
 * @author Grafikart
 *
 * @see https://github.com/Grafikart/Grafikart.fr/blob/master/src/Core/Twig/CacheExtension/CacheNode.php
 */
class CacheNode extends Node
{
    /**
     * @param AbstractExpression<AbstractExpression> $key
     */
    public function __construct(AbstractExpression $key, Node $body, int $lineno, string $tag = null)
    {
        parent::__construct(['key' => $key, 'body' => $body], [], $lineno, $tag);
    }

    /**
     * {@inheritdoc}
     */
    public function compile(Compiler $compiler) : void
    {
        $i = 0;
        $extension = TwigCacheExtension::class;
        $templateParam = "\"{$this->getTemplateName()}\", ";
        $compiler
            ->addDebugInfo($this)
            ->write("\$twigCacheExtension = \$this->env->getExtension('{$extension}');\n")
            ->write("\$twigCacheBody{$i} = \$twigCacheExtension->getCacheValue({$templateParam}")
            ->subcompile($this->getNode('key'))
            ->raw(");\n")
            ->write("if (\$twigCacheBody{$i} !== null) { echo \$twigCacheBody{$i}; } else {\n")
            ->indent()
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write("\$twigCacheBody{$i} = ob_get_clean();\n")
            ->write("\$twigCacheExtension->setCacheValue({$templateParam}")
            ->subcompile($this->getNode('key'))
            ->raw(',')
            ->raw("\$twigCacheBody{$i});\n")
            ->write("echo \$twigCacheBody{$i};\n")
            ->outdent()
            ->write("}\n");
    }
}
