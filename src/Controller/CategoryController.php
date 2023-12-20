<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $contactRepository): Response
    {
        $listCateCount = $contactRepository->findAllAlphabeticallyWithContactCount();

        return $this->render('category/index.html.twig', ['listCateCount' => $listCateCount]);
    }

    #[Route('/category/{id}', name: 'contact_categorie', requirements: ['id' => '\d+'])]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', ['category' => $category, 'contacts' => $category->getContacts()]);
    }
}
