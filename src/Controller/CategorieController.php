<?php

namespace App\Controller;


use App\Entity\Categorie;
use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/categorie', name:'categorie_')]
class CategorieController extends AbstractController
{    
    public function __construct(protected CategorieRepository $categorieRepository)
    {
        
    }
    
    #[Route('/', name: 'catalogue')]
    public function index( ): Response
    {        
        return $this->render('detail_categories/index.html.twig', [
            'list_categories' => $this->categorieRepository->findAll()
        ]);
    }

    #[Route('/{nom}', name: 'list')]
    public function list(string $nom): Response
    {
       $categorie = $this->categorieRepository->findOneByNom($nom);
        return $this->render('detail_categories/list.html.twig',[
          'list_produits' => $categorie->getProduits(),
          'nom'=> $nom,
          
        ]); 
    } 

   

    
}

