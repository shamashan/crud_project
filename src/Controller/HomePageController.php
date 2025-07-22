<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
            'data' => $data,
        ]);
    }

    #[Route('/update/{id}', name: 'update_form')]
    public function update_form(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $crud = $entityManager->getRepository(Crud::class)->find($id);
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($crud);
            $entityManager->flush();
            $this->addFlash('notice', 'Modifier réussi !!');
            return $this->redirectToRoute("app_home_page");
        }

        return $this->render('form/updateForm.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/delete/{id}', name: 'delete_form')]
    public function delete_form(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $crud = $entityManager->getRepository(Crud::class)->find($id);
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);
        $entityManager->remove($crud);
        $entityManager->flush();

        return $this->redirectToRoute("app_home_page");
    }
}