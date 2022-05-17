<?php

namespace BatchJobs\BatchJobsBundle\Controller;

use BatchJobs\BatchJobsBundle\Entity\Admin;
use BatchJobs\BatchJobsBundle\Form\AdminType;
use BatchJobs\BatchJobsBundle\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminController extends AbstractController
{

    public function index(AdminRepository $adminRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'admins' => $adminRepository->findAll(),
        ]);
    }

    public function new(Request $request, AdminRepository $adminRepository, UserPasswordEncoderInterface $encoder): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encodedPassword = $encoder->encodePassword($admin, $admin->getPassword());
            $admin->setPassword($encodedPassword);
            $admin->setRoles(['ROLE_ADMIN']);
            $adminRepository->add($admin);

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/new.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="app_admin_show", methods={"GET"})
//     */
//    public function show(Admin $admin): Response
//    {
//        return $this->render('admin/show.html.twig', [
//            'admin' => $admin,
//        ]);
//    }


    public function edit(Request $request, Admin $admin, AdminRepository $adminRepository): Response
    {
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adminRepository->add($admin);
            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/edit.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
    }


    public function delete(Request $request, Admin $admin, AdminRepository $adminRepository): Response
    {
//        if ($this->isCsrfTokenValid('delete'.$admin->getId(), $request->request->get('_token'))) {
//
//        }
        $adminRepository->remove($admin);

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }


}
