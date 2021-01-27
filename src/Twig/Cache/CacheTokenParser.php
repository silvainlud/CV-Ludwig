<?php

namespace App\Twig\Cache;

use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Class CacheTokenParser.
 *
 * @author Grafikart
 * @url https://github.com/Grafikart/Grafikart.fr/blob/master/src/Core/Twig/CacheExtension/CacheTokenParser.php
 */
class CacheTokenParser extends AbstractTokenParser
{
    public function parse(Token $token): CacheNode
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $key = $this->parser->getExpressionParser()->parseExpression();
        $key->setAttribute('always_defined', true);
        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideCacheEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new CacheNode($key, $body, $lineno, $this->getTag());
    }

    public function getTag(): string
    {
        return 'cache';
    }

    public function decideCacheEnd(Token $token): bool
    {
        return $token->test('endcache');
    }
}
