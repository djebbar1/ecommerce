<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Form\SearchFormType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/products', name: 'products_')]
class SearchController extends AbstractController
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
        $product = $this->entityManager->getRepository(Products::class)->find($productId);

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
    #[Route('/products/search', name: 'search')]
    public function search(Request $request, EntityManagerInterface $entityManager, ProductsRepository $productsRepository): Response
    {
        $product = new Products;
      //$productsRepository = new ProductsRepository;
        $form = $this->createForm(SearchType::class, $product);
        $form->handleRequest($request);
        $searchTerm = $request->query->get('searchTermFromNavbar');
    
        // Utilisez le repository pour effectuer la recherche
        
        // $product = $entityManager->getRepository(Products::class)->findAll();
        // $searchTerm = $request->query->get('searchTermFromNavbar');
    
        // Utilisez le repository pour effectuer la recherche en utilisant la méthode findBySearchTerm
        $product = $productsRepository->findBySearchTerm($searchTerm);
        return $this->render('products/search.html.twig', [
            'searchTermFromNavbar' => $searchTerm,
            'products' => $product,
            
        ]);
    }
    // public function search(Request $request, EntityManagerInterface $entityManager, ProductsRepository $productsRepository)
    // {
    //     $product = new Products;
    //     //$productsRepository = new ProductsRepository;
    //     $form = $this->createForm(SearchType::class, $product);
    //     $form->handleRequest($request);
       
        
    //     //$products = $this->entityManager->getRepository(Products::class)->find($name);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $searchTerm = $form->get('searchTerm')->getData();
    //         //$searchTerm = $form->get('searchTerm')->getData();
    //         if($searchTerm!=""){
    //             $product = $this->$entityManager->getRepository(Products::class)->findBySearchTerm(['searchTerm' => $searchTerm]);
    //         }else{
    //             $product = $this->$entityManager->getRepository(Products::class)->findAll();
    //             }
               
    //         } dd($product);
         
    //     return $this->render('products/search.html.twig', [
    //         'searchForm' => $form->createView(),  // Assurez-vous que le formulaire est correctement passé ici
    //         'products' => $product,
    //     ]);
    //         } 
    
}
