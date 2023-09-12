<?php

namespace App\Form;

use App\Entity\Driver;
use App\Entity\Trip;
use App\Entity\Vehicle;
use App\Repository\DriverRepository;
use App\Repository\VehicleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 */
class TripType extends AbstractType
{
    private $driverRepository;
    private $vehicleRepository;


    public function __construct(DriverRepository $driverRepository, VehicleRepository $vehicleRepository)
    {
        $this->driverRepository = $driverRepository;
        $this->vehicleRepository = $vehicleRepository;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Vehicle', EntityType::class,
                [
                    'class' => Vehicle::class,
                    'choices' => $this->vehicleRepository->findAll(),
                    'choice_label' =>
                        static function ($vehicle, $key, $index) {
                            \assert($vehicle instanceof Vehicle);
                            return sprintf('%s %s - (%s)', $vehicle->getBrand(), $vehicle->getModel(), $vehicle->getPlate());
                        }
                ]
            )
            ->add('Driver', EntityType::class,
                [
                    'class' => Driver::class,
                    'choices' => $this->driverRepository->findAll(),
                    'choice_label' =>
                        static function ($driver, $key, $index) {
                            \assert($driver instanceof Driver);
                            return sprintf('%s %s ', $driver->getName(), $driver->getSurname());
                        },
                ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
