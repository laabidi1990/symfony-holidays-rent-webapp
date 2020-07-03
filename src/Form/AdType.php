<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdType extends AbstractType
{
    use ApplicationType;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getFormConfig('Titre', "Taper un titre pour votre annonce"))
            ->add('introduction', TextType::class, $this->getFormConfig('Introduction', "Décriver votre annonce globalement"))
            ->add('content', TextareaType::class, $this->getFormConfig('Description', "Donner une description détaillée et attirante"))
            ->add('rooms', IntegerType::class, $this->getFormConfig('Nombre de chambres', "Indiquer le nombre de chambres de votre bien"))
            ->add('price', MoneyType::class, $this->getFormConfig('Prix par nuit', "Indiquer le prix d'une nuit"))
            ->add('coverImage', UrlType::class, $this->getFormConfig("Url de l'image principale", "Donner l'addresse d'une image"))
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
