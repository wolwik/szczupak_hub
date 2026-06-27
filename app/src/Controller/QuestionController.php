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

use App\Contract\AnswerServiceInterface;
use App\Contract\QuestionPhotoServiceInterface;
use App\Contract\QuestionServiceInterface;
use App\Contract\TagServiceInterface;
use App\Entity\Enum\QuestionStatus;
use App\Entity\Question;
use App\Entity\QuestionPhoto;
use App\Form\AnswerType;
use App\Form\QuestionDeleteType;
use App\Form\QuestionType;
use App\Repository\AvatarRepository;
use App\Repository\CategoryRepository;
use App\Repository\QuestionPhotoRepository;
use App\Security\Voter\QuestionVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class QuestionController.
 */
final class QuestionController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param QuestionServiceInterface $questionService    Question service
     * @param TranslatorInterface      $translator         Translator
     * @param CategoryRepository       $categoryRepository Category repository
     */
    public function __construct(private readonly QuestionServiceInterface $questionService, private readonly TranslatorInterface $translator, private readonly CategoryRepository $categoryRepository)
    {
    }

    /**
     * Displays paginated list of question-photos with optional filters.
     *
     * @param AvatarRepository        $avatarRepository        Avatar repository
     * @param QuestionPhotoRepository $questionPhotoRepository Question photo repository
     * @param int                     $page                    Current page number (default: 1)
     * @param int|null                $categoryId              Optional category filter
     * @param int|null                $tag                     Optional tag filter
     *
     * @return Response Rendered question-photos list page
     */
    #[Route('/', name: 'question_list', methods: ['GET'])]
    public function index(AvatarRepository $avatarRepository, QuestionPhotoRepository $questionPhotoRepository, #[MapQueryParameter] int $page = 1, #[MapQueryParameter] ?int $categoryId = null, #[MapQueryParameter] ?int $tag = null): Response
    {
        $pagination = $this->questionService->getPaginatedList($page, $categoryId, $tag);
        $categories = $this->categoryRepository->findAll();

        // optymalizacja zapytan
        $avatars = [];
        $photos = [];
        $normalizedQuestions = [];

        $questionIds = [];
        $authorIds = [];

        foreach ($pagination->getItems() as $item) {
            $question = is_array($item) ? ($item[0] ?? null) : $item;

            if ($question instanceof Question) {
                $normalizedQuestions[] = $question;
                $questionIds[] = $question->getId();

                if ($question->getAuthor()) {
                    $authorIds[] = $question->getAuthor()->getId();
                }
            }
        }

        if (!empty($authorIds)) {
            $authorIds = array_unique($authorIds);

            $rawAvatars = $avatarRepository->findBy(['user' => $authorIds]);
            foreach ($rawAvatars as $avatar) {
                $avatars[$avatar->getUser()->getId()] = $avatar->getFilename();
            }
        }

        if (!empty($questionIds)) {
            $rawPhotos = $questionPhotoRepository->findBy(['question' => $questionIds]);
            foreach ($rawPhotos as $photo) {
                $photos[$photo->getQuestion()->getId()] = $photo->getFilename();
            }
        }

        return $this->render('question/index.html.twig', [
            'pagination' => $pagination,
            'questions' => $normalizedQuestions,
            'categories' => $categories,
            'categoryId' => $categoryId,
            'avatars' => $avatars,
            'photos' => $photos,
        ]);
    }

    /**
     * Single question's view.
     *
     * @param Question                $question                Question entity
     * @param AvatarRepository        $avatarRepository        Avatar repository
     * @param QuestionPhotoRepository $questionPhotoRepository Question photo repository
     * @param AnswerServiceInterface  $answerService           Answer service
     *
     * @return Response HTTP response
     */
    #[Route('/question/{id}/show', name: 'question_view', requirements: ['id' => '[1-9]\d*'], methods: ['GET'])]
    public function view(Question $question, AvatarRepository $avatarRepository, QuestionPhotoRepository $questionPhotoRepository, AnswerServiceInterface $answerService): Response
    {
        $this->denyAccessUnlessGranted(QuestionVoter::VIEW, $question);

        $answers = $this->questionService->getAnswersForQuestion($question);

        $avatars = [];
        $authorIds = $question->getAuthor() ? [$question->getAuthor()->getId()] : [];
        foreach ($answers as $answer) {
            if ($answer->getAuthor()) {
                $authorIds[] = $answer->getAuthor()->getId();
            }
        }
        if (!empty($authorIds)) {
            $rawAvatars = $avatarRepository->findBy(['user' => array_unique($authorIds)]);
            foreach ($rawAvatars as $avatar) {
                $avatars[$avatar->getUser()->getId()] = $avatar->getFilename();
            }
        }

        $questionPhoto = $questionPhotoRepository->findOneBy(['question' => $question]);
        $photoFilename = $questionPhoto ? $questionPhoto->getFilename() : null;

        $votesMap = $answerService->getVotesMapForAnswers($answers);

        $form = $this->createForm(AnswerType::class);

        return $this->render('question/view.html.twig', [
            'question' => $question,
            'answers' => $answers,
            'answerForm' => $form->createView(),
            'avatars' => $avatars,
            'photoFilename' => $photoFilename,
            'votesMap' => $votesMap,
        ]);
    }

    /**
     * Creates a new question.
     *
     * @param Request                       $request      HTTP request
     * @param TagServiceInterface           $tagService   Tag service
     * @param QuestionPhotoServiceInterface $photoService QuestionPhoto service
     *
     * @return Response HTTP response
     */
    #[Route('/question/create', name: 'question_create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, TagServiceInterface $tagService, QuestionPhotoServiceInterface $photoService): Response
    {
        $question = new Question();

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setAuthor($this->getUser());

            // creating tags
            $tagsString = $form->get('tags')->getData();
            $tags = $tagService->createFromString($tagsString);
            foreach ($tags as $tag) {
                $question->addTag($tag);
            }

            $this->questionService->save($question);

            // adding photo
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $questionPhoto = new QuestionPhoto();
                $photoService->create($photoFile, $questionPhoto, $question);
            }

            $this->addFlash(
                'success',
                $this->translator->trans('message.question_created_successfully')
            );

            return $this->redirectToRoute('question_view', ['id' => $question->getId()]);
        }

        return $this->render('question/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Publishes question.
     *
     * @param Question $question Related question entity
     *
     * @return Response Redirect response to question view
     */
    #[Route('/question/{id}/publish', name: 'question_publish', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function publish(Question $question): Response
    {
        $question->setStatus(QuestionStatus::PUBLISHED);

        $this->questionService->save($question);

        $this->addFlash(
            'success',
            $this->translator->trans('message.question_published_successfully')
        );

        return $this->redirectToRoute('question_view', ['id' => $question->getId()]);
    }

    /**
     * Edits question.
     *
     * @param Request                       $request      HTTP request
     * @param Question                      $question     Question entity
     * @param TagServiceInterface           $tagService   Tag service
     * @param QuestionPhotoServiceInterface $photoService QuestionPhoto service
     *
     * @return Response HTTP response
     */
    #[Route('/question/{id}/edit', name: 'question_edit', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'PUT'])]
    #[IsGranted(QuestionVoter::EDIT, subject: 'question')]
    public function edit(Request $request, Question $question, TagServiceInterface $tagService, QuestionPhotoServiceInterface $photoService): Response
    {
        // dodajemy obecne tagi do formularza
        $existingTags = implode(', ', array_map(
            fn ($tag) => $tag->getName(),
            $question->getTags()->toArray()
        ));

        // usuwamy stare tagi ZANIM formularz odpali walidację!
        if ($request->isMethod('POST') || $request->isMethod('PUT')) {
            $question->clearTags();
        }

        $form = $this->createForm(
            QuestionType::class,
            $question,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('question_edit', ['id' => $question->getId()]),
            ]
        );
        // dodajemy obecne tagi do formularza
        $form->get('tags')->setData($existingTags);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Pobierz wpis użytkownika
            $tagsString = $form->get('tags')->getData();

            // Stwórz / pobierz tagi
            $tags = $tagService->createFromString($tagsString);

            // Dodaj nowe tagi
            foreach ($tags as $tag) {
                $question->addTag($tag);
            }

            $this->questionService->save($question);

            // editing photo
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                // sprawdzamy czy pytanie mialo wczesniej zdj
                $existingPhoto = $photoService->findOneByQuestion($question);

                if ($existingPhoto) {
                    // jeśli tak, podmieniamy plik
                    $photoService->update($photoFile, $existingPhoto, $question);
                } else {
                    // jeśli nie, tworzymy nowy zapis
                    $questionPhoto = new QuestionPhoto();
                    $photoService->create($photoFile, $questionPhoto, $question);
                }
            }

            $this->addFlash(
                'success',
                $this->translator->trans('message.question_edited_successfully')
            );

            return $this->redirectToRoute('question_view', ['id' => $question->getId()]);
        }

        return $this->render(
            'question/edit.html.twig',
            [
                'form' => $form->createView(),
                'question' => $question,
            ]
        );
    }

    /**
     * Deletes a question.
     *
     * @param Request  $request  HTTP request
     * @param Question $question Question entity
     *
     * @return Response HTTP response
     */
    #[Route('/question/{id}/delete', name: 'question_delete', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'POST', 'DELETE'])]
    #[IsGranted(QuestionVoter::DELETE, subject: 'question')]
    public function delete(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionDeleteType::class, null, [
            'action' => $this->generateUrl('question_delete', [
                'id' => $question->getId(),
            ]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->questionService->delete($question);

            $this->addFlash(
                'success',
                $this->translator->trans('message.question_deleted_successfully')
            );

            return $this->redirectToRoute('question_list');
        }

        return $this->render('question/delete.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
        ]);
    }
}
