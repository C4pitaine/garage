<?php

namespace App\Controller;

use App\Entity\Cars;
use App\Entity\Image;
use App\Form\CarsType;
use App\Repository\CarsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CarsController extends AbstractController
{
    /**
     * Permet d'afficher toutes les voitures
     *
     * @param CarsRepository $repo
     * @return Response
     */
    #[Route('/cars', name: 'cars_index')]
    public function index(CarsRepository $repo): Response
    {
        $cars = $repo->findAll();
        return $this->render('cars/index.html.twig', [
            'cars' => $cars
        ]);
    }

    /**
     * Permet d'ajouter une voiture à la bdd
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/cars/new',name:'cars_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $car = new Cars();

        $form = $this->createForm(CarsType::class, $car);

        $form->handleRequest($request);

       

        if($form->isSubmitted() && $form->isValid())
        {
            foreach($car->getImages() as $image)
            {
                $image->setCars($car);
                $manager->persist($image);
            }
            $manager->persist($car);
            $manager->flush();

            $this->addFlash('success','<div>La '.$car->getMarque().' '.$car->getModele().' a bien été ajouté</div>');

            return $this->redirectToRoute('cars_show', [
                'id' => $car->getId()
            ]);
        }

        return $this->render('cars/new.html.twig',[
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier une annonce
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Cars $car
     * @return Response
     */
    #[Route('cars/{id}/edit', name:"cars_edit")]
    public function edit(Request $request, EntityManagerInterface $manager, Cars $car): Response
    {
        $form = $this->createForm(CarsType::class, $car);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            foreach($car->getImages() as $image)
            {
                $image->setCars($car);
                $manager->persist($image);
            }

            $manager->persist($car);

            $manager->flush();

            $this->addFlash('warning', '<div>La '.$car->getMarque().' '.$car->getModele().' a bien été mis à jour</div>');

            return $this->redirectToRoute('cars_show', [
                'id' => $car->getId()
            ]);
        }

        return $this->render('cars/edit.html.twig', [
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher une voiture en fonction de son id
     *
     * @param integer $id
     * @param Cars $car
     * @return Response
     */
    #[Route('/cars/{id}', name:'cars_show')]
    public function show(int $id, Cars $car): Response
    {

        return $this->render('cars/show.html.twig', [
            'car' => $car
        ]);
    }
}
