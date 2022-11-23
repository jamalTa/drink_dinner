<?php

namespace App\Classes;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Panier
{
    private $session;
    private $produitRepository;

    public function __construct(SessionInterface $sessionInterface, ProduitRepository $produitRepository)
    {
        $this->session = $sessionInterface;
        $this->produitRepository = $produitRepository;
    }

    /**
     * Fonction qui ajoute un produit au panier
     *si le produit existe, elle incremente de 1 à la quantité
     * @param Int $id
     *
     */
    public function addProduitPanier($id)
    {
        //je récupére un tableau 'panier' , si il n'existe pas, on créer un tableau vide
        $panier = $this->session->get('panier', []);
        //je vérifie si le produit ($id) ce trouve dans le panier
        if(!empty ($panier[$id]))
        {
            //si oui j incrémente de 1 le produit existant
            $panier[$id] = $panier[$id] + 1;
        }else{
            // j enregistre le nouveau produit avec la valeur 1(quantité)
            $panier[$id] = 1;
        }
        //je met à jour mon tableau panier avec les nouvelles valeur
        $this->session->set('panier', $panier);
    }

    /**
     * renvoie le panier
     */

    public function getTableauPanier()
    {
        //récupére le tableau de la session si elle ne trouve rien, elle renvoie un tableau vide
        return $this->session->get('panier', []);
    }      
    
    /**
     * supprime le panier
     */

     public function deletePanier()
     {
         $this->session->remove('panier');
     }

     /**
      * supprimer une quantité au panier
      */

      public function deleteQuantityProd($id)
      {
        //je récupére le panier
        $panier = $this->getTableauPanier();
        //je vérifie que le produit ce trouve dans le panier
        if(!empty( $panier [$id])){
            if($panier [$id] > 1){
                //si quantité est superireur à 1, j enlève le produit
                $panier[$id] = $panier [$id] -1 ;
            }else{
            //sinon je supprime le produit 
            unset($panier[$id]);
            }
        }
        
        //j enregistre les modifs dans le panier
        $this->session->set('panier', $panier);

    }
    
    /**
     * fonction qui renvoie le tableau avec le detail des produits commander
     */

    public function getDetailPanier()
    {
        //je recupere le panier
        $panier= $this->getTableauPanier();
        //je créer un tableau vide
        $detail_panier= [];
        //je boucle sur le tableau panier
        foreach($panier as $id=>$quantity)
        {
            $produit =$this->produitRepository->find($id);            
            //j ajoute l objet produit et la quantité récupéré au tableau vide
            if($produit)//si il y a un produit dans la bdd 
            {
                //je declare une variable taux qui sera de la valeur du montant de la tva 
                //$taux = $produit->getTva()->getTaux();
                //total qui aura pour valeur la quantité de produit * le prix
                $total = $quantity * $produit->getPrix();
                //$ht    = $total/(1+($taux/100));
               // $tva  = $total - $ht;
               $detail_panier [] =
               [
                   'produit' => $produit,               
                   'quantite'=> $quantity ,
                   'total' => $quantity * $produit->getPrix()
               ];
            }
        }
        // je renvoie le nouveau tableau
        return $detail_panier;
    }

    /**
     * fonction qui retourne le total du panier
     */

    public function getTotalPanier()
    {
        //je recupere le tableau detail panier
        $panier = $this->getDetailPanier();
        //je déclare une variable total_panier
        $total_panier = 0;
        //je boucle dans mon tableau afin de récupérer les montant par ligne
        foreach($panier as $ligne)
        {
            $total_panier = $total_panier + $ligne['total'];
            
        }
        return $total_panier;
    }

    /**
     * fonction qui renvoie sur un vue pour confirmer le panier
     */

     public function confirmePanier()
     {
        
     }



}
