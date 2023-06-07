<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home.index')]
    public function index(PostRepository $postRepo ): Response
    {
        $posts = $postRepo->findAll();


        return $this->render('home/index.html.twig', [
            'posts' => $posts // Et je renvoies le tableau posts (tableau contenant mes 10 articles )
        ]);
    }
}
