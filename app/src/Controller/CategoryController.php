<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Category1Type;
use App\Repository\CategoryRepository;
use App\Service\AnswerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;



#[Route(
    '/category'
)]

final class CategoryController extends AbstractController
{

    /**
     * Constructor.
     */

    public function __construct(
        //private readonly CategoryService $categoryService,
        private readonly CategoryRepository $categoryRepository,
        private readonly TranslatorInterface $translator
    ) {}



    /**
     * Index.
     */

    #[Route(
        '/category_list',
        name: 'app_category',
        methods: ['GET']
    )]

    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }


    /**
     * Create action.
     */

    #[Route(
        '/{id}/create',
        name: 'app_category_new',
        methods: ['GET', 'POST']
    )]

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(Category1Type::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }



    #[Route(
        '/{id}/show',
        name: 'app_category_show',
        methods: ['GET']
    )]

    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }



    #[Route(
        '/{id}/edit',
        name: 'app_category_edit',
        methods: ['GET', 'POST']
    )]

    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Category1Type::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }



     /**
      * Delete action.
      */

    #[Route(
        '/{id}/delete',
        name: 'app_category_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['POST']
    )]

    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
