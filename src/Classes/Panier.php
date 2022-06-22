<?php

namespace App\Classes;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Panier
{
    private $session;

    public function __construct(SessionInterface $sessionInterface)
    {
        $this->session = $sessionInterface;
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

}
