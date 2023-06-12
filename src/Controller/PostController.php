<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route("/post/{slug}", name:"post.index")]
    public function index(Post $post, Request $request, EntityManagerInterface $manager): Response
    {
       $form = $this->createForm(CommentType::class);
       $form->handleRequest($request);
    
       if ($form->isSubmitted() && $form->isValid()) {
    
           $comment = $form->getData();
           $comment->setPost($post);
    
           $manager->persist($comment);
           $manager->flush();
    
           $this->addFlash('success', 'Votre commentaire est créé.');
    
           return $this->redirect($request->headers->get('referer'));
       }
    
       return $this->render('post/index.html.twig', [
           'post' => $post,
           'form' => $form->createView()
       ]);
    }
    
}
