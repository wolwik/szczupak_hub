<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Wolwik / UJ
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\Answer;
use App\Contract\VoteServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class VoteController.
 */
final class VoteController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param VoteServiceInterface $voteService Vote service
     */
    public function __construct(private readonly VoteServiceInterface $voteService)
    {
    }

    /**
     * Handles the voting logic for a specific answer.
     *
     * @param Answer $answer Answer service
     *
     * @return Response HTTP response
     *
     * @throws \LogicException if the user is not authenticated properly
     */
    #[Route('/answer/{id}/vote', name: 'answer_vote')]
    #[IsGranted('ROLE_USER')]
    public function vote(Answer $answer): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \LogicException();
        }

        $this->voteService->vote($answer, $user);

        return $this->redirectToRoute('question_view', [
            'id' => $answer->getQuestion()->getId(),
        ]);
    }
}
