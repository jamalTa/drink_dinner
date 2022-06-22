<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
              // je récupére le fichies passé dans le form
              $image = $form->get('image')->getdata();
              // si il y a une image de chargée
              if ($image) {
                  // je crée un nom unique pour cette image et je remet l'extension
                  $img_file_name = uniqid() . '.' . $image->guessExtension();
                  // enregistrer le fichier dans le dossier image 
                  $image->move($this->getParameter('upload_dir'), $img_file_name);
                  // je set l'object article
                  $produit->setImage($img_file_name);
              } else {
                  // si $image = null je set l'image par default
                  $produit->setImage('defaultimg.png');
              }
              //équivalent de persiste et flush
             $produitRepository->add($produit);
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        
        // 1 - je récupère l'ancien nom de l'image avant tout autre action
        $old_name_img= $produit->getImage(); // stocke l'ancien nom de l'image
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {  
              // récupérer le fichier image qui est passer dans le formulaire
              $image =$form->get('image')->getdata();
              // Si l'envoi de mon fichier n'est pas null
              if($image){
                  // on va get image et changer son nom afin qu'il n'y ai pas deux images avec le même nom.
                  $img_file_name = uniqid().'.'.$image->guessExtension(); //unique id méthode php basé sur le temps = ajout de la même extension. Je code quelquechose d'unique et je rajoute son extension existante (.jpg / .png / . tiff ...).
                  // enregistrer le fichier dans le dossier image 
                  $image->move($this->getParameter('upload_dir') , $img_file_name); //méthode qui attend deux arguments : où et quoi ? 
                  // j'envoie sur le serveur et je le set afin que l'image correspond à l'article
                  $produit->setImage($img_file_name);
  
                  // essaye du unlink pour supprimer l'image de la BDD quand on la remplace
                  $name_file_delete=$this->getParameter('upload_dir'). $old_name_img; // je vais récupérer le chemin de l'ancienne image et le nom de mon ancienne image
                  if(file_exists($name_file_delete) && is_file($name_file_delete)) // On test si il y a bien un chemin et si il y a bien un fichier
                  {
                      unlink($name_file_delete); // Unlink réclame un chemin en paramètre
                  }
  
              } else{
                  // si c'est null, je remplace par autre chose
                  $produit->setImage($old_name_img); // si $image is not null, je remets l'aimage avec son ancien nom "$old_name_img"
  
              }          
            $produitRepository->add($produit);
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit);
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
