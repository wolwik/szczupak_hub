<?php

/**
 * Category service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Question;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/*
 * Class CategoryService
 */

class CategoryService {
    public function __construct(
        private CategoryRepository $categoryRepository,
        private TranslatorInterface $translator,
    ) {}

    public function save(Category $category) {
        if (null === $category->getId()) {
            $this->categoryRepository->save($category);
        }

    }

    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }

}
