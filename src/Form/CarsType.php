<?php

namespace App\Form;

use App\Entity\Cars;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CarsType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque',TextType::class,$this->getConfiguration('Marque:','La marque de la voiture'))
            ->add('modele',TextType::class,$this->getConfiguration('Modèle:','Le modèle de la voiture'))
            ->add('coverImg',UrlType::class,$this->getConfiguration('Url de l\'image','La photo de couverture de la voiture'))
            ->add('km',IntegerType::class,$this->getConfiguration('Kilomètrage:','Le nombre de km de la voiture'))
            ->add('prix',IntegerType::class,$this->getConfiguration('Prix:','Le prix de la voiture'))
            ->add('proprietaire',IntegerType::class,$this->getConfiguration('Nombre de propriétaire:','Le nombre de propriétaires de la voiture'))
            ->add('cylindree',IntegerType::class,$this->getConfiguration('Cylindrée:','La cylindrée de la voiture'))
            ->add('puissance',IntegerType::class,$this->getConfiguration('Puissance:','Le nombre de cheveaux de la voiture'))
            ->add('carburant',TextType::class,$this->getConfiguration('Carburant','Le type de carburant de la voiture'))
            ->add('annee',TextType::class,$this->getConfiguration('Année:','L\'annéee d\'immatriculation de la voiture'))
            ->add('transmission',TextType::class,$this->getConfiguration('Transmission:','Le type de transmission de la voiture'))
            ->add('description',TextType::class,$this->getConfiguration('Description:','Petite description de la voiture'))
            ->add('options',TextType::class,$this->getConfiguration('Options:','Les options de la voiture'))
            ->add('slugMarque',TextType::class,$this->getConfiguration('SlugMarque','Fait automatiquement',['required'=>false]))
            ->add('slugModele',TextType::class,$this->getConfiguration('SlugModèle','Fait automatiquement',['required'=>false]))
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class, // on vient chercher le formulaire qu'on a créer pour les images
                'allow_add' => true, // pour le data_prototype
                'allow_delete' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cars::class,
        ]);
    }
}
