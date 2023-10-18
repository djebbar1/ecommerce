<?php

namespace App\Controller;

use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products', name: 'products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('products/index.html.twig');
    }
    #[Route('/{slug}', name: 'details')]
    public function details(Products $product): Response
    {

        return $this->render('products/details.html.twig', compact('product'));
    }
    //class ProductController extends AbstractController
//{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/get_product_images/{productId}', name: 'get_product_images')]
    public function getProductImages(int $productId): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($productId);

        if (!$product) {
            throw $this->createNotFoundException('Le produit n\'existe pas.');
        }

        $productImages = $product->getImages();

        $imageUrls = [];
        foreach ($productImages as $image) {
            $imageUrls[] = $this->generateUrl('image_route_name', ['imageId' => $image->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return new Response(json_encode($imageUrls));
    }
//}
}
