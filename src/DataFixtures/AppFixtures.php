<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Marques;
use App\Entity\User;
use App\Entity\Voitures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{   
    // gestion du hasher de password
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        //création d'un admin 
        $admin = new User();
        $admin->setEmail('admin@epse.be')
              ->setRoles(['ROLE_ADMIN'])
              ->setPassword($this->passwordHasher->hashPassword($admin,'password'));
        $manager->persist($admin);
        
            /**
             * Data db model
             */
            $marqueNom = array(
                'audi' , 'BMW', 'Alfa Romeo', 'Fiat', 'Toyota', 'Seat', 'Volkswagen', 'Mercedes-Benz'
            );
            $marqueCover = array(
                'images/marqueimg/L_Audi.png', 'images/marqueimg/L_BMW.png', 'images/marqueimg/L_Alfa Romeo.png', 'images/marqueimg/L_Fiat.png', 'images/marqueimg/L_Toyota.png', 'images/marqueimg/L_Seat.png', 'images/marqueimg/L_Volkswagen.png', 'images/marqueimg/L_Mercedes-Benz.png',
            );
        
        /**
         * for pour set le nom et la cover de chaque marque existante
         */
        for ($i=0; $i < 8 ; $i++) { 
            $marque = new Marques();
            $marque->setNom($marqueNom[$i])
                ->setCover($marqueCover[$i]);

             /**
             * data db voiture
             * ajouter des voitures
             */
            for ($j=0; $j < rand(5,20) ; $j++){ 
                $cl = array( 1.6, 1.8, 2, 2.3, 2.6 );
                
                $voiture = new Voitures();
                $voiture->setModele($faker->Word())
                        ->setKm(rand(5000,250000))
                        ->setPrix(rand(3000,25000))
                        ->setCylindree(shuffle($cl))
                        ->setPuissance(rand(99,200))
                        ->setCarburant("diesel")
                        ->setAnneeCirculation($faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now'))
                        ->setTransmission("arrière")
                        ->setNbProprio(rand(1,5))
                        ->setDescription(join( $faker->paragraphs(1)))
                        ->setOptionCar(join( $faker->words(5)))
                        ->setMarque($marque)
                        ->setCoverImg('cover.png');
                $manager->persist($voiture); 
            }

        $manager->persist($marque);
        }
        

        $manager->flush();

            
    }

}
