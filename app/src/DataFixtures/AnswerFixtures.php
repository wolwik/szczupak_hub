<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


/**
 * Class AnswerFixtures.
 *
 */

class AnswerFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{

    /**
     * Loads data.
     *
     */

    protected function loadData(): void
    {
        if (!$this->manager || !$this->faker) {
            return;
        }

        $this->createMany(50, 'answer', function (int $i) {

            $answer = new Answer();

            $answer->setContent(
                $this->faker->realText(200)
            );

            $answer->setCreatedAt(
                $this->faker->dateTimeBetween('-100 days', '-1 days')
            );

            // przypisanie Question (NOT NULL FK)
            $question = $this->getRandomReference('question', Question::class);
            $answer->setQuestion($question);

            $user = $this->getRandomReference('user', User::class);
            $answer->setAuthor($user);

            return $answer;
        });
    }


    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: QuestionFixtures::class}
     */

    public function getDependencies(): array
    {
        return [
            QuestionFixtures::class,
        ];
    }
}
