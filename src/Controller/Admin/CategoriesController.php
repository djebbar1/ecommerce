<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesFormType;
use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;



#[Route('/admin/categories', name: 'admin_categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findAll();
        return $this->render('admin/categories/index.html.twig', compact('categories'));
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $category = new Categories();
        $categoryForm = $this->createForm(CategoriesFormType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $slug = $slugger->slug($category->getName());
            $category->setSlug($slug);
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie ajoutée avec succès');
            return $this->redirectToRoute('admin_categories_index');
        }
        $categoryForm = $this->createForm(CategoriesFormType::class, $category);
        $categoryFormView = $categoryForm->createView();
        return $this->render('admin/categories/add.html.twig', [
            'categoryForm' => $categoryFormView,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Categories $category, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $categoryForm = $this->createForm(CategoriesFormType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            // $slug = $slugger->slug($category->getName());
            // $category->setSlug($slug);
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie modifiée avec succès');
            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('admin/categories/edit.html.twig', [
            'categoryForm' => $categoryForm->createView(),
            'category' => $category
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    
    public function delete(Categories $category, EntityManagerInterface $em, Request $request): Response
    {
        if ($request->isMethod('POST') && $request->request->get('confirmation') === 'yes') {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $em->remove($category);
            $em->flush(); 
            $this->addFlash('success', 'Catégorie supprimée avec succès');
            return $this->redirectToRoute('admin_categories_index');
        }
    return $this->render('admin/categories/delete.html.twig', [
        'category' => $category
        ]);
    }
    
    
    #[Route('/category/{categoryId}/products', name: 'products_by_category')]
public function productsByCategory(int $categoryId, ProductsRepository $productsRepository): Response
{
    $category = $this->getDoctrine()->getRepository(Categories::class)->find($categoryId);
    
    if (!$category) {
        throw $this->createNotFoundException('La catégorie n\'existe pas.');
    }

    $products = $productsRepository->findByCategory($category);

    return $this->render('products/products_by_category.html.twig', [
        'category' => $category,
        'products' => $products,
    ]);
}

}


