<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Cars;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $slugify = new Slugify();

        $marques=['Renault','Mercedes','Porshe','BMW','Audi','Volvo','Volkswagen','Alfa Romeo'];
        $modeles=['Arkana','CLA','Gt3 RS','Serie 3','Q3','XC40','Golf 8','Giulia'];
        $coverImgs=['arkana.jpg','cla.jpg','gt3.png','serie3.jpg','q3.jpg','xc40.jpg','golf8.jpg','giulia.jpeg'];
        $carburants=['essence','diesel','electrique','hybride'];
        $annees=['2018','2019','2020','2021','2022','2023'];
        $transmissions=['traction','propulsion','intégrale'];
        $description = '<p>'.join('</p><p>',$faker->paragraphs(3)).'</p>';
        $options = $faker->paragraph(3);

        for($i=0;$i<8;$i++)
        {
            $car = new Cars();

            $marque = $marques[$i];
            $modele = $modeles[$i];
            $coverImg = $coverImgs[$i];

            $carburant = $faker->randomElement($carburants);
            $annee = $faker->randomElement($annees);
            $transmission = $faker->randomElement($transmissions);

            $car->setMarque($marque)
                ->setModele($modele)
                ->setCoverImg($coverImg)
                ->setKm(rand(2000,200000))
                ->setPrix(rand(10000,80000))
                ->setProprietaire(rand(0,8))
                ->setCylindree(rand(1400,2000))
                ->setPuissance(rand(70,200))
                ->setCarburant($carburant)
                ->setAnnee($annee)
                ->setTransmission($transmission)
                ->setDescription($description)
                ->setOptions($options);

            $manager->persist($car);
        }

        


        $manager->flush();
    }
}
