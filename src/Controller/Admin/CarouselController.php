<?php

namespace App\Controller\Admin;

use App\Entity\Carousel;
use App\Form\Carousel1Type;
use App\Repository\CarouselRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/carousel')]
class CarouselController extends AbstractController
{
    #[Route('/', name: 'app_carousel_index', methods: ['GET'])]
    public function index(CarouselRepository $carouselRepository): Response
    {
        return $this->render('carousel/index.html.twig', [
            'carousels' => $carouselRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_carousel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CarouselRepository $carouselRepository): Response
    {
        $carousel = new Carousel();
        $form = $this->createForm(Carousel1Type::class, $carousel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

             // je récupére le fichies passé dans le form
             $image = $form->get('nom')->getdata();
             
             // si il y a une image de chargée
             if ($image) {
                 // je crée un nom unique pour cette image et je remet l'extension
                 $img_file_name = uniqid() . '.' . $image->guessExtension();
                 // enregistrer le fichier dans le dossier image 
                 $image->move($this->getParameter('upload_dir'), $img_file_name);
                 // je set l'object article
                 $carousel->setNom($img_file_name);
             } else {
                 // si $image = null je set l'image par default
                 $carousel->setNom('defaultimg.jpg');
             }

      

            $carouselRepository->add($carousel);
            return $this->redirectToRoute('app_carousel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carousel/new.html.twig', [
            'carousel' => $carousel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carousel_show', methods: ['GET'])]
    public function show(Carousel $carousel): Response
    {
        return $this->render('carousel/show.html.twig', [
            'carousel' => $carousel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_carousel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Carousel $carousel, CarouselRepository $carouselRepository): Response
    {
        $form = $this->createForm(Carousel1Type::class, $carousel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carouselRepository->add($carousel);
            return $this->redirectToRoute('app_carousel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carousel/edit.html.twig', [
            'carousel' => $carousel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carousel_delete', methods: ['POST'])]
    public function delete(Request $request, Carousel $carousel, CarouselRepository $carouselRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carousel->getId(), $request->request->get('_token'))) {
            $carouselRepository->remove($carousel);
        }

        return $this->redirectToRoute('app_carousel_index', [], Response::HTTP_SEE_OTHER);
    }
}
