<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerDeleteType;
use App\Form\AnswerType;
use App\Repository\QuestionRepository;
use App\Security\Voter\AnswerVoter;
use App\Security\Voter\QuestionVoter;
use App\Service\AnswerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;



#[Route(
    '/answer'
)]

final class AnswerController extends AbstractController
{

    /**
     * Constructor.
     */

    public function __construct(
        private readonly AnswerService $answerService,
        private readonly TranslatorInterface $translator
    ) {}



    /**
     * Index.
     */

    #[Route(
        '/answer_list',
        name: 'answer_list',
        methods: ['GET']
    )]

    public function index(): Response
    {
        return $this->render('answer/index.html.twig', [
            'controller_name' => 'AnswerController',
        ]);
    }



    /**
     * Create action.
     */

    #[Route(
        '/question/{id}/answer', // tworzone do pytania!!!
        name: 'answer_create',
        methods: ['POST']
    )]
    #[IsGranted('ROLE_USER')]

    public function create(Question $question, Request $request): Response
    {
        $answer = new Answer();

        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $answer->setAuthor($this->getUser());

            $this->answerService->save($answer, $question);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

        }

        // powrót do PYTANIA
        return $this->redirectToRoute('question_view', ['id' => $question->getId()]);
    }

    // nie renderujemy twiga, bo to jest w twigu renderowanym przez Question



    /**
     * Edit action.
     */

    #[Route(
        'answer/{id}/edit',
        name: 'answer_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'PUT']
    )]
    #[IsGranted(AnswerVoter::EDIT, subject: 'answer')]

    public function edit(Request $request, Answer $answer): Response
    {
        $form = $this->createForm(
            AnswerType::class,
            $answer, [
                'method' => 'PUT',
                'action' => $this->generateUrl('answer_edit', ['id' => $answer->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->answerService->save($answer, $answer->getQuestion());

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('question_view', ['id' => $answer->getQuestion()->getId()]); // pobranie id pytania
        }

        return $this->render('answer/edit.html.twig', [
            'form' => $form->createView(),
            'answer' => $answer,
        ]);
    }


    /**
     * Delete action.
     */

    #[Route(
        'answer/{id}/delete',
        name: 'answer_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST', 'DELETE']
    )]
    #[IsGranted(AnswerVoter::DELETE, subject: 'answer')]

    public function delete(Request $request, Answer $answer): Response
    {
        $form = $this->createForm(AnswerDeleteType::class, null, [
            'action' => $this->generateUrl('answer_delete', [
                'id' => $answer->getId()
            ]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->answerService->delete($answer);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('question_view', ['id' => $answer->getQuestion()->getId()]);
        }

        return $this->render('answer/delete.html.twig', [
            'form' => $form->createView(),
            'question' => $answer,
        ]);
    }


    /**
     * Mark as best action.
     */

    #[Route(
    '/{id}/best',
    name: 'answer_best',
    requirements: ['id' => '[1-9]\d*'],
    methods: ['GET', 'POST']
    )]
    #[IsGranted(AnswerVoter::MARK_AS_BEST, subject: 'answer')]

    public function markAsBest(Answer $answer, QuestionRepository $questionRepository): Response
    {
        $this->answerService->markAsBest($answer, $questionRepository);

        $this->addFlash(
            'success',
            'Najlepsza odpowiedź została wybrana.'
        );

        return $this->redirectToRoute('question_view', ['id'=>$answer->getQuestion()->getId()]);


    }

}
