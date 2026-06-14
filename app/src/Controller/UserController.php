<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


#[Route(
    '/user'
)]

final class UserController extends AbstractController
{

    public function __construct(
        private readonly UserService $userService,
        private readonly TranslatorInterface $translator,
        private readonly UserRepository $userRepository,
    ) {}

    /**
     * Index.
     */

    #[Route(
        '/user_index',
        name: 'user_index'
    )]

    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


     /**
      * View action.
      *
      */

     /*
     #[Route(
         '/{id}/show',
         name: 'user_view',
         requirements: ['id' => '[1-9]\d*'],
         methods: ['GET']
    )]

    public function show(User $user): Response
    {

    }
     */

    /**
     * Registration
     *
     */


    #[Route(
        '/register',
        name: 'register',
        methods: ['GET', 'POST']
    )]

    public function register(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('password')->getData();

            $this->userService->register($user, $plainPassword);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
