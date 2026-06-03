<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagDeleteType;
use App\Form\TagType;
use App\Repository\TagRepository;
use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


#[Route(
    '/tag'
)]

final class TagController extends AbstractController
{

    /**
     * Constructor.
     */


    public function __construct(
        //private readonly CategoryService $categoryService,
        private readonly TagRepository $tagRepository,
        private readonly TagService $tagService,
        private readonly TranslatorInterface $translator
    ) {}


    /**
     * Index.
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
     * Create action.
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

            $this->tagRepository->save($tag);

            $this->addFlash(
                'success',
                $this->translator->trans('message.success.new_category')
            );

            return $this->redirectToRoute('tag_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tag/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Tag $tag Tag entity
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
                $this->translator->trans('message.edited_successfully')
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

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->delete($tag);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('tag_list');
        }

        return $this->render('tag/delete.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag,
        ]);
    }



}
