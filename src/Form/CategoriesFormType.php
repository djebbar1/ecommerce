<?php

namespace App\Form;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', null, [ 
            'label' => 'Nom'
        ])
        // ->add('categoryOrder', Categories::class, [ // Utilisez IntegerType pour le champ categoryOrder
        //     'label' => 'Ordre de la catégorie',
        //     'choice_label' => 'username',

        // ])
        ->add('parent', null, [ 
            'label' => 'Catégorie parente'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}