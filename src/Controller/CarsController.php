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
use Symfony\Component\Security\Http\Attribute\IsGranted;
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
    #[IsGranted('ROLE_ADMIN')]
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
     * Permet d'afficher les voitures en fonction de leurs marques
     *
     * @param string $marque
     * @param CarsRepository $marque
     * @return Response
     */
    #[Route('cars/marque/{marque}', name:'cars_marque')]
    public function marque(string $marque,CarsRepository $repo): Response
    {
        $carsMarque = $repo->findAll();
        $cars = $repo->findBy($criteria = ['slugMarque' => $marque], $orderBy = ['id' => 'DESC'], $limit = "4", $offset = null);

        return $this->render('cars/marque.html.twig',[
            'carsMarque' => $carsMarque,
            'cars' => $cars,
            'marque' => $marque
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
    #[IsGranted('ROLE_ADMIN')]
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
     * Permet de supprimer une voiture de la bdd
     *
     * @param Cars $car
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/cars/{id}/delete',name:"cars_delete")]
    public function delete(Cars $car, EntityManagerInterface $manager): Response
    {
        $manager->remove($car);

        $manager->flush();

        $this->addFlash('success',"Vous avez bien supprimer la voiture : <strong>".$car->getMarque()." ".$car->getModele()."</strong>");

        return $this->redirectToRoute('cars_index');

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
