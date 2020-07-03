<?php

namespace App\Controller;

use App\Entity\UpdatePassword;
use App\Entity\User;
use App\Form\EditAccountType;
use App\Form\RegistrationType;
use App\Form\UpdatePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * permet de se connecter
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        return $this->render('account/login.html.twig', [
            'hasErrors' => $error !== null
        ]);
    }

    /**
     * permet de déconnecter
     * 
     * @route("/logout", name="account_logout")
     *
     * @return Response
     */
    public function logout() 
    {

    }

    /**
     * permet de créer un nouveau compte
     * 
     * @route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) 
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hashedPassword = $encoder->encodePassword($user, $user->getHashedPassword());
            $user->setHashedPassword($hashedPassword);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Votre compte a été crée avec succée, connectez vous");

            return $this->redirectToRoute('account_login');

        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * permet de modifier le profile de l'utilisateur
     * 
     * @Route("/account/edit", name="account_edit")
     *
     * @return Response
     */
    public function editAccount(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditAccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Votre compte a été modifié avec succée");

        }

        return $this->render('account/editAccount.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Modification du password
     * 
     * @Route("/account/update-password", name="update_password")
     *
     * @return Response
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $updatePassword = new UpdatePassword();
        $user = $this->getUser();
        $form = $this->createForm(UpdatePasswordType::class, $updatePassword);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // 1. vérifier que le oldPassword du form est le meme que le password du user
            if (! password_verify($updatePassword->getOldPassword(), $user->getHashedPassword())) {
                $form->get('oldPassword')->addError((new FormError("le mot de pas passe que vous avez tapé n'est votre mot de passe actuel")));
            } else {
                $newPassword = $updatePassword->getNewPassword();
                $hashedPassword = $encoder->encodePassword($user, $newPassword);

                $user->setHashedPassword($hashedPassword);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', "Votre mot de passe a été modifié avec succée");

                return $this->redirectToRoute('homepage');

            }
        }

        return $this->render('account/updatePassword.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page de l"utilisateur actuel
     * 
     * @Route("/account", name="account_index")
     *
     * @return Response
     */
    public function account()
    {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
