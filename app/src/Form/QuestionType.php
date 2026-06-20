<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Wolwik / UJ
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use App\Entity\Category;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class QuestionType.
 */
class QuestionType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // TITLE
            ->add('title', TextType::class, [
                'label' => 'label.title',
                'required' => true,
                'attr' => [
                    'max_length' => 64,
                    'class' => 'form-control',
                ],
            ])

            // CONTENT
            ->add('content', TextareaType::class, [
                'label' => 'label.content',
                'required' => true,
                'attr' => [
                    'rows' => 6,
                    'class' => 'form-control',
                ],
            ])

            // CATEGORY (relation)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'label.category',
                'required' => true,
                'attr' => [
                    'class' => 'form-select',
                ],
            ])

            // TAGS (relation)
            ->add('tags', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'label.tags',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])

            // PHOTO (unmapped field)
            ->add('photo', FileType::class, [
                'label' => 'label.photo',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new File(maxSize: '2M', mimeTypes: [
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                    ], mimeTypesMessage: 'Proszę przesłać poprawny plik graficzny (JPEG, PNG, WebP).'),
                ],
            ]);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Question::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'question';
    }
}
