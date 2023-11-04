<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword',PasswordType::class,$this->getConfiguration('Mot de passe actuel:',"Entrez votre mot de passe actuel.."))
            ->add('newPassword',PasswordType::class,$this->getConfiguration('Nouveau mot de passe:',"Entrez votre nouveau mot de passe"))
            ->add('confirmPassword',PasswordType::class,$this->getConfiguration('Confirmation:','Veuillez confirmer votre nouveau mot de passe'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
