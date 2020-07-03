<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends AbstractType
{
    use ApplicationType;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getFormConfig('Prénom', "Entrez votre prénom"))
            ->add('lastName', TextType::class, $this->getFormConfig('Nom', "Entrez votre nom de famille"))
            ->add('email', EmailType::class, $this->getFormConfig('Email', "Entrez adresse email"))
            ->add('picture', UrlType::class, $this->getFormConfig('Photo de profile', "Entrez l'url de votre avatar"))
            ->add('hashedPassword', PasswordType::class, $this->getFormConfig('Mot de passe', "Entrer un fort mot de passe"))
            ->add('confirmPassword', PasswordType::class, $this->getFormConfig('Confirmation de mot de passe', "Confirmer votre mot de passe"))
            ->add('introduction', TextType::class, $this->getFormConfig('Introduction', "Présentez vous en quelques mots..."))
            ->add('description', TextareaType::class, $this->getFormConfig('Description', "Parlez un peut de vous..."))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
