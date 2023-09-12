<?php

namespace App\Form\Reservation;

use App\Entity\Driver;
use App\Entity\Trip;
use App\Entity\Vehicle;
use App\Repository\DriverRepository;
use App\Repository\VehicleRepository;
use DateInterval;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function assert;

/**
 *
 */
class PhaseIIIType extends AbstractType
{
    public const PHASE_STEP = 3;
    private $driverRepository;
    private $vehicleRepository;


    public function __construct(DriverRepository $driverRepository, VehicleRepository $vehicleRepository)
    {
        $this->driverRepository = $driverRepository;
        $this->vehicleRepository = $vehicleRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        [$rangeA, $rangeB] = $this->makeDateRanges($options['rdate']);


        $builder
            ->add('Date')
            ->add('Vehicle', EntityType::class,
                [
                    'class' => Vehicle::class,
                    'choices' => $this->vehicleRepository->findByNotReserved($rangeA, $rangeB),
                    'choice_label' =>
                        static function ($vehicle, $key, $index) {
                            assert($vehicle instanceof Vehicle);
                            return sprintf('%s %s - (%s)', $vehicle->getBrand(), $vehicle->getModel(), $vehicle->getPlate());
                        }
                ]
            )
            ->add('Driver', EntityType::class,
                [
                    'class' => Driver::class,
                    'choices' => $this->driverRepository->findBySameLicense($options['vehicle']->getLicenseRequired(), $rangeA, $rangeB),
                    'choice_label' =>
                        static function ($driver, $key, $index) {
                            /** @var Driver $driver */
                            return sprintf('%s %s ', $driver->getName(), $driver->getSurname());
                        },
                ]);
    }

    /**
     * @param DateTime $initDate
     * @return DateTime[]
     * @todo move to DateRange or similar
     *
     */
    private function makeDateRanges(DateTime $initDate)
    {
        $rangeB = clone $initDate;
        $rangeB->add(new DateInterval('P1D'));
        return [$initDate, $rangeB];

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);

        $resolver->setRequired('rdate');
        $resolver->setAllowedTypes('rdate', 'DateTime');

        $resolver->setRequired('vehicle');
        $resolver->setAllowedTypes('vehicle', Vehicle::class);
    }
}
