<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findBy([], ['lastname' => 'ASC', 'firstname' => 'ASC']);

        return $this->render('contact/index.html.twig', ['contacts' => $contacts]);
    }

    #[Route('/contact/{contactId}')]
    public function show(ContactRepository $contactRepository, int $contactId) : Response
    {
        if (!$contactId){
            throw new NotFoundHttpException("Le contact n'existe pas");
        }

        $contact = $contactRepository->find($contactId);

        return $this->render('contact/show.html.twig', ['contact' => $contact]);
    }
}
