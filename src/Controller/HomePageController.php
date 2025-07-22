<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudType;
use App\Repository\CrudRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function HomePage(EntityManagerInterface $entityManager): Response
    // public function HomePage(CrudRepository $CrudRepo): Response
    {
        // $data = $CrudRepo->finalAll();
        $data = $entityManager->getRepository(Crud::class)->findAll();
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
            'data' => $data,
        ]);
    }

    #[Route('/create', name: 'app_createForm')]
    public function create_form(Request $request, EntityManagerInterface $entityManager): Response
    {
        $crud = new Crud();
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $sendDatabase = $this->getDoctrine()->getManager();
            $entityManager->persist($crud);
            $entityManager->flush();

            $this->addFlash('notice', 'soumission réussi !!');
            return $this->redirectToRoute("app_home_page");
        }

        return $this->render('form/createForm.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/update/{id}', name: 'form_edit')]
    public function update_form(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $crud = $this->$entityManager()->getRepository(Crud::class)->find($id);
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($crud);
            $entityManager->flush();

            $this->addFlash('notice', 'Modifier réussi !!');
            return $this->redirectToRoute("app_home_page");
        }

        return $this->render('form/createForm.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/delete/{id}', name: 'form_delete')]
    public function delete_form(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $crud = $this->$entityManager()->getRepository(Crud::class)->find($id);
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($crud);
            $entityManager->flush();

            $this->addFlash('notice', 'Supprimer réussi !!');
            return $this->redirectToRoute("app_home_page");
        }

        return $this->render('form/createForm.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
