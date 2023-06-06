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

        for ($i=0; $i<6; $i++) {
            $state = new State();
            $state->setTag($generator->randomElement(["created","open","closed","ongoing","over","cancelled"]));


        $manager->persist($state);

        }


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
                ->setState(2);

            $manager->persist($event);

        }


        //----------------------------------------------------------------------------------
        // Validation dans la BD
        //----------------------------------------------------------------------------------
        $manager->flush();
    }
}