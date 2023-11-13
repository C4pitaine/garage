<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\AccountType;
use App\Entity\UserImgModify;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\UserImgModifyType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\TooManyLoginAttemptsAuthenticationException;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher le profil d'un utilisateur
     *
     * @return Response
     */
    #[Route('/account', name:"account_show")]
    #[IsGranted('ROLE_USER')]
    public function account(): Response
    {
        return $this->render('/account/account.html.twig',[
            'user' => $this->getUser()
        ]);
    }



    /**
     * Permet de se connecter
     *
     * @param AuthenticationUtils $utils
     * @return Response
     */
    #[Route('/login', name: 'account_login')]
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        $loginError = null;

        if($error instanceof TooManyLoginAttemptsAuthenticationException)
        {
            $loginError = "Trop de tentatives de connexion, veuillez attendre";
        }


        return $this->render('account/index.html.twig', [
            'hasError' => $error !== null,
            'loginError' => $loginError,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @return void
     */
    #[Route('/logout',name:"account_logout")]
    public function deco():void
    {

    }

    /**
     * Permet de s'inscrire
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route('/register',name:"account_register")]
    public function inscription(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $form['picture']->getData(); // récupère les information de l'image
            
            //gestion de l'image
            if(!empty($file))
            {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename."-".uniqid().'.'.$file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('uploads_directory'), //où on va l'envoyer
                        $newFilename // qui on envoit
                    );
                }catch(FileException $e)
                {
                    return $e->getMessage();
                }

                $user->setPicture($newFilename);
            }
            

            $hash = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('account_login');
        }

        return $this->render('/account/register.html.twig',[
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permet de mofidier un profil user
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/account/profile', name:"account_profile")]
    #[IsGranted('ROLE_USER')]
    public function editProfile(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        $fileName = $user->getPicture();

        if(!empty($fileName))
        {
            $user->setPicture(
                new File($this->getParameter('uploads_directory').'/'.$user->getPicture())
            );
        }

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user->setSlug('') // on a déjà le slug de fait
                 ->setPicture($fileName);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',"Les données ont été modidiées avec succès"
            );
        }

        return $this->render('/account/profile.html.twig',[
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permer de modifier le mot de passe de l'user
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route('/account/passwordEdit',name:"account_password")]
    #[IsGranted('ROLE_USER')]
    public function editPassword(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if(!password_verify($passwordUpdate->getOldpassword(), $user->getPassword()))
            {
                $form->get('oldPassword')->addError(new FormError("Le mot de passe actuel entré ne correspond pas avec votre mot de passe"));
            }else{
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $hasher->hashPassword($user, $newPassword);

                $user->setPassword($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success', "Votre mot de passe a été modifié avec succès"
                );

                return $this->redirectToRoute('account_profile');
            }
        }

        return $this->render('account/password.html.twig',[
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier l'image de profil d'un user
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/account/imgEdit', name:"account_imgedit")]
    #[IsGranted('ROLE_USER')]
    public function ImgEdit(Request $request, EntityManagerInterface $manager): Response
    {
        $imgEdit = new UserImgModify();

        $user = $this->getUser();
        $form = $this->createForm(UserImgModifyType::class, $imgEdit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if(!empty($user->getPicture()))
            {
                unlink($this->getParameter('uploads_directory').'/'.$user->getPicture());
            }

            $file = $form['newPicture']->getData();
            if(!empty($file))
            {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename."-".uniqid().'.'.$file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('uploads_directory'), //où on va l'envoyer
                        $newFilename // qui on envoit
                    );
                }catch(FileException $e)
                {
                    return $e->getMessage();
                }

                $user->setPicture($newFilename);
            }

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Votre image de profil a bien été modifiée');

            return $this->redirectToRoute('account_profile');
        }


        return $this->render('account/imgEdit.html.twig',[
            'myForm' => $form->createView()
        ]);
    }

    
}
