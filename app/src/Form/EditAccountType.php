<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EditAccountType.
 */

class EditAccountType extends AbstractType
{
    /**
     * Builds form for editing user's account.
     *
     * @param FormBuilderInterface $builder Form builder instance
     * @param array<string, mixed> $options Form options
     */

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // EMAIL
            ->add('email', EmailType::class, [
                'label' => 'label.email',
            ])

            // NICKNAME
            ->add('nickname', TextType::class, [
                'label' => 'label.nickname',
            ]);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
