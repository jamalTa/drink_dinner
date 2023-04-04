<?php

namespace App\Controller\Admin;

use App\Entity\Sauce;
use App\Form\SauceType;
use App\Repository\SauceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/sauce')]
class SauceController extends AbstractController
{
    #[Route('/', name: 'app_sauce_index', methods: ['GET'])]
    public function index(SauceRepository $sauceRepository): Response
    {
        return $this->render('sauce/index.html.twig', [
            'sauces' => $sauceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sauce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SauceRepository $sauceRepository): Response
    {
        $sauce = new Sauce();
        $form = $this->createForm(SauceType::class, $sauce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sauceRepository->add($sauce);
            return $this->redirectToRoute('app_sauce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sauce/new.html.twig', [
            'sauce' => $sauce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sauce_show', methods: ['GET'])]
    public function show(Sauce $sauce): Response
    {
        return $this->render('sauce/show.html.twig', [
            'sauce' => $sauce,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sauce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sauce $sauce, SauceRepository $sauceRepository): Response
    {
        $form = $this->createForm(SauceType::class, $sauce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sauceRepository->add($sauce);
            return $this->redirectToRoute('app_sauce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sauce/edit.html.twig', [
            'sauce' => $sauce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sauce_delete', methods: ['POST'])]
    public function delete(Request $request, Sauce $sauce, SauceRepository $sauceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sauce->getId(), $request->request->get('_token'))) {
            $sauceRepository->remove($sauce);
        }

        return $this->redirectToRoute('app_sauce_index', [], Response::HTTP_SEE_OTHER);
    }
}
