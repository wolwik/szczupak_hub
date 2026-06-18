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

use Symfony\Component\Routing\Attribute\Route;
use App\Contract\AvatarServiceInterface;
use App\Entity\Avatar;
use App\Entity\User;
use App\Form\AvatarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AvatarController.
 */
#[Route('/avatar')]
class AvatarController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param AvatarServiceInterface $avatarService Avatar service
     * @param TranslatorInterface    $translator    Translator
     */
    public function __construct(private readonly AvatarServiceInterface $avatarService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'avatar_create', methods: 'GET|POST')]
    public function create(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \LogicException();
        }

        if ($user->getAvatar()) {
            return $this->redirectToRoute('avatar_edit');
        }

        $avatar = new Avatar();
        $form = $this->createForm(
            AvatarType::class,
            $avatar,
            ['action' => $this->generateUrl('avatar_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $this->avatarService->create(
                $file,
                $avatar,
                $user
            );

            $this->addFlash(
                'success',
                $this->translator->trans('message.avatar_created_successfully')
            );

            return $this->redirectToRoute('account_index');
        }

        return $this->render(
            'avatar/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/edit', name: 'avatar_edit', methods: 'GET|PUT')]
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \LogicException();
        }

        $avatar = $user->getAvatar();

        if (!$avatar) {
            return $this->redirectToRoute('avatar_create');
        }

        $form = $this->createForm(
            AvatarType::class,
            $avatar,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('avatar_edit'),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $this->avatarService->update(
                $file,
                $avatar,
                $user
            );

            $this->addFlash(
                'success',
                $this->translator->trans('message.avatar_edited_successfully')
            );

            return $this->redirectToRoute('account_index');
        }

        return $this->render(
            'avatar/edit.html.twig',
            [
                'form' => $form->createView(),
                'avatar' => $avatar,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @return Response HTTP response
     */
    #[Route('/delete', name: 'avatar_delete', methods: 'GET|PUT|POST')]
    public function delete(): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \LogicException();
        }

        $this->avatarService->delete($user);

        $this->addFlash(
            'success',
            $this->translator->trans('message.avatar_deleted_successfully')
        );

        return $this->redirectToRoute('account_index');
    }
}
