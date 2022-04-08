<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route ("/category")
 */
class CategoryController extends AbstractController
{

    private $_em;

    public function __construct(ManagerRegistry $registry)
    {
        $this->_em = $registry;
    }

    
    /**
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(int $id): Response
    {

        $category = $this->_em->getRepository(Category::class);
        $theCategory = $category->find($id);

        return $this->render(
            'category/show.html.twig',
            [
                'category' => $theCategory,
            ]
        );
    }

    /**
     * @Route("/new", name="new_category", methods={"GET", "POST"})
     */
    public function newPost(Request $request): Response
    {
        $category =  new Category();

        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->_em->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_index'); 
        }
        return $this->render('category/new.html.twig', [
            'category' => $category, 
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/{id}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category)
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->_em->getManager()->flush();
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/{id}", name="category_delete", methods={"POST"} )
     */
    public function delete(Request $request, Category $category)
    {
        $this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'));
        $entityManager = $this->_em->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('category_index');
    }


}



