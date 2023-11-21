<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Products;
use App\Entity\Favoris;
use App\Repository\CategoriesRepository;
use App\Repository\FavorisRepository;
use App\Repository\ProductsRepository;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
   
    #[Route('/{id}', name: 'list')]
    
    public function list(Categories $category, ProductsRepository $productsRepository, CategoriesRepository $categoriesRepository, Request $request, int $id, MainController $main): Response
    {
        // On va chercher le numéro de page dans l'url
        $page = $request->query->getInt('page', 1);
        $categories = $categoriesRepository->findAll();
        // On va chercher la liste des produits de la categorie
        $products = $productsRepository->findProduitsPaginated($page, $category->getId(), 4);
        // dd($products);
        return $this->render('categories/list.html.twig', [
            'categories' => $categories,
            'category' => $category,
            'products' => $products,
            'main' => $main,
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
