<?php
namespace App\DataFixtures;
use App\Entity\Campus;
use App\Entity\City;
use App\Entity\User;
use App\Entity\Role;
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


        //--------------------------------------------------------------------------------------
        //Création des Roles
        //--------------------------------------------------------------------------------------
//        $role1Admin = new Role();
//        $role1Admin->setLibelle("ROLE_ADMIN");
//        $manager->persist($role1Admin);
//
//        $role2User = new Role();
//        $role2User->setLibelle("ROLE_USER");
//        $manager->persist($role2User);
//
//        $role3Abonne = new Role();
//        $role3Abonne->setLibelle("ROLE_ABONNE");
//        $manager->persist($role3Abonne);

        //--------------------------------------------------------------------------------------
        //Création des Abonnements
        //--------------------------------------------------------------------------------------
//        $abonnement1Free = new Abonnement();
//        $abonnement1Free->setLibelle("FREE");
//        $manager->persist($abonnement1Free);
//
//        $abonnement2Jour = new Abonnement();
//        $abonnement2Jour->setLibelle("JOUR");
//        $manager->persist($abonnement2Jour);
//
//        $abonnement3Mensuel = new Abonnement();
//        $abonnement3Mensuel->setLibelle("MENSUEL");
//        $manager->persist($abonnement3Mensuel);
//
//        $abonnement4Annuel = new Abonnement();
//        $abonnement4Annuel->setLibelle("ANNUEL");
//        $manager->persist($abonnement4Annuel);

        //-------------------------------------------
        //Création des Campus
        //-------------------------------------------
        for ($i=0; $i<5; $i++) {
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
                ->setZipCode($generator->randomNumber($nbDigits = 5, $strict = true));
            $manager->persist($city);
        }
        //-------------------------------------------
        //Création des User
        //-------------------------------------------



        for ($i=0; $i<25; $i++) {
            $user = new User();
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

            //Pass

            //$user->setPassword($generator->$this->hasher->hashPassword($user, 'test123456'));

            $plainPassword = "123456";
            $encoded = $this->encoder->hashPassword($user, $plainPassword);
            $user->setPassword($encoded);

            $manager->persist($user);
        }
		 //----------------------------------------------------------------------------------
        // Validation dans la BD
        //----------------------------------------------------------------------------------
        $manager->flush();
    }
}