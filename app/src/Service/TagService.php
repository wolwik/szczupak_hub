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
use Symfony\Component\String\Slugger\SluggerInterface;

/*
 * Class TagService
 */

class TagService {
    public function __construct(
        private readonly TagRepository $tagRepository,
        private readonly TranslatorInterface $translator,
        private readonly SluggerInterface $slugger,
    ) {}

    public function save(Tag $tag) {

        // generating slug
        $slug = $this->slugger->slug($tag->getName())
        ->lower()
        ->toString();

        $tag->setSlug($slug);

        $this->tagRepository->save($tag);
    }

    // ???
    public function findOrCreate(string $name): Tag
    {
        $name = trim($name);

        $tag = $this->tagRepository->findOneBy(['name' => $name]);

        if ($tag) {
            return $tag;
        }

        $tag = new Tag();
        $tag->setName($name);

        $this->save($tag);

        return $tag;
    }

    public function createFromString(string $tagsString): array
    {
        $names = array_filter(array_map(
            'trim',
            explode(',', $tagsString)
        ));

        $tags = [];

        foreach ($names as $name) {
            if ($name === '') {
                continue;
            }
            $tags[] = $this->findOrCreate($name);
        }

        return $tags;
    }





    public function delete(Tag $tag): void
    {
        $this->tagRepository->delete($tag);
    }

}
