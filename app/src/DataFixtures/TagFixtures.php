<?php

namespace App\DataFixtures;

use App\Entity\Tag;

class TagFixtures extends AbstractBaseFixtures
{
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
