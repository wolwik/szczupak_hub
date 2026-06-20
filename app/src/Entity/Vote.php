<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Wolwik / UJ
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Vote.
 */
#[ORM\Entity(repositoryClass: VoteRepository::class)]
#[ORM\Table(name: 'votes')]
#[UniqueEntity(
    fields: ['user', 'answer'],
)]
class Vote
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Answer associated with this vote.
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'answer_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    #[Assert\NotBlank]
    private ?Answer $answer = null;

    /**
     * User who voted.
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    #[Assert\NotBlank]
    private ?User $user = null;

    /**
     * Getter for ID.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Setter for ID.
     *
     * @param int|null $id Id
     *
     * @return $this
     */
    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for answer.
     *
     * @return Answer|null Answer entity
     */
    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    /**
     * Setter for answer.
     *
     * @param Answer|null $answer Answer entity
     *
     * @return $this
     */
    public function setAnswer(?Answer $answer): static
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Getter for user.
     *
     * @return User|null User entity
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setter for user.
     *
     * @param User|null $user User entity
     *
     * @return $this
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
