<?php

namespace App\Controller;

use App\Entity\Produit;

use App\Entity\Categorie;
use App\Repository\CarouselRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(CarouselRepository $carouselRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'carousel' =>$carouselRepository->findAll()
        ]);
    }


    
    // #[Route('/detail-produit/{id}', name: 'show_produit_categorie')]
    // public function show(Categorie $categorie, ProduitRepository $produitRepository , $id): Response
    // {
    //     return $this->render('home/detail_produit_categorie.html.twig', [
    //         'list_produits' => $produitRepository->findAll(),
    //         'produit' => $categorie->getProduits(),
    //     ]);
    // }
    
  

    // #[Route('/produit/{id}', name: 'show_produit_categorie', methods:{" GET "})]
    // public function showProduit(Categorie $categorie, ProduitRepository $produitRepository): Response
    // {
    //     return $this->render('home/detail_produit_categorie.html.twig', [
    //         'list_produits' => $produitRepository->findAll(),
    //         'produit' => $categorie->getProduits(),
            
    //     ]);
    // }
}
