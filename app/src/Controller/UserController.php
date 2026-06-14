<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\AccountDeleteType;
use App\Form\CategoryDeleteType;
use App\Form\EditAccountType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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

    // TU DODAJ LISTE USEROW DLA ADMINA
    #[Route(
        '/user_list',
        name: 'user_list'
    )]

    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findAll(),
        ]);
    }


     /**
      * Account action
      *
      */


     #[Route(
         '/account',
         name: 'account_index',
         methods: ['GET']
    )]
    #[IsGranted('ROLE_USER')]

    public function showAccount(): Response
    {
        return $this->render('account/index.html.twig');
    }


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

    /**
     * Edit action
     *
     */

    #[Route(
        '/account/edit',
        name: 'account_edit',
        methods: ['GET', 'POST']
    )]
    #[IsGranted('ROLE_USER')]

    public function edit(Request $request): Response
    {
        $user = $this->getUser(); // biore usera z sesji

        $form = $this->createForm(EditAccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('account_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);

    }


    /**
     * Manage users action (only for admin)
     *
     */

    #[Route(
        '/user/{id}/edit',
        name: 'user_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    #[isGranted('ROLE_ADMIN')]

    public function editAsAdmin(Request $request, User $user): Response
    {
        $form = $this->createForm(EditAccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Delete action.
     */

    #[Route(
        '/account/delete',
        name: 'account_delete',
        methods: ['GET', 'POST']
    )]

    public function delete(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(AccountDeleteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 1. najpierw zdejmij użytkownika z security
            $this->container->get('security.token_storage')->setToken(null);

            // 2. dopiero usuń
            $this->userService->delete($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/delete.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }



}
