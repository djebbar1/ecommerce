<?php

namespace App\Controller;


use App\Entity\Products;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavorisController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    #[Route('/favoris', name: 'app_favoris')]
    public function favoris(ProductsRepository $product, ManagerRegistry $doctrine)
    {
        $user = $this->getUser();
        $userId = $user->getId();
       
        $queryBuilder = $doctrine->getManager()->createQueryBuilder()
        ->select('p')
        ->from(Products::class, 'p')
        ->join('p.favorite', 'f')
        ->where('f.id = :userId')
        ->setParameter('userId', $userId);
            //dd($queryBuilder);
        $favorites = $queryBuilder->getQuery()->getResult();

        // $favorites = $doctrine->getRepository(Music::class)->findBy(['favoris'=>$user]);
        $product = $this->entityManager->getRepository(products::class)->findAll();
        return $this->render('favoris/index.html.twig', [
            'favoris' => $favorites,
            'product' => $product
        ]);
       
    }
}
