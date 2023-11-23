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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/favoris', name: 'favoris_')]
class FavorisController extends AbstractController
{
    #[Route('/{id}/{idCategories}', name: 'list')]
public function list(ProductsRepository $productsRepository,Products $products, EntityManagerInterface $entityManager, Request $request, int $id, int $idCategories): Response
{
    // Utilisez find au lieu de findAll pour récupérer un produit spécifique
    $user = $this->getUser();
    $products->addFavori($user);

    $entityManager->persist($user);
    $entityManager->flush();

    return $this->redirectToRoute('categories_list', ['id' => $idCategories]);
}


#[Route('/recup', name: 'favoris_recup')]
public function recup(ProductsRepository $productsRepository, EntityManagerInterface $em, Request $request,ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();


        $queryBuilder = $doctrine->getManager()->createQueryBuilder()
            ->select('p')
            ->from(Products::class, 'p')
            ->join('p.favoris', 'f')
            ->where('f.id = :userId')
            ->setParameter('userId', $userId);

        $favorites = $queryBuilder->getQuery()->getResult();
// dd($favorites);
        
        return $this->render('favoris/recupFavoris.html.twig', ['favoris' => $favorites]);
    }
    #[Route('/remove/favorite/{id}/{idCategories}', name: 'remove_favorite')]
    public function removeFavorite(Request $request, Products $products, EntityManagerInterface $entityManager, $idCategories): Response
    {

        $user = $this->getUser();
        $products->removeFavori($user);
    
        $entityManager->persist($user);
        $entityManager->flush();
    
        return $this->redirectToRoute('categories_list', ['id' => $idCategories]);
    }

}