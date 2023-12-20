<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/contact/{id}/update', name: 'update_contact', requirements: ['id' => '\d+'])]
    public function update(Contact $contact, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('detail_contact', ['id' => $contact->getId()]);
        }

        return $this->render('contact/update.html.twig', ['contact' => $contact, 'form' => $form->createView()]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/contact/create', name: 'create_contact')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();
        }

        return $this->render('contact/create.html.twig', ['form' => $form->createView()]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/contact/{id}/delete', name: 'delete_contact', requirements: ['id' => '\d+'])]
    public function delete(Contact $contact, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, ['label' => 'delete'])
            ->add('cancel', SubmitType::class, ['label' => 'cancel'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->get('delete')->isClicked()) {
                $entityManager->remove($contact);
                $entityManager->flush();
                return $this->redirectToRoute('app_contact');
            } elseif (!$form->get('delete')->isClicked()) {
                return $this->redirectToRoute('detail_contact', ['id' => $contact->getId()]);
            }
        }

        return $this->render('contact/delete.html.twig', ['contact' => $contact, 'form' => $form->createView()]);
    }
}
