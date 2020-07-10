<?php

namespace App\Form;

trait ApplicationType
{
    /**
     * Permet d'avoir la configuration de base d'un champ de formulaire
     *
     * @param string $label
     * @param string $placeholder
     * @param string $options
     * @return array
     */
    protected function getFormConfig($label, $placeholder, $option = [])
    {
        return array_merge_recursive([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $option);
    }
}