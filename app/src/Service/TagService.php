<?php

/**
 * Tag service.
 */

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/*
 * Class TagService
 */

class TagService {
    public function __construct(
        private TagRepository $tagRepository,
        private TranslatorInterface $translator,
    ) {}

    public function save(Tag $tag) {
        $this->tagRepository->save($tag);
    }

    public function delete(Tag $tag): void
    {
        $this->tagRepository->delete($tag);
    }

}
