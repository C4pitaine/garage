<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',TextType::class,$this->getConfiguration('Prénom:','Votre prénom...'))
            ->add('lastName',TextType::class,$this->getConfiguration('Nom:','Votre nom de famille...'))
            ->add('email',EmailType::class,$this->getConfiguration('Email:','Votre email...'))
            ->add('password',PasswordType::class,$this->getConfiguration('Mot de passe:','Votre mot de passe...'))
            ->add('confirmPassword',PasswordType::class,$this->getConfiguration('Confirmation:','Confirmer le mot de passe...'))
            ->add('picture',FileType::class,[
                'label' => "Photo de profil",
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
