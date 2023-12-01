<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $contactRepository): Response
    {
        $qb = $contactRepository->createQueryBuilder('cate');
        $qb->select('cate as category')
            ->addSelect('contCate')
            ->leftJoin('cate.contacts', 'contCate')
            ->orderBy('cate.name', 'ASC')
            ->groupBy('cate')
            ->addSelect('COUNT(contCate) as count');

        $query = $qb->getQuery();

        $listCateCount = $query->execute();

        return $this->render('category/index.html.twig', ['listCateCount' => $listCateCount]);
    }

    #[Route('/category/{id}', name: 'contact_categorie', requirements: ['id' => '\d+'])]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', ['category' => $category, 'contacts' => $category->getContacts()]);
    }
}
