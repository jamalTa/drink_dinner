<?php

namespace App\Controller;

use App\Classes\Panier;
use App\Classes\CommandeManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AchatController extends AbstractController
{
    #[Route('/achat', name: 'app_achat')]
    public function index(EntityManagerInterface $manager, Panier $panier, CommandeManager $commandeManager):Response
    {
        //je créé un objet commande avec la methode commande de la class commandemanager
        $commande = $commandeManager->getCommande($panier);        
        //je persist l objett commande 
        $manager->persist($commande);
        //je créé les objet detailcommande avec la methode getdetcomm de la class commanager
        $tableau = $panier->getDetailPanier();
        foreach($tableau as $row_panier)
        {
            $detail = $commandeManager->getDetailCommande($commande,$row_panier);
            $manager->persist($detail);
        }
        $manager->flush();
        $panier->deletePanier();
        // todo ici module stripe

        return $this->render('home/index.html.twig');
    }
}
