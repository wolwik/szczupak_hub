<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Category;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class QuestionFixtures
 */

class QuestionFixtures extends AbstractBaseFixtures implements DependentFixtureInterface {


    public function loadData(): void {

        if (!$this->manager instanceof ObjectManager || !$this->faker) {
            return;
        }

        $this->createMany(10, 'question', function (int $i) {

            $question = new Question();

            $question->setTitle(
                $this->faker->sentence()
            );

            $question->setContent(
                $this->faker->realText(500)
            );

            $question->setCreatedAt(
                $this->faker->dateTimeBetween('-100 days', '-1 days')
            );

            $question->setUpdatedAt(
                $this->faker->dateTimeBetween('-100 days', '-1 days')
            );

            // losowa kategoria z referencji
            $category = $this->getRandomReference('category', Category::class);
            $question->setCategory($category);

            // tagi (losowo 1-4)
            for ($i = 0; $i < random_int(1,7); $i++) {
                $tag = $this->getRandomReference('tag', Tag::class);
                $question->addTag($tag);
            }

            return $question;
        });
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class}
     */
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
