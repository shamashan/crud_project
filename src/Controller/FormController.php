<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FormController extends AbstractController
{

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
}
