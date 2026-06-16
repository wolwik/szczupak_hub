<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountDeleteType;
use App\Form\EditAccountType;
use App\Form\RegistrationType;
use App\Form\UserDeleteType;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Service\QuestionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * Class UserController.
 */

#[Route(
    '/user'
)]

final class UserController extends AbstractController
{

    /**
     * Constructor.
     *
     * @param UserService          $userService      User service
     * @param TranslatorInterface  $translator       Translator
     * @param UserRepository       $userRepository   User repository
     * @param QuestionService      $questionService  Question service
     */

    public function __construct(
        private readonly UserService $userService,
        private readonly TranslatorInterface $translator,
        private readonly UserRepository $userRepository,
        private readonly QuestionService $questionService,
    ) {}



    /**
     * Displays list of users.
     *
     * @return Response Rendered questions list page
     */

    #[Route(
        '/user_list',
        name: 'user_list'
    )]
    #[IsGranted('ROLE_ADMIN')]

    public function index(): Response
    {
        // show everyone but NOT currently logged admin
        $currentUser = $this->getUser();

        // sprawdzenie obiektu i zawężenie typu, bo symfony się denerwuje
        if (!$currentUser instanceof \App\Entity\User) {
            throw new \LogicException();
        }

        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findAllExceptCurrentUser($currentUser->getId()),
        ]);
    }


     /**
      * Account panel with user's info and article drafts.
      *
      * @return Response HTTP response
      */

     #[Route(
         '/account',
         name: 'account_index',
         methods: ['GET']
    )]
    #[IsGranted('ROLE_USER')]

    public function showAccount(): Response
    {
        $user = $this->getUser();

        // Expected parameter of type '\App\Entity\User', 'null|\Symfony\Component\Security\Core\User\UserInterface' provided
        if (!$user instanceof \App\Entity\User) {
            throw new \LogicException();
        }

        $drafts = $this->questionService->getUserDrafts($user);

        return $this->render('account/index.html.twig', [
            'drafts' => $drafts,
        ]);
    }


    /**
     * Account registration.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     * @throws \Exception
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
                $this->translator->trans('message.user_registered_successfully')
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * Edits currently logged-in user's data.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */

    #[Route(
        '/account/edit',
        name: 'account_edit',
        methods: ['GET', 'POST']
    )]
    #[IsGranted('ROLE_USER')]

    public function edit(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user instanceof \App\Entity\User) {
            throw new \LogicException();
        }

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
     * Edits anyone's data as administrator.
     *
     * @param Request $request HTTP request
     * @param User    $user    HTTP request
     *
     * @return Response HTTP response
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

        return $this->render('user/edit_as_admin.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * Deletes currently logged-in user.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */

    #[Route(
        '/account/delete',
        name: 'account_delete',
        methods: ['GET', 'POST']
    )]

    public function delete(Request $request): Response
    {
        $user = $this->getUser();

        // Expected parameter of type '\App\Entity\User', 'null|\Symfony\Component\Security\Core\User\UserInterface' provided
        if (!$user instanceof \App\Entity\User) {
            throw new \LogicException();
        }

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



    /**
     * Deletes a user account as administrator.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     *
     * @throws AccessDeniedException When admin tries to delete their own account
     */

    #[Route(
        '/user/{id}/delete',
        name: 'user_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    #[IsGranted('ROLE_ADMIN')]

    public function deleteAsAdmin(Request $request, User $user): Response
    {
        // admin nie może usunąć samego siebie
        if ($this->getUser() === $user) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(UserDeleteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->userService->delete($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('user_list');

        }

        return $this->render('user/delete.html.twig', [
            'form' => $form->createView(),
        ]);

    }



    /**
     * Grants administrator role to a user.
     *
     * @param User  $user  User entity
     *
     * @return Response HTTP response
     */

    #[Route(
        '/user/{id}/make-admin',
        name:'user_make_admin',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    #[IsGranted('ROLE_ADMIN')]

    public function makeAdmin(User $user): Response
    {
        $user->promoteToAdmin();

        $this->userService->save($user);

        $this->addFlash(
            'success',
            $this->translator->trans('message.edited_successfully')
        );

        return $this->redirectToRoute('user_list');
    }



    /**
     * Removes administrator role from a user.
     *
     * @param User  $user  User entity
     *
     * @return Response HTTP response
     */

    #[Route(
        '/user/{id}/remove-admin',
        name:'user_remove_admin',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    #[IsGranted('ROLE_ADMIN')]

    public function removeAdmin(User $user): Response
    {
        $admins = $this->userRepository->findAdmins();

        if(count($admins) <= 1) {
            throw new AccessDeniedException('Cannot remove last admin');
        };

        $user->removeAdminRole();

        $this->userService->save($user);

        $this->addFlash(
            'success',
            $this->translator->trans('message.removed_successfully')
        );

        return $this->redirectToRoute('user_list');

    }


    /**
     * Blocks user.
     *
     * @param User  $user  User entity
     *
     * @return Response HTTP response
     */

    #[Route(
        '/user/{id}/block',
        name:'user_block_admin',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    #[IsGranted('ROLE_ADMIN')]

    public function block(Request $request, User $user): Response
    {
        $user->setIsBlocked(true);
        $this->userService->save($user);

        $this->addFlash(
            'success',
            $this->translator->trans('message.blocked_successfully')
        );

        return $this->redirectToRoute('user_list');
    }


    /**
     * Unblocks user.
     *
     * @param User  $user  User entity
     *
     * @return Response HTTP response
     */

    #[Route(
        '/user/{id}/unblock',
        name:'user_unblock_admin',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    #[IsGranted('ROLE_ADMIN')]

    public function unblock(User $user): Response
    {
        $user->setIsBlocked(false);
        $this->userService->save($user);

        $this->addFlash(
            'success',
            $this->translator->trans('message.unblocked_successfully')
        );

        return $this->redirectToRoute('user_list');

    }

}
