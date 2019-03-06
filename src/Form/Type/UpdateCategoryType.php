<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', TextType::class, array(
                    'label' => 'Modifiez le nom de votre catégorie',
                    'invalid_message' => 'Veuillez saisir une catégorie valide.'
                ))

                ->add('save', SubmitType::class);
    }
}
