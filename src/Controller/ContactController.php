<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(ContactRepository $contactRepository, Request $request): Response
    {
        $searchText = $request->query->get('search', '');
        $contacts = $contactRepository->search($searchText);

        return $this->render('contact/index.html.twig', ['contacts' => $contacts]);
    }

    #[Route('/contact/{id}', name: 'detail_contact', requirements: ['id' => '\d+'])]
    public function show(
        #[MapEntity(expr: 'repository.findWithCategory(id)')]
        Contact $contact
    ): Response {
        return $this->render('contact/show.html.twig', ['contact' => $contact]);
    }

    #[Route('/contact/{id}/update', name: 'update_contact', requirements: ['id' => '\d+'])]
    public function update(Contact $contact, Request $request): Response
    {
        $requete = $request->query->get('requete');

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($requete);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            return $this->redirectToRoute('detail_contact');
        }

        return $this->render('contact/update.html.twig', ['contact' => $contact, 'form' => $form->createView()]);
    }

    #[Route('/contact/create', name: 'create_contact')]
    public function create(): Response
    {
        return $this->render('contact/create.html.twig');
    }

    #[Route('/contact/{id}/delete', name: 'delete_contact', requirements: ['id' => '\d+'])]
    public function delete(Contact $contact): Response
    {
        return $this->render('contact/delete.html.twig', ['contact' => $contact]);
    }
}
