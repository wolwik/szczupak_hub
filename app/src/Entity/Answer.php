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

use App\Repository\AnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Answer.
 */
#[ORM\Entity(repositoryClass: AnswerRepository::class)]
#[ORM\Table(name: 'answers')]
class Answer
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Answer content.
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Answer content cannot be empty.')]
    #[Assert\Length(
        min: 1,
        minMessage: 'Answer is too short.',
    )]
    private ?string $content = null;

    /**
     * Created at timestamp.
     */
    #[ORM\Column(type: 'datetime')]
    #[Assert\Type(\DateTime::class)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTime $createdAt = null;

    /**
     * Updated at timestamp.
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Assert\Type(\DateTime::class)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTime $updatedAt = null;

    /**
     * Related question.
     */
    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
    #[ORM\JoinColumn(name: 'question_id', referencedColumnName: 'id', nullable: false)]
    #[Assert\Type(type: Question::class)]
    private ?Question $question = null;

    /**
     * Author of answer.
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'answers')]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?User $author = null;

    /**
     * Votes collection.
     *
     * @var Collection<int, Vote>
     */
    #[ORM\OneToMany(
        targetEntity: Vote::class,
        mappedBy: 'answer',
        cascade: ['remove'],
        orphanRemoval: true
    )]
    #[Assert\Valid]
    private Collection $votes;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

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
     * @param int $id Id
     *
     * @return $this
     */
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for content.
     *
     * @return string|null Content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content.
     *
     * @param string $content Content
     *
     * @return $this
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Getter for creation date.
     *
     * @return \DateTime|null Created at
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * Setter for creation date.
     *
     * @param \DateTime $createdAt Created at
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Getter for question.
     *
     * @return Question|null Question entity
     */
    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    /**
     * Setter for question.
     *
     * @param Question|null $question Question entity
     *
     * @return $this
     */
    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Getter for update date.
     *
     * @return \DateTime|null Updated at
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Setter for update date.
     *
     * @param \DateTime|null $updatedAt Updated at
     *
     * @return $this
     */
    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Getter for author.
     *
     * @return User|null User entity
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for author.
     *
     * @param User|null $author User entity
     *
     * @return $this
     */
    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Getter for votes collection.
     *
     * @return Collection<int, Vote> Votes collection
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    /**
     * Adds a vote to the answer.
     *
     * @param Vote $vote Vote entity
     *
     * @return $this
     */
    public function addVote(Vote $vote): static
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setAnswer($this);
        }

        return $this;
    }

    /**
     * Removes a vote from the answer.
     *
     * @param Vote $vote Vote entity
     *
     * @return $this
     */
    public function removeVote(Vote $vote): static
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getAnswer() === $this) {
                $vote->setAnswer(null);
            }
        }

        return $this;
    }

    /**
     * Gets the total count of votes.
     *
     * @return int Votes count
     */
    public function getVotesCount(): int
    {
        return count($this->votes);
    }
}
