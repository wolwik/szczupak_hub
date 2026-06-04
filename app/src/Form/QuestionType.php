<?php
/**
 * Question type.
 */

namespace App\Form;

use App\Entity\Category;
use App\Entity\Question;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class QuestionType.
 */
class QuestionType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // TITLE
            ->add(
            'title',
            TextType::class,
            [
                'label' => 'label.title',
                'required' => true,
                'attr' => ['max_length' => 64, 'placeholder' => 'Karp na parówkę: mit czy prawda?'],
            ])

            // CONTENT
            ->add(
            'content',
            TextareaType::class,
            [
                'label' => 'label.content',
                'required' => true,
                'attr' => ['rows' => 10, 'placeholder' => 'Zadaj pytanie, opowiedz historię, podziel się przepisem na zanętę.'],
            ])

            // CATEGORY (relation)
            ->add(
            'category',
            EntityType::class,
            [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'label.category',
                'required' => true,
                'placeholder' => "Wybierz kategorię",
            ])

            // TAGS (relation)
            ->add(
                'tags',
                TextType::class,
                [
                    'mapped' => false,
                    'required' => true,
                    'label' => 'label.tags',
                    'attr' => ['placeholder' => 'karp, spinning, jezioro...'],
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
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'question';
    }


}

