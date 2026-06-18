<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Wolwik / UJ
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Contract\TagServiceInterface;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class TagService.
 */
class TagService implements TagServiceInterface
{
    /**
     * Constructor.
     *
     * @param TagRepository    $tagRepository Tag repository
     * @param SluggerInterface $slugger       Slugger interface
     */
    public function __construct(private readonly TagRepository $tagRepository, private readonly SluggerInterface $slugger)
    {
    }

    /**
     * Save tag.
     *
     * @param Tag $tag Tag entity
     */
    public function save(Tag $tag): void
    {
        // generating slug
        $slug = $this->slugger->slug($tag->getName())
        ->lower()
        ->toString();

        $tag->setSlug($slug);

        $this->tagRepository->save($tag);
    }

    /**
     * Find tag by name or create a new one.
     *
     * @param string $name Tag name
     *
     * @return Tag Tag entity
     */
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

    /**
     * Create tags from string.
     *
     * @param string $tagsString Comma-separated tags
     *
     * @return array Array of Tag entities
     */
    public function createFromString(string $tagsString): array
    {
        $names = array_filter(array_map(
            trim(...),
            explode(',', $tagsString)
        ));

        $tags = [];

        foreach ($names as $name) {
            if ('' === $name) {
                continue;
            }
            $tags[] = $this->findOrCreate($name);
        }

        return $tags;
    }

    /**
     * Delete tag.
     *
     * @param Tag $tag Tag entity
     */
    public function delete(Tag $tag): void
    {
        $this->tagRepository->delete($tag);
    }
}
