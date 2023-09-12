<?php

namespace App\Form\Reservation;

use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 */
class PhaseIType extends AbstractType
{
    public const PHASE_STEP = 1;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Date')        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
