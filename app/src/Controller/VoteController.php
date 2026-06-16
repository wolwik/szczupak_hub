<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Service\VoteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

final class VoteController extends AbstractController
{

    public function __construct(
        private readonly VoteService $voteService,
        private readonly TranslatorInterface $translator,
    ) {}



    #[Route(
        '/vote', name:
        'app_vote'
    )]
    public function index(): Response
    {
        return $this->render('vote/index.html.twig', [
            'controller_name' => 'VoteController',
        ]);
    }


    #[Route(
        '/answer/{id}/vote',
        name: 'answer_vote'
    )]
    #[IsGranted('ROLE_USER')]

    public function vote(Answer $answer): Response
    {
        $user = $this->getUser();

        // sprawdzenie obiektu i zawężenie typu, bo symfony się denerwuje
        if (!$user instanceof \App\Entity\User) {
            throw new \LogicException();
        }

        $this->voteService->vote($answer, $user);

        $this->addFlash(
            'success',
            $this->translator->trans('message.voted_successfully')
        );

        return $this->redirectToRoute('question_view', [
            'id' => $answer->getQuestion()->getId()
        ]);
    }
}
