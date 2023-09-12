<?php

namespace App\DataFixtures;

use App\Entity\Driver;
use App\Entity\Trip;
use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;

/**
 *
 */
class AppFixtures extends Fixture
{

    public const VEHICLE_MIN = 20;
    public const DRIVER_MIN = 20;

    public const TRIPS_MIN = 50;
    public const ENTITY_MAX = 100;

    private static $brands = [
        'brand1',
        'brand2',
        'brand3',
        'brand4',
        'brand5',
        'brand6',
        'brand7',
        'brand8',
        'brand9',
        'brand0'
    ];

    /**
     * @var Factory|null
     */
    private $faker;

    /**
     * @var Driver|null []
     */
    private $drivers;

    /**
     * @var Vehicle[]|null
     *
     */
    private $vehicles;

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();

        $this->generateDrivers(random_int(self::DRIVER_MIN, self::ENTITY_MAX), $manager);
        $this->generateVehicles(random_int(self::VEHICLE_MIN, self::ENTITY_MAX), $manager);
        $this->generateTrips(random_int(self::TRIPS_MIN, self::ENTITY_MAX), $manager);

        $manager->flush();
    }


    /**
     * @param $quantity
     * @param $manager
     * @return void
     */
    private function generateDrivers($quantity, $manager)
    {
        while ($quantity) {
            $tmpDriver = new Driver();
            $tmpDriver->setName($this->faker->firstName());
            $tmpDriver->setSurname($this->faker->lastName());
            $tmpDriver->setLicense($this->faker->boolean ? 'S' : 'N');
            $this->drivers[] = $tmpDriver;
            $manager->persist($tmpDriver);
            $quantity--;
        }
    }

    /**
     * @param $quantity
     * @param $manager
     * @return void
     */
    private function generateVehicles($quantity, $manager)
    {
        while ($quantity) {
            $tmpVehicle = new Vehicle();
            $tmpVehicle->setModel($this->faker->text(10));
            $tmpVehicle->setBrand(self::$brands[array_rand(self::$brands)]);
            $tmpVehicle->setPlate($this->makePlate());
            $tmpVehicle->setLicenseRequired($this->faker->boolean ? 'S' : 'N');
            $this->vehicles[] = $tmpVehicle;
            $manager->persist($tmpVehicle);
            $quantity--;
        }

    }


    /**
     * @param $quantity
     * @param $manager
     * @return void
     */
    private function generateTrips($quantity, $manager)
    {
        while ($quantity) {
            $tmpTrip = new Trip();

            $tmpTrip->setDriver($this->drivers[array_rand($this->drivers)]);
            $tmpTrip->setVehicle($this->vehicles[array_rand($this->vehicles)]);
            $tmpTrip->setDate($this->faker->dateTimeBetween('-2 years','now'));
            $manager->persist($tmpTrip);
            $quantity--;
        }
    }

    /**
     * @return string
     * @todo make domain object and format logic. apply
     *
     */
    private function makePlate()
    {
        return sprintf('%2s-%05d-%2s',

            random_int(10, 90), $this->faker->numberBetween(0, 99999), random_int(10, 90));
    }
}
