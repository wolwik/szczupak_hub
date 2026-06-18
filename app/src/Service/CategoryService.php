<?php

/**
 * Category service.
 */

namespace App\Service;

use App\Contract\CategoryServiceInterface;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

/*
 * Class CategoryService
 */

class CategoryService implements CategoryServiceInterface{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly SluggerInterface $slugger
    ) {}

    public function save(Category $category): void
    {
        $slug = $this->slugger->slug($category->getName())
            ->lower()
            ->toString();

        $category->setSlug($slug);

        $this->categoryRepository->save($category);

    }

    public function delete(Category $category): bool
    {
        if (!$category->getQuestions()->isEmpty()) {
            return false;
        }

        $this->categoryRepository->delete($category);

        return true;
    }

}
