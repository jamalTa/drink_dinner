<?php

namespace App\Controller;

use App\Classes\Panier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier')]
    public function index(Panier $panier): Response
    {
       // $panier->addProduitPanier(2);
        return $this->render('panier/index.html.twig', [
           'panier' =>$panier->getTableauPanier()
        ]);
    }

    #[Route('/delete-panier', name: 'delete_panier')]
    public function delete(Panier $panier): Response
    {
        $panier->deletePanier();
        return $this->redirectToRoute('panier');
    }

    #[Route('/add-panier', name: 'add_panier')]
    public function add(Panier $panier, $id ): Response
    {
        $panier-> addProduitPanier($id);
        return $this->redirectToRoute('panier');
    }

    #[Route('/delete-quantity-panier', name: 'delete_quantity_panier')]
    public function deleteQuantityProd(Panier $panier ): Response
    {
        $panier-> deleteQuantityProd(17);
        return $this->redirectToRoute('panier');
    }






}
