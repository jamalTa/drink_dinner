<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('prix')            
            ->add('image', FileType::class,
            [
                
                'label' => 'Ajouter une image',
                'data_class' => null,
                'required' =>false,
                //  Enregistre la nouvelle clé "empty_data" avec une chaîne vide et rajouter ? setimage  "public function setImage(?string $image): self"
                'empty_data' => ''
            ])
            ->add('categorie', EntityType::class,[
                'class' => Categorie::class,
                'choice_label' => 'nom'
            ])   
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
