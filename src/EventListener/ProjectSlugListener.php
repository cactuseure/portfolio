<?php

namespace App\EventListener;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProjectSlugListener
{
    private SluggerInterface $slugger;
    private EntityManagerInterface $entityManager;

    public function __construct(SluggerInterface $slugger, EntityManagerInterface $entityManager)
    {
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
    }

    public function prePersist(LifecycleEventArgs $event): void
    {
        $entity = $event->getObject();
        if ($entity instanceof Project) {
            $this->handleSlug($entity);
        }
    }

    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $entity = $event->getObject();
        if ($entity instanceof Project) {
            $this->handleSlug($entity);
        }
    }

    private function handleSlug(Project $project): void
    {
        if (empty($project->getSlug())) {
            $slug = $this->slugger->slug($project->getTitle())->lower();
            $originalSlug = $slug;

            $counter = 1;
            while ($this->isSlugTaken($slug)) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $project->setSlug($slug);
        }
    }

    private function isSlugTaken(string $slug): bool
    {
        return $this->entityManager->getRepository(Project::class)->findOneBy(['slug' => $slug]) !== null;
    }
}