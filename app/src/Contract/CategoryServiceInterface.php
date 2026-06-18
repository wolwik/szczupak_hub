<?php

/**
 * Category service interface.
 */

namespace App\Contract;

use App\Entity\Category;

/**
 * Interface CategoryServiceInterface.
 */
interface CategoryServiceInterface
{
    /**
     * Save category.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void;

    /**
     * Delete category.
     *
     * @param Category $category Category entity
     *
     * @return bool True if deleted, false otherwise
     */
    public function delete(Category $category): bool;
}
