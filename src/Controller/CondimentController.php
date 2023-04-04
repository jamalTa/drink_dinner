<?php

namespace App\Controller;

use App\Entity\Condiment;
use App\Form\CondimentType;
use App\Repository\CondimentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/condiment')]
class CondimentController extends AbstractController
{
    #[Route('/', name: 'app_condiment_index', methods: ['GET'])]
    public function index(CondimentRepository $condimentRepository): Response
    {
        return $this->render('condiment/index.html.twig', [
            'condiments' => $condimentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_condiment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CondimentRepository $condimentRepository): Response
    {
        $condiment = new Condiment();
        $form = $this->createForm(CondimentType::class, $condiment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $condimentRepository->add($condiment);
            return $this->redirectToRoute('app_condiment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('condiment/new.html.twig', [
            'condiment' => $condiment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_condiment_show', methods: ['GET'])]
    public function show(Condiment $condiment): Response
    {
        return $this->render('condiment/show.html.twig', [
            'condiment' => $condiment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_condiment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Condiment $condiment, CondimentRepository $condimentRepository): Response
    {
        $form = $this->createForm(CondimentType::class, $condiment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $condimentRepository->add($condiment);
            return $this->redirectToRoute('app_condiment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('condiment/edit.html.twig', [
            'condiment' => $condiment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_condiment_delete', methods: ['POST'])]
    public function delete(Request $request, Condiment $condiment, CondimentRepository $condimentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$condiment->getId(), $request->request->get('_token'))) {
            $condimentRepository->remove($condiment);
        }

        return $this->redirectToRoute('app_condiment_index', [], Response::HTTP_SEE_OTHER);
    }
}
