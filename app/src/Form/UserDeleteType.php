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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UserDeleteType.
 */
class UserDeleteType extends AbstractType
{
    /**
     * Builds form for deleting user.
     *
     * @param FormBuilderInterface $builder Form builder instance
     * @param array<string, mixed> $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('POST')
            ->add('_method', HiddenType::class, [
                'data' => 'DELETE',
            ])
            ->add('delete', SubmitType::class, [
                'label' => 'label.delete',
            ]);
    }
}
