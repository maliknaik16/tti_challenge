<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for($i = 0; $i < 20; $i++)
        {
            $first_name = $faker->firstName();
            $last_name = $faker->lastName();
            $email = $faker->email();
            $password = '1234';
            $address = $faker->address();
            $phone = $faker->phoneNumber();
            $role = rand(0, 1);

            $user = new User();
            $user->setFirstName($first_name);
            $user->setLastName($last_name);
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setAddress($address);
            $user->setPhone($phone);
            $user->setRole($role);
            $user->setCreatedAt(new \DateTime());

            $manager->persist($user);
        }

        $manager->flush();
    }
}
