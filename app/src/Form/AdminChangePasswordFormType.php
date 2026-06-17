<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AdminChangePasswordFormType.
 */

class AdminChangePasswordFormType extends AbstractType
{
    /**
     * Builds the admin password change form.
     *
     * @param FormBuilderInterface $builder Form builder instance
     * @param array<string, mixed> $options Form options
     */

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('newPassword', PasswordType::class, [
            'label' => 'label.new_password',
            'mapped' => false, // dajemy bo pole nie istnieje w encji, to dane do przetworzenia (zahashowania)
        ]);
    }
}

