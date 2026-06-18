<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Wolwik / UJ
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Avatar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AvatarRepository.
 *
 * @extends ServiceEntityRepository<Avatar>
 */
class AvatarRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avatar::class);
    }

    /**
     * Save entity.
     *
     * @param Avatar $avatar Avatar entity
     */
    public function save(Avatar $avatar): void
    {
        $this->getEntityManager()->persist($avatar);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete entity.
     *
     * @param Avatar $avatar Avatar entity
     */
    public function delete(Avatar $avatar): void
    {
        $this->getEntityManager()->remove($avatar);
        $this->getEntityManager()->flush();
    }
}
