<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(ProductRepository $productRepository): Response
    {
        $Products = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'List' => $Products,
        ]);
    }

    #[Route('/addProduct', name: 'add_product')]
    public function newProduct(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $product = new Product();
        $formProd = $this->createForm(ProductType::class, $product);
        $formProd->handleRequest($request);
        if ($formProd->isSubmitted() && $formProd->isValid()) {
            $entityManagerInterface->persist($product);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_product');
        }
        return $this->render('product/add.html.twig', [
            'formProd' => $formProd,
        ]);
    }
}
