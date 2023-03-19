<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $CatRepo): Response
    {
        $category = $CatRepo->findAll();
        return $this->render('category/index.html.twig', [
            'List' => $category,
        ]);
    }

    #[Route('/addCategory', name: 'add_category')]
    public function addCategory(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $cat = new Category();
        $formCat = $this->createForm(CategoryType::class, $cat);
        $formCat->handleRequest($request); 
        if ($formCat->isSubmitted() && $formCat->isValid()) {
            $entityManagerInterface->persist($cat);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/add.html.twig', [
            'FormCat' => $formCat,
        ]);
    }
}
