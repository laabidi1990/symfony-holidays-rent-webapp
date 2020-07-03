<?php

namespace App\Form;

trait ApplicationType
{
    /**
     * Permet d'avoir la configuration de base d'un champ de formulaire
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    protected function getFormConfig($label, $placeholder)
    {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }
}