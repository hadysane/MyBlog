<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    private $_em;

    public function __construct(ManagerRegistry $registry)
    {
        $this->_em = $registry;
    }


    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }  
    
}
