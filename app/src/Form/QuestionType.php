<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // TITLE
            ->add('title', TextType::class, [
                'label' => 'label.title',
                'required' => true,
                'attr' => [
                    'max_length' => 64,
                    //'placeholder' => 'Tytuł pytania',
                    'class' => 'form-control'
                ],
            ])

            // CONTENT
            ->add('content', TextareaType::class, [
                'label' => 'label.content',
                'required' => true,
                'attr' => [
                    'rows' => 6,
                    //'placeholder' => 'Zadaj pytanie, opowiedz historię...',
                    'class' => 'form-control'
                ],
            ])

            // CATEGORY (relation)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'label.category',
                'required' => true,
                //'placeholder' => 'Wybierz kategorię',
                'attr' => [
                    'class' => 'form-select'
                ],
            ])

            // TAGS (relation)
            ->add('tags', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'label.tags',
                'attr' => [
                    //'placeholder' => 'karp, spinning, jezioro...',
                    'class' => 'form-control'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Question::class]);
    }

    public function getBlockPrefix(): string
    {
        return 'question';
    }
}
