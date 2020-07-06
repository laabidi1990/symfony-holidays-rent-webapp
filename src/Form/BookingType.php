<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\FrenshDateTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends AbstractType
{
    use ApplicationType;

    private $transformer;

    public function __construct(FrenshDateTransformer $transformer)
    {
        return $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', TextType::class, $this->getFormConfig(
                        "Date d'arrivée", "Cliquer pour choisir la date d'arriver"
                    )
                )
            ->add('endDate', TextType::class, $this->getFormConfig(
                        "Date de départ", "Cliquer pour choisir la date de départ"
                    )
                )
            ->add('comment', TextareaType::class, $this->getFormConfig(
                        false ,
                        "Si vous avez un commentaire n'hésitez pas à e faire une.. (optionnel)", 
                        ["required" => false],
                    )
                )
        ;

        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
