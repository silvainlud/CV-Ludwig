<?php

namespace App\EventSubscriber\Doctrine\CV;

use App\Entity\Main\CV\Competence;
use App\Entity\Main\CV\RealisationImage;
use App\Entity\Main\CV\Technologie;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class CompetenceSubscriber implements EventSubscriberInterface
{
    private SluggerInterface $slugger;

    private AdapterInterface $cache;

    private EntityManagerInterface $em;

    public function __construct(SluggerInterface $slugger, AdapterInterface $cache, EntityManagerInterface $em)
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
