<?php

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
     *
     */

    protected function loadData(): void {

        if (!$this->manager instanceof ObjectManager || !$this->faker) {
            return;
        }

        $this->createMany(10, 'category', function (int $i) {
            $category = new Category();

            $category->setName(
                $this->faker->word()
            );

            $category->setSlug(
                $this->faker->slug()
            );

            return $category;
        });
    }
}
