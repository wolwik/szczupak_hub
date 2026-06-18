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
use App\Form\AdminChangePasswordFormType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class SecurityController.
 */
class SecurityController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator Translator
     */
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Login action.
     *
     * @param AuthenticationUtils $authenticationUtils Authentication utilities
     *
     * @return Response HTTP response
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if user is an instance of an interface, do not show login screen
        if ($this->getUser() instanceof UserInterface) {
            return $this->redirectToRoute('question_list');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Logout action.
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Changes own password.
     *
     * @param Request                     $request HTTP request
     * @param UserPasswordHasherInterface $hasher  Password hasher
     * @param EntityManagerInterface      $em      Entity manager
     *
     * @return Response HTTP response
     *
     * @throws \LogicException When user is not authenticated
     * @throws \Exception      When current password is invalid
     */
    #[Route('/change-password', name: 'change_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user instanceof PasswordAuthenticatedUserInterface) {
            throw new \LogicException('Brak zalogowanego użytkownika');
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!$hasher->isPasswordValid($user, $data['currentPassword'])) {
                throw new \Exception('Błędne hasło');
            }

            $user->setPassword(
                $hasher->hashPassword($user, $data['newPassword'])
            );

            $em->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('message.password_changed_successfully')
            );

            return $this->redirectToRoute('account_index');
        }

        return $this->render('security/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Changes anyone's password, action permitted only for admins.
     *
     * @param User                        $user    User entity
     * @param Request                     $request HTTP request
     * @param UserPasswordHasherInterface $hasher  Password hasher
     * @param EntityManagerInterface      $em      Entity manager
     *
     * @return Response HTTP response
     */
    #[Route('/user/{id}/change-password', name: 'user_change_password')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminChangePassword(User $user, Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AdminChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();

            $user->setPassword(
                $hasher->hashPassword($user, $newPassword)
            );

            $em->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('message.password_changed_successfully')
            );

            return $this->redirectToRoute('user_list');
        }

        return $this->render('security/admin_change_password.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
