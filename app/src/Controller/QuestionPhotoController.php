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

use App\Contract\QuestionPhotoServiceInterface;
use App\Entity\Question;
use App\Entity\QuestionPhoto;
use App\Form\QuestionPhotoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class QuestionPhotoController.
 */
#[Route('/question/{id}/photo')]
class QuestionPhotoController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param QuestionPhotoServiceInterface $photoService Photo service
     * @param TranslatorInterface           $translator   Translator
     */
    public function __construct(private readonly QuestionPhotoServiceInterface $photoService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Create action.
     *
     * @param Request  $request  HTTP request
     * @param Question $question Question entity
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'question_photo_create', methods: 'GET|POST')]
    public function create(Request $request, Question $question): Response
    {
        if ($this->photoService->findOneByQuestion($question)) {
            return $this->redirectToRoute('question_photo_edit', ['id' => $question->getId()]);
        }

        $photo = new QuestionPhoto();
        $form = $this->createForm(
            QuestionPhotoType::class,
            $photo,
            ['action' => $this->generateUrl('question_photo_create', ['id' => $question->getId()])]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            $this->photoService->create($file, $photo, $question);

            $this->addFlash('success', $this->translator->trans('message.photo_created_successfully'));

            return $this->redirectToRoute('question_view', ['id' => $question->getId()]); // przekierowanie na podgląd pytania
        }

        return $this->render('question_photo/create.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
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
    #[Route('/edit', name: 'question_photo_edit', methods: 'GET|PUT')]
    public function edit(Request $request, Question $question): Response
    {
        $photo = $this->photoService->findOneByQuestion($question);

        if (!$photo) {
            return $this->redirectToRoute('question_photo_create', ['id' => $question->getId()]);
        }

        $form = $this->createForm(
            QuestionPhotoType::class,
            $photo,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('question_photo_edit', ['id' => $question->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            $this->photoService->update($file, $photo, $question);

            $this->addFlash('success', $this->translator->trans('message.photo_edited_successfully'));

            return $this->redirectToRoute('question_view', ['id' => $question->getId()]);
        }

        return $this->render('question_photo/edit.html.twig', [
            'form' => $form->createView(),
            'photo' => $photo,
            'question' => $question,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Question $question Question entity
     *
     * @return Response HTTP response
     */
    #[Route('/delete', name: 'question_photo_delete', methods: 'GET|PUT|POST')]
    public function delete(Question $question): Response
    {
        $this->photoService->delete($question);

        $this->addFlash('success', $this->translator->trans('message.photo_deleted_successfully'));

        return $this->redirectToRoute('question_view', ['id' => $question->getId()]);
    }
}
