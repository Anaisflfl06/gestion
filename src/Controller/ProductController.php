<?php

// src/Controller/ProductController.php
namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    #[Route('/product/test', name: 'product_test')]
    public function test(): Response
    {
        // Create ingredients and recipe
        $ingredients = [
            ['name' => 'Sugar', 'quantity' => 100],
            ['name' => 'Flour', 'quantity' => 200],
        ];

        $recipe = $this->productService->createRecipe('Cake', 100, $ingredients);

        // Create a product for the recipe
        $product = $this->productService->createProduct('Cake Product', 9.99, 'cake.jpg', 'recipe', $recipe->getId());

        return new Response('Test entities created successfully!');
    }

    #[Route('/product/show/{id}', name: 'product_show')]
    public function show(int $id): Response
    {
        // Fetch the product by its ID
        $product = $this->productService->getProductById($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        // Fetch the product associations
        $associations = $this->productService->getProductAssociations($product);

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'associations' => $associations,
        ]);

    }
    #[Route('/product/buy/{id}', name: 'product_buy')]
    public function buy(int $id): Response
    {
        $product = $this->productService->getProductById($id);



        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $result = $this->productService->buyProduct($product);

        return new Response($result);
    }
}
