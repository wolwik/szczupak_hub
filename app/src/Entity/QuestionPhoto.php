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

use App\Repository\QuestionPhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class QuestionPhoto.
 */
#[ORM\Entity(repositoryClass: QuestionPhotoRepository::class)]
#[ORM\Table(name: 'question_photos')]
#[ORM\UniqueConstraint(name: 'uq_question_photos_filename', columns: ['filename'])]
#[UniqueEntity(fields: ['filename'], message: 'This filename is already in use.')]
#[UniqueEntity(fields: ['question'], message: 'This question already has a photo.')]
class QuestionPhoto
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Associated question.
     */
    #[ORM\OneToOne(targetEntity: Question::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'question_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[Assert\Type(Question::class)]
    private ?Question $question = null;

    /**
     * Photo filename.
     */
    #[ORM\Column(type: 'string', length: 191)]
    #[Assert\Type('string')]
    private ?string $filename = null;

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
     * Getter for question.
     *
     * @return Question|null Question
     */
    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    /**
     * Setter for question.
     *
     * @param Question|null $question Question
     */
    public function setQuestion(?Question $question): void
    {
        $this->question = $question;
    }

    /**
     * Getter for filename.
     *
     * @return string|null Filename
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * Setter for filename.
     *
     * @param string|null $filename Filename
     */
    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }
}
