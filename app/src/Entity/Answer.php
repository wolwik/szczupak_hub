<?php

/**
 * Answer entity.
 */

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
#[ORM\Table(name: 'answers')]

class Answer {

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
    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: "answers")]
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



    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): static
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setAnswer($this);
        }

        return $this;
    }

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

    public function getVotesCount(): int {
        return count($this->votes);
    }

}
