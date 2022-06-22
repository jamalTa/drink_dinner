<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')  
            ->add('image', FileType::class,
            [
                
                'label' => 'Ajouter une image',
                'data_class' => null,
                'required' =>false,
                  //  Enregistre la nouvelle clé "empty_data" avec une chaîne vide et rajouter ? setimage  "public function setImage(?string $image): self"
                  'empty_data' => ''
            ])     
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
