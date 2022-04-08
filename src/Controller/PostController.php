<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Builder\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/post" )
 */
class PostController extends AbstractController
{
    private $_em;

    public function __construct(ManagerRegistry $registry)
    {
        $this->_em = $registry;
    }

    
    // all posts
    #[Route('/', name: 'post_index', methods:['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

   

    /**
    * @Route("/new", name="new_post", methods={"GET", "POST"})
    */
    public function createPost(Request $request)
    {
        $post = new Post();
        // $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->_em->getManager();
            // $post->setAuthor($user);
          
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_index'); 
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
    * @Route ("/{id}/edit", name="post_edit", methods={"GET","POST"})
    * 
    */
    public function edit(Request $request, Post $post)
    {
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->_em->getManager()->flush();
            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/{id}/delete", name="post_delete", methods={"DELETE"} )
     */
    public function delete(Request $request, Post $post)
    {

        //permet de valider le token pour ne pas confondre avec le show product 
        $this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'));
        $entityManager = $this->_em->getManager();
        $entityManager->remove($post);
        $entityManager->flush();
        return $this->redirectToRoute('post_index');
    }


    /**
     * @Route("/{slug}", name="post_show", methods={"GET"})
     */
    public function show(string  $slug): Response
    {
        $post = $this->_em->getRepository(Post::class);
        $thePost = $post->findOneBy(['slug' => $slug]);

        return $this->render(
            'post/show.html.twig',
            [
                'post' => $thePost,
            ]
        );
    }

}
