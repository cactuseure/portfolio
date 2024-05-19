<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_index')]
    public function index(ProjectRepository $projectRepository,ArticleRepository $articleRepository): Response
    {
        // Récupérer les 3 derniers projets
        $projects = $projectRepository->findBy([], ['projectDate' => 'DESC'], 3);
        $articles = $articleRepository->findBy([], ['createdAt' => 'DESC'], 2);
        return $this->render('home/index.html.twig', [
            'projects' => $projects,
            'articles' => $articles,
            'isHomepage' => true,
        ]);
    }
}
