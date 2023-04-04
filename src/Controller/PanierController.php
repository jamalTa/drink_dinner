<?php

namespace App\Controller;


use App\Classes\Panier;
use App\Form\ConfirmationLivraisonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier')]
    public function index(Panier $panier): Response
    {
       // $panier->addProduitPanier(2);
        return $this->render('panier/index.html.twig', [
           'panier' =>$panier->getDetailPanier(),
           'total_panier' => $panier->getTotalPanier()
        ]);
    }

    #[Route('/delete-panier', name: 'delete_panier')]
    public function delete(Panier $panier): Response
    {
        $panier->deletePanier();
        return $this->redirectToRoute('panier');
    }

    #[Route('/add-panier/{id}', name: 'add_panier')]
    public function add(Panier $panier, $id): Response
    {
        $panier-> addProduitPanier($id);
        return $this->redirectToRoute('panier');
    }

    #[Route('/delete-quantity-panier/{id}', name: 'delete_quantity_panier')]
    public function deleteQuantityProd(Panier $panier, $id): Response
    {
        $panier->deleteQuantityProd($id);
        return $this->redirectToRoute('panier');
    }

    #[Route('/confirmer-panier', name:'confirme_panier')]
    public function confirmePanier(Request $request, EntityManagerInterface $manager, Panier $panier)
    {
        //je récupére l utilisateur connecté
        $user = $this->getUser();
         // si notre client n'a pas renseigné un nom ou une adresse ou un code postal
        // on le renvoie vers un formulaire pour compléter les données 
        if(!$user->getNom() || !$user->getAdresse() || !$user->getCodePostal())
        {
            $form = $this->createForm(ConfirmationLivraisonType::class, $user);
            $form->handleRequest($request);    

            if($form->isSubmitted() && $form->isValid())
            {
                $manager->persist($user);
                $manager->flush();
                return $this->redirectToRoute('confirme_panier');
            }
            return $this->render('panier/confirmation_panier.html.twig',[
                'form' => $form->createView()
    
            ]);

        }
        //si l utilisateur a enregistrer son adresse de livraison je lui retourne la vue du panier
        //sinon je lui envoie un formulaire 
        return $this->render('panier/recap_panier.html.twig',[
            'panier'=>$panier->getDetailPanier(),
            'total_panier'=>$panier->getTotalPanier()
    
        ]);
        
    }


}


