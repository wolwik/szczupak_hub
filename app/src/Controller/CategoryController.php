<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Category1Type;
use App\Form\CategoryDeleteType;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
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
        private readonly CategoryService $categoryService,
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
        '/create',
        name: 'app_category_new',
        methods: ['GET', 'POST']
    )]

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(Category1Type::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->categoryRepository->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.success.new_category')
            );

            return $this->redirectToRoute('app_category', [], Response::HTTP_SEE_OTHER);
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
        methods: ['GET', 'PUT']
    )]

    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(
            Category1Type::class,
            $category,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('app_category_edit', ['id' => $category->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryRepository->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('app_category', ['id' => $category->getId()], Response::HTTP_SEE_OTHER);
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
        methods: ['GET', 'POST', 'DELETE']
    )]

    public function delete(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryDeleteType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->redirectToRoute('app_category');
        }

        $deleted = $this->categoryService->delete($category);

        if ($deleted) {
            $this->addFlash('success', 'Kategoria została usunięta.');
        } else {
            $this->addFlash('error', 'Nie można usunąć kategorii, ponieważ ma przypisane pytania.');
        }

        return $this->redirectToRoute('app_category');
    }
}
