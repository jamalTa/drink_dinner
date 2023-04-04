<?php

namespace App\Controller\Admin;

use App\Entity\Boisson;
use App\Form\BoissonType;
use App\Repository\BoissonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/boisson')]
class BoissonController extends AbstractController
{
    #[Route('/', name: 'app_boisson_index', methods: ['GET'])]
    public function index(BoissonRepository $boissonRepository): Response
    {
        return $this->render('boisson/index.html.twig', [
            'boissons' => $boissonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_boisson_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BoissonRepository $boissonRepository): Response
    {
        $boisson = new Boisson();
        $form = $this->createForm(BoissonType::class, $boisson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boissonRepository->add($boisson);
            return $this->redirectToRoute('app_boisson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('boisson/new.html.twig', [
            'boisson' => $boisson,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_boisson_show', methods: ['GET'])]
    public function show(Boisson $boisson): Response
    {
        return $this->render('boisson/show.html.twig', [
            'boisson' => $boisson,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_boisson_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Boisson $boisson, BoissonRepository $boissonRepository): Response
    {
        $form = $this->createForm(BoissonType::class, $boisson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boissonRepository->add($boisson);
            return $this->redirectToRoute('app_boisson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('boisson/edit.html.twig', [
            'boisson' => $boisson,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_boisson_delete', methods: ['POST'])]
    public function delete(Request $request, Boisson $boisson, BoissonRepository $boissonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$boisson->getId(), $request->request->get('_token'))) {
            $boissonRepository->remove($boisson);
        }

        return $this->redirectToRoute('app_boisson_index', [], Response::HTTP_SEE_OTHER);
    }
}
