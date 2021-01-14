<?php

namespace App\EventSubscriber\Doctrine\CV;

use App\Controller\Dashboard\CV\AdminSkillsController;
use App\Entity\Main\CV\Competence;
use App\Entity\Main\CV\CompetenceCategorie;
use App\Entity\Main\CV\Technologie;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class CompetenceSubscriber implements EventSubscriberInterface
{
    private SluggerInterface $slugger;

    private AdapterInterface $cache;

    public function __construct(SluggerInterface $slugger, AdapterInterface $cache)
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
        if ($args->getEntity() instanceof Technologie) {
            $args->getEntity()->CompleteSlug($this->slugger);
            AdminSkillsController::RemoveSkillCache($args->getEntityManager(), $this->cache);
        } elseif ($args->getEntity() instanceof Competence) {
            AdminSkillsController::RemoveSkillCache($args->getEntityManager(), $this->cache);
        } elseif ($args->getEntity() instanceof CompetenceCategorie) {
            AdminSkillsController::RemoveSkillCache($args->getEntityManager(), $this->cache);
        }
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        if ($args->getEntity() instanceof Technologie) {
            $args->getEntity()->CompleteSlug($this->slugger);
            AdminSkillsController::RemoveSkillCache($args->getEntityManager(), $this->cache);
        } elseif ($args->getEntity() instanceof Competence) {
            AdminSkillsController::RemoveSkillCache($args->getEntityManager(), $this->cache);
        } elseif ($args->getEntity() instanceof CompetenceCategorie) {
            AdminSkillsController::RemoveSkillCache($args->getEntityManager(), $this->cache);
        }
    }
}
