<?php

/**
 * Vote entity.
 */

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
#[ORM\Table(name: 'votes')]
#[UniqueEntity(
    fields: ['user', 'answer'],
    message: 'Już oddałeś głos na tę odpowiedź.'
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
    #[ORM\ManyToOne(inversedBy: 'votes')]
    #[ORM\JoinColumn(name: 'answer_id', referencedColumnName: 'id', nullable: true)]
    #[Assert\NotBlank]
    private ?Answer $answer = null;

    /**
     * User who voted.
     */
    #[ORM\ManyToOne(inversedBy: 'votes')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    #[Assert\NotBlank]
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(?Answer $answer): static
    {
        $this->answer = $answer;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
