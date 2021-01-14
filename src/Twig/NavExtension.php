<?php

namespace App\Twig;

use App\Controller\SilvainEu\ServiceController;
use App\Entity\Main\SilvainEu\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavExtension extends AbstractExtension
{
    private RouterInterface $router;

    private RequestStack $request;

    private EntityManagerInterface $em;

    private AdapterInterface $cache;

    private TranslatorInterface $translator;

    private UrlMatcherInterface $matcher;

    public function __construct(RouterInterface $router, UrlMatcherInterface $matcher, RequestStack $request, EntityManagerInterface $em, AdapterInterface $cache, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->request = $request;
        $this->em = $em;
        $this->cache = $cache;
        $this->translator = $translator;
        $this->matcher = $matcher;
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
            new TwigFunction('GetServicesFooter', [$this, 'GetServicesFooter']),
        ];
    }

    public function GetPreviousUrl(): ?string
    {
        try {
            $url = $this->router->generate('index');
            if (null === $this->request->getMasterRequest()) {
                return null;
            }
            $referer = $this->request->getMasterRequest()->headers->get('referer');

            if (null == $referer) {
                return null;
            }

            $lastPath = str_replace($this->request->getMasterRequest()->getSchemeAndHttpHost(), '', $referer);

            $parametersLastRoute = $this->matcher->match($lastPath);

            if ($parametersLastRoute['_route'] !== $this->request->getMasterRequest()->attributes->get('_route')) {
                $url = $referer;
            }

            return $url;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return array<int, array<int, array<int, string|null>>>
     */
    public function GetServicesFooter(): array
    {
        $i = $this->cache->getItem(ServiceController::CACHE_KEY_SERVICES);
        if (!$i->isHit()) {
            $i->set($this->em->getRepository(Service::class)->findAll());
            $this->cache->save($i);
        }

        $col1 = [];
        $col2 = [];
        $services = $i->get();
        $i = 1;
        /** @var Service $s */
        foreach ($services as $s) {
            $k = [0 => $s->getName(), $s->getLink()];
            if ($i < \count($services)) {
                $col1[] = $k;
            } else {
                $col2[] = $k;
            }

            ++$i;
        }
        $k = [$this->translator->trans('general.title.legal-notice'), ''];
        if ($i < \count($services)) {
            $col1[] = $k;
        } else {
            $col2[] = $k;
        }

        return [0 => $col1, 1 => $col2];
    }
}
