<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\AnswerType;
use App\Form\QuestionDeleteType;
use App\Form\QuestionType;
use App\Repository\CategoryRepository;
use App\Service\QuestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Contracts\Translation\TranslatorInterface;



#[Route(
    '/question'
)]

final class QuestionController extends AbstractController {

    /**
     * Constructor.
     *
     * @param QuestionServiceInterface $questionService Question service
     * @param TranslatorInterface      $translator      Translator
     */
    public function __construct(
        private readonly QuestionService $questionService,
        private readonly TranslatorInterface $translator,
        private readonly CategoryRepository $categoryRepository
    ) {}



    /**
     * Index.
     */

    #[Route(
        '/question_list',
        name: 'question_list',
        methods: ['GET']
    )]

    public function index(
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $categoryId = null): Response
    {
        $pagination = $this->questionService->getPaginatedList($page, $categoryId);

        $categories = $this->categoryRepository->findAll();

        return $this->render('question/index.html.twig', [
            'pagination' => $pagination,
            'categories' => $categories,
            'categoryId' => $categoryId,
        ]);
    }



    /**
     * View action.
     *
     * @param Question $question Question entity
     *
     * @return Response HTTP response
     */

    #[Route(
        '/{id}/show',
        name: 'question_view',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET']
    )]

    public function view(Question $question): Response {

        // tworzymy zmienną z formularza dla Answer
        $form = $this->createForm(AnswerType::class);

        return $this->render('question/view.html.twig', [
            'question' => $question,
            'answerForm' => $form->createView(),
        ]);
    }



    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */

    #[Route(
        '/create',
        name: 'question_create',
        methods: ['GET', 'POST']
    )]
    public function create(Request $request): Response
    {
        $question = new Question();

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->questionService->save($question);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('question_list');

        }

        return $this->render('question/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * Edit action.
     *
     * @param Request  $request  HTTP request
     * @param Question $question Question entity
     *
     * @return Response HTTP response
     */

    #[Route(
        '/{id}/edit',
        name: 'question_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'PUT']
    )]

    public function edit(Request $request, Question $question): Response
    {
        $form = $this->createForm(
            QuestionType::class,
            $question,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('question_edit', ['id' => $question->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->questionService->save($question);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('question_view', ['id' => $question->getId()]);

        }

        return $this->render(
            'question/edit.html.twig',
            [
                'form' => $form->createView(),
                'question' => $question,
            ]
        );
    }




    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param Question $question Question entity
     *
     * @return Response HTTP response
     */

    #[Route(
        '/{id}/delete',
        name: 'question_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST', 'DELETE']
    )]
    public function delete(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionDeleteType::class, null, [
            'action' => $this->generateUrl('question_delete', [
                'id' => $question->getId()
            ]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->questionService->delete($question);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('question_list');
        }

        return $this->render('question/delete.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
        ]);
    }
}

