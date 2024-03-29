<?php

namespace App\EventSubscriber\Doctrine\CV;

use App\Controller\Dashboard\CV\AdminMakingController;
use App\Entity\Main\CV\Realisation;
use App\Entity\Main\CV\RealisationImage;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class RealisationSubscriber implements EventSubscriberInterface
{
    private SluggerInterface $slugger;

    private CacheItemPoolInterface $cache;

    public function __construct(SluggerInterface $slugger, CacheItemPoolInterface $cache)
    {
        $this->slugger = $slugger;
        $this->cache = $cache;
    }

    public function getSubscribedEvents() : array
    {
        return [
            Events::prePersist => 'prePersist',
            Events::preUpdate => 'preUpdate',
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        if ($args->getEntity() instanceof Realisation) {
            $args->getEntity()->CompleteSlug($this->slugger);
            AdminMakingController::RemoveMakingCache($args->getEntityManager(), $this->cache);
        } elseif ($args->getEntity() instanceof RealisationImage) {
            AdminMakingController::RemoveMakingCache($args->getEntityManager(), $this->cache);
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->prePersist($args);
    }
}
