<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

      
     #[Route("/detail-categorie", name:"show_detail_categorie")]     
    public function showCategorie(CategorieRepository $categorieRepository): Response
    {
        return $this->render('home/detail_categorie.html.twig', [
            'list_categories' => $categorieRepository->findAll()
        ]);
    }

    #[Route('/produit/{id}', name: 'show_produit_categorie')]
    public function showProduit(Categorie $categorie, ProduitRepository $produitRepository): Response
    {
        return $this->render('home/detail_produit_categorie.html.twig', [
            'list_produits' => $produitRepository->findAll(),
            'produit' => $categorie->getProduits(),
            
        ]);
    }
}
