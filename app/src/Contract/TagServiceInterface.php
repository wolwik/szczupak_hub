<?php

/**
 * Tag service interface.
 */

namespace App\Contract;

use App\Entity\Tag;

/**
 * Interface TagServiceInterface.
 */
interface TagServiceInterface
{
    /**
     * Save tag.
     *
     * @param Tag $tag Tag entity
     */
    public function save(Tag $tag): void;

    /**
     * Find tag by name or create a new one if it doesn't exist.
     *
     * @param string $name Tag name
     *
     * @return Tag Tag entity
     */
    public function findOrCreate(string $name): Tag;

    /**
     * Create an array of Tag entities from a comma-separated string.
     *
     * @param string $tagsString Comma-separated tags
     *
     * @return array<int, Tag> Array of Tag entities
     */
    public function createFromString(string $tagsString): array;

    /**
     * Delete tag.
     *
     * @param Tag $tag Tag entity
     */
    public function delete(Tag $tag): void;
}
