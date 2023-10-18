<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
   
    #[Route('/{id}', name: 'list')]
    public function list(Categories $category, ProductsRepository $productsRepository, Request $request, int $id): Response
    {
        // On va chercher le numéro de page dans l'url
         $page = $request->query->getInt('page', 1);
        
        // On va chercher la liste des produits de la categorie
        $products = $productsRepository->findProduitsPaginated(1, $category->getId(), 4);
        // var_dump($products);
        return $this->render('categories/list.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);

        
        //syntaxe alternative
        /*
            return $this->render('categories/list.html.twig', [
                'category' => '$category', 
                'products' => '$products'
                )];
        */ 
    }
}
