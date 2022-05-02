<?php

namespace App\EventSubscriber\Doctrine\CV;

use App\Controller\Dashboard\CV\AdminMakingController;
use App\Controller\Dashboard\CV\AdminSkillsController;
use App\Entity\Main\CV\Competence;
use App\Entity\Main\CV\CompetenceCategorie;
use App\Entity\Main\CV\RealisationImage;
use App\Entity\Main\CV\Technologie;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class CompetenceSubscriber implements EventSubscriberInterface
{
    private SluggerInterface $slugger;

    private CacheItemPoolInterface $cache;

    private EntityManagerInterface $em;

    public function __construct(SluggerInterface $slugger, CacheItemPoolInterface $cache, EntityManagerInterface $em)
    {
        $this->slugger = $slugger;
        $this->cache = $cache;
        $this->em = $em;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist => 'prePersist',
            Events::preUpdate => 'preUpdate',
            Events::postUpdate => 'postUpdate',
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->preUpdate($args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        if ($args->getEntity() instanceof Technologie) {
            $args->getEntity()->CompleteSlug($this->slugger);
            AdminSkillsController::RemoveSkillCache($args->getEntityManager(), $this->cache);
            AdminMakingController::RemoveMakingCache($args->getEntityManager(), $this->cache);

            /** @var Competence|null $skill */
            $skill = $this->em->getRepository(Competence::class)->findOneBy(['technologie' => $args->getEntity()->getId()]);

            if ($skill) {
                $this->em->persist($skill);
                $skill->preUpdate();
            }
//            dd($skill);
        } elseif ($args->getEntity() instanceof Competence) {
            AdminSkillsController::RemoveSkillCache($args->getEntityManager(), $this->cache);
        } elseif ($args->getEntity() instanceof CompetenceCategorie) {
            AdminSkillsController::RemoveSkillCache($args->getEntityManager(), $this->cache);
        }
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        if ($args->getEntity() instanceof Technologie) {
            /** @var Competence|null $skill */
            $skill = $this->em->getRepository(Competence::class)->findOneBy(['technologie' => $args->getEntity()->getId()]);
            if ($skill) {
                $this->em->persist($skill);
                $skill->preUpdate();
            }
            $this->em->flush();
        } elseif ($args->getEntity() instanceof Competence) {
            $args->getEntity()->postUpdate();
            $this->em->flush();
        } elseif ($args->getEntity() instanceof RealisationImage) {
            $args->getEntity()->postUpdate();
            $this->em->flush();
        }
    }
}
