<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Wolwik / UJ
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CategoryFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    protected function loadData(): void
    {
        if (!$this->manager instanceof ObjectManager || !$this->faker) {
            return;
        }

        $this->createMany(10, 'category', function (int $i) {

            $category = new Category();

            $name = $this->faker->word();

            $category->setName($name);
            $category->setSlug(strtolower(str_replace(' ', '-', $name)));

            return $category;
        });
    }
}
