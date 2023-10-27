<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            ['categoryOrder' => 'asc']
        ]);
    }
    //class ProductController extends AbstractController
//{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/get_categories_images/{categorieId}', name: 'get_categories_images')]
    public function getCategorieImages(int $CategorieId): Response
    {
        $categorie = $this->entityManager->getRepository(Categories::class)->find($CategorieId);

        if (!$categorie) {
            throw $this->createNotFoundException('Le categorie n\'existe pas.');
        }

        $categorieImages = $categorie->getImages();

        $imageUrls = [];
        foreach ($categorieImages as $image) {
            $imageUrls[] = $this->generateUrl('image_route_name', ['imageId' => $image->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return new Response(json_encode($imageUrls));
    }
//}
}
