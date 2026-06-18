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

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Entity\Enum\QuestionStatus;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\Table(name: 'questions')]

/**
 * Class Question.
 */
class Question
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Question title.
     */
    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Tytuł pytania nie może być pusty.')]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'Tytuł jest za krótki (minimum {{ limit }} znaków).',
        maxMessage: 'Tytuł nie może być dłuższy niż {{ limit }} znaków.'
    )]
    private ?string $title = null;

    /**
     * Question content.
     */
    #[ORM\Column(type: 'text')]
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Treść pytania nie może być pusta.')]
    #[Assert\Length(min: 5, minMessage: 'Opisz swój problem nieco dokładniej (minimum {{ limit }} znaków).')]
    private string $content;

    /**
     * Question status.
     */
    #[ORM\Column(enumType: QuestionStatus::class)]
    #[Assert\Type(QuestionStatus::class)]
    private QuestionStatus $status = QuestionStatus::DRAFT;

    /**
     * Created at timestamp.
     */
    #[ORM\Column(type: 'datetime')]
    #[Assert\Type(\DateTime::class)]
    private ?\DateTime $createdAt = null;

    /**
     * Updated at timestamp.
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Assert\Type(\DateTime::class)]
    private ?\DateTime $updatedAt = null;

    /**
     * Answers collection.
     */
    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class, fetch: 'EXTRA_LAZY', cascade: ['remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    #[Assert\Valid]
    private Collection $answers;

    /**
     * Question category.
     */
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'questions')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotNull(message: 'Wybierz kategorię dla pytania.')]
    #[Assert\Type(Category::class)]
    private ?Category $category = null;

    /**
     * Tags collection.
     *
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'questions')]
    #[ORM\JoinTable(name: 'questions_tags')]
    #[ORM\JoinColumn(name: 'question_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'tag_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Assert\Valid]
    private Collection $tags;

    /**
     * Question author.
     */
    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Assert\Type(User::class)]
    private ?User $author = null;

    /**
     * Best answer.
     */
    #[ORM\OneToOne(targetEntity: Answer::class)]
    #[ORM\JoinColumn(name: 'best_answer_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    #[Assert\Type(Answer::class)]
    private ?Answer $bestAnswer = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
     * Getter for title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string $title Title
     *
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

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
     * Getter for status.
     *
     * @return QuestionStatus Status
     */
    public function getStatus(): QuestionStatus
    {
        return $this->status;
    }

    /**
     * Setter for status.
     *
     * @param QuestionStatus $status Status
     *
     * @return $this
     */
    public function setStatus(QuestionStatus $status): self
    {
        $this->status = $status;

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
     * Getter for category.
     *
     * @return Category|null Category entity
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for category.
     *
     * @param Category|null $category Category entity
     *
     * @return $this
     */
    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Getter for answers.
     *
     * @return Collection<int, Answer> Answers collection
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    /**
     * Getter for tags.
     *
     * @return Collection<int, Tag> Tags collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Adds a tag to the question.
     *
     * @param Tag $tag Tag entity
     *
     * @return $this
     */
    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addQuestion($this);
        }

        return $this;
    }

    /**
     * Removes a tag from the question.
     *
     * @param Tag $tag Tag entity
     *
     * @return $this
     */
    public function removeTag(Tag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeQuestion($this);
        }

        return $this;
    }

    /**
     * Clears all tags from the question.
     *
     * @return $this
     */
    public function clearTags(): self
    {
        $this->tags->clear();

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
     * Getter for best answer.
     *
     * @return Answer|null Answer entity
     */
    public function getBestAnswer(): ?Answer
    {
        return $this->bestAnswer;
    }

    /**
     * Setter for best answer.
     *
     * @param Answer|null $bestAnswer Answer entity
     *
     * @return $this
     */
    public function setBestAnswer(?Answer $bestAnswer): self
    {
        $this->bestAnswer = $bestAnswer;

        return $this;
    }
}
