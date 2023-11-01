<?php

namespace App\Controller;

use App\Repository\CarsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(CarsRepository $repo): Response
    {
        /**
         * Permet de donner à l'accueil les 4 dernières voitures de la bdd
         */
        $cars = $repo->findBy($criteria = [], $orderBy = ['id' => 'DESC'], $limit = "4", $offset = null);
        /**
         * Affiche la page d'Accueil
         */
        return $this->render('index.html.twig', [
            "cars" => $cars
        ]);
    }
}
