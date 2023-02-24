<?php

namespace App\Classes;

use DateTime;
use App\Entity\User;
use App\Entity\Commande;
use App\Entity\DetailCommande;
use Symfony\Component\Security\Core\Security;

class CommandeManager
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

 

    /**
     * Undocumented function
     *
     * @return User
     */
    public function getUserConnecte()
    {
        $user = $this->security->getUser();
        return $user;

    }

      /**
    * Méthode de création d'un objet de la class commande par apport aux donnéé dans le panier de l'utilisateurs connécté
    *@return Commande
    */ 

    public function getCommande(Panier $panier)
    {
        //je créé un nouvel objet commande 
        $commande = new Commande();
        //je récupère l utilisateur connécté 
        $user = $this->getUserConnecte();
        //je set l objet commande
        $commande->setUser($user);
        //je récupere la date du jour
        $date_commande = new DateTime();
        //je set l objet commande avec la date du jour
        $commande->setDateCommande($date_commande);
        // je créé le nom complet de l'utilisateur
        $nom = $user->getNom(). ' ' . $user->getprenom();
        $commande->setNom($nom);
        //je construit l'adresse en string
        $adresse = $user->getAdresse(). ' ' . $user->getCodePostal();
        $commande->setAdresseLivraison($adresse);
        $commande->setStatu(false);
        //je récupere le total du panier
        $commande->setTotal($panier->getTotalPanier());


        return $commande;
    }

    /**
     * 
     */

    public function getDetailCommande(Commande $commande, $row_panier)
    {
        //je créé un objet detail_commande
        $detail = new DetailCommande();

        $detail->setCommande($commande);
        //je set le nom du produit on le récupére de la ligne 
        $detail->setNom($row_panier['produit']->getNom());
        //$detail->setRef($ref)
        $detail->setPrixUnitaire($row_panier['produit']->getPrix());
        $detail->setQuantiter($row_panier['quantite']);
        $detail->setTotal($row_panier['total']);

        return $detail;

    }




}