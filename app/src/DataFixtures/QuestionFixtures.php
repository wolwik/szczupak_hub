<?php

namespace App\DataFixtures;

use App\Entity\Enum\QuestionStatus;
use App\Entity\Question;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


/**
 * Class QuestionFixtures.
 *
 */

class QuestionFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{

    /**
     * Loads data.
     *
     */

    public function loadData(): void {

        if (!$this->manager instanceof ObjectManager || !$this->faker) {
            return;
        }

        $this->createMany(17, 'question', function (int $i) {

            $question = new Question();

            $question->setTitle(
                $this->faker->sentence()
            );

            $question->setContent(
                $this->faker->realText(500)
            );

            // random status from enum
            $status = $this->faker->randomElement([
                QuestionStatus::DRAFT,
                QuestionStatus::PUBLISHED,
            ]);
            $question->setStatus($status);


            $question->setCreatedAt(
                $this->faker->dateTimeBetween('-100 days', '-1 days')
            );

            $question->setUpdatedAt(
                $this->faker->dateTimeBetween('-100 days', '-1 days')
            );

            // random category from reference
            $category = $this->getRandomReference('category', Category::class);
            $question->setCategory($category);

            // tags (random 1-4)
            for ($j = 0; $j < random_int(1,7); $j++) {
                $tag = $this->getRandomReference('tag', Tag::class);
                $question->addTag($tag);
            }

            $user = $this->getRandomReference('user', User::class);
            $question->setAuthor($user);

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
