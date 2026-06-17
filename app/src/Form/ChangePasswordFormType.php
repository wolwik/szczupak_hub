<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChangePasswordFormType.
 */

class ChangePasswordFormType extends AbstractType
{
    /**
     * Builds form for changing password.
     *
     * @param FormBuilderInterface $builder Form builder instance
     * @param array<string, mixed> $options Form options
     */

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'label.current_password',
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'label.new_password'
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
            // Configure your form options here
        ]);
    }
}
