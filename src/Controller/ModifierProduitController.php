<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Entity\Supplement;
use App\Repository\CondimentRepository;
use App\Repository\SauceRepository;
use App\Repository\SupplementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/modifierProduit', name:'modifierProduit_')]
class ModifierProduitController extends AbstractController
{

    #[Route('/{id}', name: 'commande', methods: ['GET'])]
    public function showCommande(Produit $produit, SauceRepository $sauce, SupplementRepository $supplement, CondimentRepository $condiment ): Response
    {


        
        
    return $this->render('modifier_produit/modifier_produit.html.twig', [     
        'produit'=> $produit, 
        'sauces' =>  $sauce->findAll(),    
        'supplements'=> $supplement->findAll(),
        'condiments'=> $condiment->findAll(),
    ]);

        
    }
}
