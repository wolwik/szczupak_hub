<?php

namespace App\Controller;

use App\Contract\TagServiceInterface;
use App\Entity\Tag;
use App\Form\TagDeleteType;
use App\Form\TagType;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * Class TagController.
 */
#[Route(
    '/tag'
)]
#[IsGranted('ROLE_ADMIN')]

final class TagController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param TagServiceInterface  $tagService     Tag service
     * @param TranslatorInterface  $translator     Translator
     */
    public function __construct(
        private readonly TagServiceInterface $tagService,
        private readonly TranslatorInterface $translator
    ) {}


    /**
     * Displays list of tags.
     *
     * @return Response HTTP response
     */
    #[Route(
        '/tag_list',
        name: 'tag_list',
        methods: ['GET'],
    )]
    public function index(TagRepository $tagRepository): Response
    {
        return $this->render('tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
        ]);
    }


    /**
     * Creates a new tag.
     *
     * @param Request $request HTTP request
     *
     * @return Response Redirect response to question view
     */
    #[Route(
        '/create',
        name: 'tag_new',
        methods: ['GET', 'POST']
    )]
    public function create(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->tagService->save($tag);

            $this->addFlash(
                'success',
                $this->translator->trans('message.tag_created_successfully')
            );

            return $this->redirectToRoute('tag_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tag/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Edits a tag.
     *
     * @param Request $request HTTP request
     * @param Tag     $tag     Tag entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}/edit',
        name: 'tag_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'PUT']
    )]
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(
            TagType::class,
            $tag,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('tag_edit', ['id' => $tag->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);

            $this->addFlash(
                'success',
                $this->translator->trans('message.tag_edited_successfully')
            );

            return $this->redirectToRoute('tag_list');

        }

        return $this->render(
            'tag/edit.html.twig',
            [
                'form' => $form->createView(),
                'tag' => $tag,
            ]
        );
    }


    /**
     * Deletes a tag.
     *
     * @param Request  $request  HTTP request
     * @param Tag      $tag      Tag entity
     *
     * @return Response Redirect or rendered confirmation page
     */
    #[Route(
        '/{id}/delete',
        name: 'tag_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST', 'DELETE']
    )]
    public function delete(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagDeleteType::class, null, [
            'action' => $this->generateUrl('tag_delete', [
                'id' => $tag->getId()
            ]),
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->tagService->delete($tag);

            $this->addFlash(
                'success',
                $this->translator->trans('message.tag_deleted_successfully')
            );

            return $this->redirectToRoute('tag_list');
        }

        return $this->render('tag/delete.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag,
        ]);
    }

}
