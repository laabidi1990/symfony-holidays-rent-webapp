<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    use ApplicationType;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', IntegerType::class, $this->getFormConfig('Note sur 5', "Veuillez indiqué votre note de 0 à 5", [
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 1
                ] 
            ]))
            ->add('body', TextareaType::class, $this->getFormConfig('Votre avis / témoignage', "Merci d'ètre honnete et précis, cela aidera nos futurs voyageurs"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
