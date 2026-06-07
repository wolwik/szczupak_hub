<?php

namespace App\Controller;

use App\Security\Voter\AnswerVoter;
use App\Security\Voter\QuestionVoter;
use App\Service\AnswerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

final class AccountController extends AbstractController
{

    /**
     * Index.
     */

    #[Route(
        '/account',
        name: 'account_index'
    )]

    #[IsGranted('ROLE_USER')]

    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

}

