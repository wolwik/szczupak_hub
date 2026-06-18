<?php

namespace App\Controller;

use App\Contract\CategoryServiceInterface;
use App\Entity\Category;
use App\Form\CategoryDeleteType;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * Class CategoryController.
 */

#[Route(
    '/category'
)]
#[IsGranted('ROLE_ADMIN')]
final class CategoryController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param CategoryServiceInterface   $categoryService     Category service
     * @param TranslatorInterface        $translator          Translator
     */

    public function __construct(
        private readonly CategoryServiceInterface $categoryService,
        private readonly TranslatorInterface $translator
    ) {}


    /**
     * Displays list of categories.
     *
     * @return Response HTTP response
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
     * Creates a new category.
     *
     * @param Request $request HTTP request
     *
     * @return Response Redirect response to question view
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

            $this->categoryService->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.category_created_successfully')
            );

            return $this->redirectToRoute('category_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }


    /**
     * Edits a category.
     *
     * @param Request  $request  HTTP request
     * @param Category $category HTTP request
     *
     * @return Response Rendered page or redirect response
     */

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

            $this->categoryService->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.category_edited_successfully')
            );

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }


    /**
     * Deletes a category.
     *
     * @param Request   $request HTTP request
     * @param Category  $category  Answer entity to delete
     *
     * @return Response Redirect or rendered confirmation page
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
                $this->addFlash(
                    'success',
                    $this->translator->trans('message.category_deleted_successfully')
                );
            } else {
                $this->addFlash(
                    'warning',
                    $this->translator->trans('message.category_deleted_unsuccessfully')
                );
            }

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/delete.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

}
