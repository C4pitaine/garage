<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Cars;
use App\Entity\User;
use App\Entity\Image;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $slugify = new Slugify();

        $marques=['Renault','Mercedes','Porshe','BMW','Audi','Volvo','Volkswagen','Alfa Romeo'];
        $modeles=['Arkana','CLA','Gt3 RS','Serie 3','Q3','XC40','Golf 8','Giulia'];
        $coverImgs=['https://www.auto-infos.fr/mediatheque/7/1/9/000276917_600x400_c.JPG','https://images.caradisiac.com/logos-ref/modele/modele--mercedes-cla-2-amg/S0-modele--mercedes-cla-2-amg.jpg','https://www.topgear.com/sites/default/files/2022/08/CFM_2458v1.jpg','https://cdn.lesnumeriques.com/optim/news/18/183299/dfb9091e-la-bmw-serie-3-s-offre-un-facelift-et-de-nouvelles-technologies-pour-2022__1200_900__0-33-2177-1260.jpeg','https://images.caradisiac.com/logos-ref/modele/modele--audi-q3-2e-generation-sportback/S0-modele--audi-q3-2e-generation-sportback.jpg','https://annuelauto.ca/wp-content/uploads/2022/05/6777b344fd630383b540589c24e55f4702fae0af.jpeg','https://assets.volkswagen.com/is/image/volkswagenag/VW_GTI_Night_34Front?Zml0PWNyb3AlMkMxJndpZD04MDAmaGVpPTUzMyZmbXQ9anBlZyZxbHQ9NzkmYmZjPW9mZiYyOTg5','https://im.qccdn.fr/node/actualite-alfa-romeo-giulia-premieres-impressions-107382/inline-116502.jpg'];
        $carburants=['essence','diesel','electrique','hybride'];
        $annees=['2018','2019','2020','2021','2022','2023'];
        $transmissions=['traction','propulsion','intégrale'];
        $description = '<p>'.join('</p><p>',$faker->paragraphs(3)).'</p>';
        $options = '<p>'.join('</p><p>',$faker->paragraphs(6)).'</p>';

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
                ->setProprietaire(rand(0,5))
                ->setCylindree(rand(1400,2000))
                ->setPuissance(rand(70,200))
                ->setCarburant($carburant)
                ->setAnnee($annee)
                ->setTransmission($transmission)
                ->setDescription($description)
                ->setOptions($options);

                for($j=1;$j<=rand(1,4);$j++)
                {
                    $image = new Image();

                    $url = $coverImg;
                    $caption = "La ".$marque." ".$modele." Image n°".$j;

                    $image->setUrl($url)
                          ->setCaption($caption)
                          ->setCars($car);
                    $manager->persist($image);
                }

            $manager->persist($car);
        }

        $users = [];
        $genres = ['male','femelle'];

        for($u=1;$u<=2;$u++)
        {
            $user = new User();
            $genre = $faker->randomElement($genres);

            $hash = $this->passwordHasher->hashPassword($user, 'password');

            $user->setFirstName($faker->firstName($genre))
                 ->setLastName($faker->lastName())
                 ->setEmail($faker->email())
                 ->setPassword($hash)
                 ->setPicture('');

            $manager->persist($user);
            $users[] = $user;
        }


        $manager->flush();
    }
}
