<?php

namespace App\Controller;

use App\Repository\CarsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Permet de donner à l'accueil les 4 dernières voitures de la bdd
     *
     * @param CarsRepository $repo
     * @return Response
     */
    #[Route('/', name: 'homepage')]
    public function index(CarsRepository $repo): Response
    {

        $cars = $repo->findBy($criteria = [], $orderBy = ['id' => 'DESC'], $limit = "4", $offset = null);
        
        return $this->render('index.html.twig', [
            "cars" => $cars
        ]);
    }
}
