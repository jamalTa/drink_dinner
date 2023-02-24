<?php

namespace App\Controller\Admin;

use App\Classes\CommandeManager;
use App\Classes\Panier;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/commande')]
class CommandeController extends AbstractController
{   
   
    #[Route('/liste-commande/{param}', name: 'app_commande_index',defaults:[ 'param'=> null], methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository,$param): Response
    {
        switch ($param){

            case 'livre':
                $commande = $commandeRepository->findBy(['statu' => true ]);
                break;
            case 'nlivre':
                $commande = $commandeRepository->findBy(['statu' => false ]);
                break;
            case 'tout':
                $commande = $commandeRepository->findAll();
                break;
            default:
                $commande = $commandeRepository->findBy(['statu' => false ]);
                break;
            }
        
        return $this->render('commande/index.html.twig', [
            'commandes' => $commande,
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommandeRepository $commandeRepository): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->add($commande);
            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,                  
            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->add($commande);
            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $commandeRepository->remove($commande);
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/livraison/{id}', name: 'app_commande_livre', methods: ['GET'])]
    public function livre(Commande $commande, EntityManagerInterface $manager): Response
    {
        $commande->setStatu(true);
        $manager->persist($commande);
        $manager->flush();
        
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,  
                
            
        ]);
    }
}
