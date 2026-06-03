<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Category1Type;
use App\Form\CategoryDeleteType;
use App\Form\CategoryType;
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
        private readonly CategoryRepository $categoryRepository,
        private readonly CategoryService $categoryService,
        private readonly TranslatorInterface $translator
    ) {}



    /**
     * Index.
     */

    #[Route(
        '/category_list',
        name: 'category_list',
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
        name: 'category_new',
        methods: ['GET', 'POST']
    )]

    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->categoryRepository->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.success.new_category')
            );

            return $this->redirectToRoute('category_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }



    #[Route(
        '/{id}/show',
        name: 'category_show',
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
        name: 'category_edit',
        methods: ['GET', 'PUT']
    )]

    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(
            CategoryType::class,
            $category,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('category_edit', ['id' => $category->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryRepository->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('category_show', ['id' => $category->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }



     /**
      * Delete action.
      */

    #[Route(
        '/{id}/delete',
        name: 'category_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST', 'DELETE']
    )]

    public function delete(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryDeleteType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {

            $deleted = $this->categoryService->delete($category);

            if ($deleted) {
                $this->addFlash('success', 'Kategoria została usunięta.');
            } else {
                $this->addFlash('error', 'Nie można usunąć kategorii, ponieważ ma przypisane pytania.');
            }

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/delete.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }


}
