<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class NavExtension extends AbstractExtension
{
    private RouterInterface $router;

    private RequestStack $request;

    public function __construct(RouterInterface $router, RequestStack $request)
    {
        $this->router = $router;
        $this->request = $request;
    }

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
            new TwigFunction('GetPreviousUrl', [$this, 'GetPreviousUrl']),
        ];
    }

    public function GetPreviousUrl()
    {
        try {
            $url = $this->router->generate('index');
            $referer = $this->request->getMasterRequest()->headers->get('referer');
            $matcher = $this->router->getMatcher();
            $lastPath = substr($referer, strpos($referer, $this->request->getMasterRequest()->getSchemeAndHttpHost()));
            $lastPath = str_replace($this->request->getMasterRequest()->getSchemeAndHttpHost(), '', $lastPath);

            $parametersLastRoute = $matcher->match($lastPath);

            if ($parametersLastRoute['_route'] !== $this->request->getMasterRequest()->attributes->get('_route')) {
                $url = $referer;
            }

            return $url;
        } catch (\Exception $e) {
            return null;
        }
    }
}
