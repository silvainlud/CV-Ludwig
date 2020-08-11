<?php

namespace App\EventSubscriber\Doctrine\SilvainEu;

use App\Controller\Dashboard\SilvainEu\AdminServicesController;
use App\Entity\Main\SilvainEu\Service;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class SilvainEuSubscriber implements EventSubscriberInterface
{
    private SluggerInterface $slugger;

    private AdapterInterface $cache;

    public function __construct(SluggerInterface $slugger, AdapterInterface $cache)
    {
        $this->slugger = $slugger;
        $this->cache = $cache;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist => 'prePersist',
            Events::preUpdate => 'preUpdate',
        ];
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof Service) {
            $args->getEntity()->MakeSlug($this->slugger);
            AdminServicesController::RemoveServiceCache($args->getEntityManager(), $this->cache);
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof Service) {
            $args->getEntity()->MakeSlug($this->slugger);
            AdminServicesController::RemoveServiceCache($args->getEntityManager(), $this->cache);
        }
    }
}
