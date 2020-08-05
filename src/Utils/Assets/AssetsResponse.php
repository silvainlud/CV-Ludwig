<?php

namespace App\Utils\Assets;

use App\Utils\StringHelper;
use Gumlet\ImageResize;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Contracts\Cache\ItemInterface;

class AssetsResponse
{
    public const image_width = 200;

    /**
     * @param $file
     * @param $filename
     * @param string $ext
     *
     * @throws InvalidArgumentException
     *
     * @return Response
     */
    public static function ReturnImg($file, $filename, $ext = 'png', int $width_resize = self::image_width, int $expireCache = 3600): Response
    {
        $filename = StringHelper::strRemoveAccent($filename);
        $filename = str_replace(' ', '', $filename);
        $filename = str_replace('/', '_', $filename);
        $filename = str_replace('\\', '_', $filename);
        $filename = iconv('UTF-8', 'ASCII//IGNORE', $filename);
        $ext = str_replace(' ', '', $ext);
        $ext = str_replace('_', '', $ext);
        if ($expireCache < 0) {
            return self::MakeReponseImg($file, $filename, $ext, $width_resize);
        }
        if (\is_string($file)) {
            $cacheKey = $file . '_' . $width_resize;
        } else {
            $cacheKey = $filename . '_' . $ext . '_' . $width_resize;
        }

        $cacheKey = StringHelper::strRemoveCacheKey($cacheKey);
        $cache = new FilesystemAdapter('app.cache');

        return $cache->get($cacheKey, function (ItemInterface $item) use ($file, $filename, $ext, $width_resize, $expireCache) {
            $item->expiresAfter($expireCache);

            return self::MakeReponseImg($file, $filename, $ext, $width_resize);
        });
    }

    public static function CacheKey($file, string $filename, string $ext, int $width_resize): string
    {
        if (\is_string($file)) {
            $cacheKey = $file . '_' . $width_resize;
        } else {
            $cacheKey = $filename . '_' . $ext . '_' . $width_resize;
        }

        return StringHelper::strRemoveCacheKey($cacheKey);
    }

    public static function MakeReponseImg($file, $filename, $ext = 'png', int $width_resize = self::image_width): Response
    {
        if (\is_string($file)) {
            $image = new ImageResize($file);
        } else {
            $image = ImageResize::createFromString(stream_get_contents($file));
        }

        $image->resizeToWidth($width_resize);
        $response = new Response((string) $image);
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
        $date = new \DateTime();
        $date->modify('+ 900 seconds');
        $response->setExpires($date);
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');
        $response->headers->addCacheControlDirective('max-age', 900);
        $response->headers->addCacheControlDirective('s-maxage', 900);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('public', true);
        $response->headers->removeCacheControlDirective('private');

        return $response;
    }

    /**
     * @param $file
     * @param $filename
     * @param string $ext
     *
     * @throws InvalidArgumentException
     *
     * @return Response
     */
    public static function ReturnImgAdapterCache(AdapterInterface $cache, $file, $filename, $ext = 'png', int $width_resize = self::image_width, int $expireCache = 3600): Response
    {
        $filename = StringHelper::strRemoveAccent($filename);
        $filename = str_replace(' ', '', $filename);
        $filename = str_replace('/', '_', $filename);
        $filename = str_replace('\\', '_', $filename);
        $filename = iconv('UTF-8', 'ASCII//IGNORE', $filename);
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
}
