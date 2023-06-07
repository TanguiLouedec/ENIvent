<?php
namespace App\DataFixtures;
use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Location;
use App\Entity\State;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;
   
	// injection de dependance
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {



        $generator = Factory::create('fr_FR');

        //-------------------------------------------
        //Création des Campus
        //-------------------------------------------
        for ($i=0; $i<3; $i++) {
            $campus = new Campus();
            $campus->setName($generator->randomElement(["ENI Rennes","ENI Nantes","ENI Niort"]));
            $manager->persist($campus);
        }
        //-------------------------------------------
        //Création des City
        //-------------------------------------------
        for ($i=0; $i<15; $i++) {
            $city = new City();
            $city->setName($generator->city)
                ->setZipCode($generator->randomNumber(5, true));
            $manager->persist($city);
        }
        //-------------------------------------------
        //Création des User
        //-------------------------------------------



        for ($i=0; $i<25; $i++) {
            $user = new User();
            $user->setNickname($generator->userName);
            $user->setFirstName($generator->firstName);
            $user->setLastName($generator->lastName);
            $user->setEmail($generator->email);
            //$user->setRoles($generator->randomElement([$role3Abonne->getLibelle(), $role1Admin->getLibelle()]);
            //$user->setAbonnement($abonnement1Free);
            $user->setActive("Active");
//        $campus=new Campus();
//        $campus->setName()
//            ->setId();
            $user->setCampus($campus);
            $user->setPhoneNumber($generator->phoneNumber);

            //Pass

            //$user->setPassword($generator->$this->hasher->hashPassword($user, 'test123456'));

            $plainPassword = "123456";
            $encoded = $this->encoder->hashPassword($user, $plainPassword);
            $user->setPassword($encoded);

            $manager->persist($user);
        }

        $user=new User();

        $plainPassword = "123456";
        $encoded = $this->encoder->hashPassword($user, $plainPassword);

        $user->setLastName("test")
            ->setFirstName("test")
            ->setEmail("test@test.fr")
            ->setActive("Active")
            ->setRoles(["ROLE_ADMIN"])
            ->setCampus($campus)
            ->setPhoneNumber("0123456789")
            ->setNickname("chaussettes");

        $user->setPassword($encoded);
        $manager->persist($user);

        //-------------------------------------------
        //Création des Location
        //-------------------------------------------

        for ($i=0; $i<25; $i++) {
            $location = new Location;
            $location->setLatitude($generator->latitude)
                ->setLongitude($generator->longitude)
                ->setName($generator->name)
                ->setStreet($generator->streetName)
                ->setCity($city);

            $manager->persist($location);

        }


        //-------------------------------------------
        //Création des State
        //-------------------------------------------


        $state1 = new State();
        $state1->setTag("saved");

        $state2 = new State();
        $state2->setTag("open");

        $state3 = new State();
        $state3->setTag("closed");

        $state4 = new State();
        $state4->setTag("ongoing");

        $state5 = new State();
        $state5->setTag("over");

        $state6 = new State();
        $state6->setTag("cancelled");

        $manager->persist($state1);
        $manager->persist($state2);
        $manager->persist($state3);
        $manager->persist($state4);
        $manager->persist($state5);
        $manager->persist($state6);

//        //-------------------------------------------
//        //Création des Event
//        //-------------------------------------------
//
        for ($i=0; $i<25; $i++) {
            $event = new Event;
            $event->setCampus($campus)
                ->setDateLimitRegistration($generator->dateTimeBetween('+1 week', '+6 week'))
                ->setDateTimeStart($generator->dateTimeBetween('+7 week', '+10 week'))
                ->setDuration($generator->randomNumber(3,false))
                ->setInfoEvent($generator->realText(200))
                ->setLocation($location)
                ->setName($generator->name)
                ->setNumMaxRegistration($generator->randomNumber(3,false))
                ->setState($state2);

            $manager->persist($event);

        }


        //----------------------------------------------------------------------------------
        // Validation dans la BD
        //----------------------------------------------------------------------------------
        $manager->flush();
    }
}