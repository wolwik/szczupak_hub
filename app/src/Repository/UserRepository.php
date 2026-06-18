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

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * Class UserRepository.
 *
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Save entity.
     *
     * @param User $user User entity
     */
    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds user by email.
     *
     * @param string $email Email string
     *
     * @return User User entity
     */
    public function findByEmail(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Finds user by nickname.
     *
     * @param string $nickname Nickname string
     *
     * @return User User entity
     */
    public function findByNickname(string $nickname): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.nickname = :nickname')
            ->setParameter('nickname', $nickname)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Finds every administrator.
     *
     * @return array Array of admins
     */
    public function findAdmins(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"ROLE_ADMIN"%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     *
     * @param PasswordAuthenticatedUserInterface $user              User interface
     * @param string                             $newHashedPassword New password
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete entity.
     *
     * @param User $user  User entity
     * @param bool $flush Bool for deleting entity
     */
    public function delete(User $user, bool $flush = true): void
    {
        $this->getEntityManager()->remove($user);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Displays users in admin panel. The currently logged-in admin cannot do any actions on himself in this endpoint.
     *
     * @param int $userId Currently logged-in user (admin)
     *
     * @return array Array of users
     */
    public function findAllExceptCurrentUser(int $userId): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id != :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }
}
