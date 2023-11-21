<?php
namespace App\Controller;

use App\Entity\Products;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Favoris;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductsRepository;
use App\Repository\FavorisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/favoris', name: 'favoris_')]
class FavorisController extends AbstractController
{
    #[Route('/{id}/{idCategories}', name: 'list')]
public function list(ProductsRepository $productsRepository, FavorisRepository $favorisRepository, EntityManagerInterface $em, Request $request, int $id, int $idCategories): Response
{
    // Utilisez find au lieu de findAll pour récupérer un produit spécifique
    $product = $productsRepository->find($id);

    $favori = new Favoris();
    $user = $this->getUser();
    $favori->addProduct($product);
    $favori->addUser($user);
    $em->persist($favori);

    $em->flush();

    // Vérifiez si le produit a été trouvé
    // if (!$product) {
    //     throw $this->createNotFoundException('Produit non trouvé');
    // }

    // On va chercher le numéro de page dans l'url
    // $page = $request->query->getInt('page', 1);

    // // On va chercher la liste des produits de la categorie
    // $favoris = $favorisRepository->findProduitsPaginated($page, $product->getId(), 4);
    //dd($favoris);

    // return $this->render('favoris/list.html.twig', [
    //     'products' => $product,
    //     'favoris' => $favoris,
    // ]);
    return $this->redirectToRoute('categories_list', ['id' => $idCategories]);
}


#[Route('/recup', name: 'favoris_recup')]
public function recup(ProductsRepository $productsRepository, FavorisRepository $favorisRepository, EntityManagerInterface $em, Request $request,ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();
       
        // Assurez-vous que l'utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à vos favoris.');
        }

 
        $queryBuilder = $doctrine->getManager()->createQueryBuilder()
        ->select('p')
        ->from(Products::class, 'p')
        ->join('p.favoris', 'f')
        ->where('f.id = :userId')
        ->setParameter('userId', $userId);
            //dd($queryBuilder);
        $favorites = $queryBuilder->getQuery()->getResult();
        //dd($user);
        
        return $this->render('favoris/recupFavoris.html.twig', ['favoris' => $favorites]);
    }
    // #[Route('/add/favorite/{id}', name: 'add_favorite')]
    // public function addFavorite(Request $request, Products $products, EntityManagerInterface $entityManager): Response
    // {

    //     $user = $this->getUser();
    //     // dd($products);
    //     $favoris = new Favoris;
    //     $favoris->addProduct($products);
    //     $favoris->addUser($user);
    //     // dd($favoris);
    //     // $products->addFavori($user);

    //     $entityManager->persist($user);
    //     $entityManager->persist($favoris);
    //     $entityManager->flush();

    //     return $this->redirectToRoute('main');
    // }

}