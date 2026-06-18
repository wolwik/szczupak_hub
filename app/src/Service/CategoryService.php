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

use App\Contract\CategoryServiceInterface;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class CategoryService.
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * Constructor.
     *
     * @param CategoryRepository $categoryRepository Category repository
     * @param SluggerInterface   $slugger            Slugger interface
     */
    public function __construct(private readonly CategoryRepository $categoryRepository, private readonly SluggerInterface $slugger)
    {
    }

    /**
     * Save category.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void
    {
        $slug = $this->slugger->slug($category->getName())
            ->lower()
            ->toString();

        $category->setSlug($slug);

        $this->categoryRepository->save($category);
    }

    /**
     * Delete category.
     *
     * @param Category $category Category entity
     *
     * @return bool True if deleted, false if category has assigned questions
     */
    public function delete(Category $category): bool
    {
        if (!$category->getQuestions()->isEmpty()) {
            return false;
        }

        $this->categoryRepository->delete($category);

        return true;
    }
}
