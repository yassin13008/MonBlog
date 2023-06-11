<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route("/post/{slug}", name:"post.index")]
    public function index($slug, PostRepository $postRepo): Response
    {


        $post = $postRepo->findBySlug($slug);



        return $this->render('post/index.html.twig', [
            'post' => $post[0],
        ]);
    }
}
