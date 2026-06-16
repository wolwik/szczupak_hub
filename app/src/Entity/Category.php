<?php

/**
 * Category entity.
 */

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'categories')]
#[UniqueEntity(
    fields: ['name'],
    message: 'Taka kategoria już istnieje.'
)]

class Category
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Category name.
     *
     */
    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Nazwa kategorii nie może być pusta.')]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'Nazwa kategorii jest za krótka.',
        maxMessage: 'Nazwa kategorii nie może być dłuższa niż {{ limit }} znaków.'
    )]
    private ?string $name = null;

    /**
     * Category slug.
     *
     * Automatically generated.
     */
    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    private ?string $slug = null;

    /**
     * Questions in this category.
     *
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: "category")]
    #[Assert\Valid] // symfony walidując tą encję powinien też wejsc do tych obiektów i sprawdzić ich reguły walidacji
    private Collection $questions;


    public function __construct()
    {
        $this->questions = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getQuestions(): Collection
    {
        return $this->questions;
    }
}
