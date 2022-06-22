<?php

namespace App\Controller;

use DateTime;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }


    #[Route('/utilisateur-contact', name: 'user_contact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContactRepository $contactRepository): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // _________debut code_____
            // je récupére la date du jour 
            $date_jour = new DateTime()  ;
            //je set l'object contact avec la date du jour
            $contact->setDateEnvoi($date_jour);//
            // je récupére le user connécté 
            $user = $this->getUser();
            // je set mon object contact avec le user connécté
            $contact->setUser($user);
            //_________fin code _________
            $contactRepository->add($contact);
            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/new_contact.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }
    
}
