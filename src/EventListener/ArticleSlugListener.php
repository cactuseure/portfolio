<?php

namespace App\EventListener;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleSlugListener
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
        $article = $event->getObject();
        if ($article instanceof Article) {
            $this->handleSlug($article);
        }
    }

    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $entity = $event->getObject();
        if ($entity instanceof Article) {
            $this->handleSlug($entity);
        }
    }

    private function handleSlug(Article $article): void
    {
        if (empty($article->getSlug())) {
            $slug = $this->slugger->slug($article->getTitle())->lower();
            $originalSlug = $slug;

            $counter = 1;
            while ($this->isSlugTaken($slug)) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $article->setSlug($slug);
        }
    }

    private function isSlugTaken(string $slug): bool
    {
        return $this->entityManager->getRepository(Article::class)->findOneBy(['slug' => $slug]) !== null;
    }
}