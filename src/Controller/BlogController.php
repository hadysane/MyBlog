<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/blog" )
 */
class BlogController extends AbstractController
{
    #[Route('/', name: 'blog_index')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }
}
