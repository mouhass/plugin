<?php

namespace BatchJobs\BatchJobsBundle\Controller;

use BatchJobs\BatchJobsBundle\Entity\Historique;
use BatchJobs\BatchJobsBundle\Form\HistoriqueType;
use BatchJobs\BatchJobsBundle\Repository\HistoriqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HistoriqueController extends AbstractController
{

    public function index(HistoriqueRepository $historiqueRepository): Response
    {
        return $this->render('@batchJobs/historique/index.html.twig', [
            'historiques' => $historiqueRepository->findAll(),
        ]);
    }


    public function new(Request $request, HistoriqueRepository $historiqueRepository): Response
    {
        $historique = new Historique();
        $form = $this->createForm(HistoriqueType::class, $historique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $historiqueRepository->add($historique);
            return $this->redirectToRoute('app_historique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@batchJobs/historique/new.html.twig', [
            'historique' => $historique,
            'form' => $form->createView(),
        ]);
    }


    public function show(Historique $historique): Response
    {
        return $this->render('@batchJobs/historique/show.html.twig', [
            'historique' => $historique,
        ]);
    }


    public function edit(Request $request, Historique $historique, HistoriqueRepository $historiqueRepository): Response
    {
        $form = $this->createForm(HistoriqueType::class, $historique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $historiqueRepository->add($historique);
            return $this->redirectToRoute('app_historique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@batchJobs/historique/edit.html.twig', [
            'historique' => $historique,
            'form' => $form->createView(),
        ]);
    }


    public function delete( Historique $historique, HistoriqueRepository $historiqueRepository): Response
    {
       // if ($this->isCsrfTokenValid('delete'.$historique->getId(), $request->request->get('_token'))) {
            $historiqueRepository->remove($historique);
      //  }

        return $this->redirectToRoute('app_historique_index', [], Response::HTTP_SEE_OTHER);
    }
}
