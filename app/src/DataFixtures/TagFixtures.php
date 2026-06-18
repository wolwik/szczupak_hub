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

use App\Entity\Tag;

/**
 * Class AnswerFixtures.
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Loads data.
     */
    protected function loadData(): void
    {
        $this->createMany(10, 'tag', function (int $i) {

            $tag = new Tag();

            $name = $this->faker->word();

            $tag->setName($name);
            $tag->setSlug(strtolower(str_replace(' ', '-', $name)));

            return $tag;
        });
    }
}
