<?php

namespace App\EventSubscriber\Doctrine\SilvainEu;

use App\Controller\Dashboard\SilvainEu\AdminServicesController;
use App\Entity\Main\SilvainEu\Service;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class SilvainEuSubscriber implements EventSubscriberInterface
{
    private SluggerInterface $slugger;

    private CacheItemPoolInterface $cache;

    public function __construct(SluggerInterface $slugger, CacheItemPoolInterface $cache)
    {
        $this->slugger = $slugger;
        $this->cache = $cache;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist => 'prePersist',
            Events::preUpdate => 'preUpdate',
        ];
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        if ($args->getEntity() instanceof Service) {
            $args->getEntity()->MakeSlug($this->slugger);
            AdminServicesController::RemoveServiceCache($args->getEntityManager(), $this->cache);
        }
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        if ($args->getEntity() instanceof Service) {
            $args->getEntity()->MakeSlug($this->slugger);
            AdminServicesController::RemoveServiceCache($args->getEntityManager(), $this->cache);
        }
    }
}
