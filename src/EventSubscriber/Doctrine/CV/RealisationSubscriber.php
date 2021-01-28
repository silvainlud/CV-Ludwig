<?php

namespace App\EventSubscriber\Doctrine\CV;

use App\Entity\Main\CV\Realisation;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class RealisationSubscriber implements EventSubscriberInterface
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

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->prePersist($args);
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        if ($args->getEntity() instanceof Realisation) {
            $args->getEntity()->CompleteSlug($this->slugger);
        }
    }
}
