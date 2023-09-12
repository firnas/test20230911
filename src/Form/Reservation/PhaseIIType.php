<?php

namespace App\Form\Reservation;

use App\Entity\Trip;
use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use DateInterval;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 */
class PhaseIIType extends AbstractType
{
    public const PHASE_STEP = 2;

    /**
     * @var VehicleRepository
     */
    private $repository;


    public function __construct( VehicleRepository $repository)
    {
        $this->repository = $repository;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        [$rangeA, $rangeB] = $this->makeDateRanges($options['rdate']);

        $builder
            ->add('Date')
            ->add('Vehicle', EntityType::class,
                [
                    'class' => Vehicle::class,
                    'choices' => $this->repository->findByNotReserved($rangeA, $rangeB),
                    'choice_label' =>
                        static function ($vehicle, $key, $index) {
                            \assert($vehicle instanceof Vehicle);
                            return sprintf('%s %s - (%s)', $vehicle->getBrand(), $vehicle->getModel(), $vehicle->getPlate());
                        }
                ]
            );
    }

    /**
     * @todo move to READ or similar
     *
     * @param DateTime $initDate
     * @return DateTime[]
     */
    private function makeDateRanges(DateTime $initDate)
    {
        $rangeB = clone $initDate;
        $rangeB->add(new DateInterval('P1D'));
        return [$initDate,$rangeB];

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);

        $resolver->setRequired('rdate');
        $resolver->setAllowedTypes('rdate', 'DateTime');

    }
}
