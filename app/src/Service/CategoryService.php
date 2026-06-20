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
use App\Repository\QuestionRepository;
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
     * @param QuestionRepository $questionRepository Question repository
     * @param SluggerInterface   $slugger            Slugger interface
     */
    public function __construct(private readonly CategoryRepository $categoryRepository, private readonly QuestionRepository $questionRepository, private readonly SluggerInterface $slugger)
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
     * @return bool True if deleted, false if category has assigned question-photos
     */
    public function delete(Category $category): bool
    {
        if ($this->questionRepository->countByCategory($category) > 0) {
            return false;
        }

        $this->categoryRepository->delete($category);

        return true;
    }
}
