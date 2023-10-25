<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findBy([], ['lastname' => 'ASC', 'firstname' => 'ASC']);

        return $this->render('contact/index.html.twig', ['contacts' => $contacts]);
    }

    #[Route('/contact/{id}')]
    public function show(ContactRepository $contactRepository, int $contactId): Response
    {
        $contact = $contactRepository->find($contactId);
        if (!$contact) {
            throw new NotFoundHttpException("Le contact renseigné n'existe pas.");
        }

        return $this->render('contact/show.html.twig', ['contact' => $contact]);
    }
}
