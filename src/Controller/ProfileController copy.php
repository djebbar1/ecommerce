<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\CategoriesRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
            'categories' => $categoriesRepository->findAll(),
            ['categoryOrder' => 'asc']
        ]);
    }
    #[Route('/orders', name: 'orders')]
    public function orders(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Commandes de l\'utilisateur',
        ]);
    }
    #[Route('/delete/user/{id}', name: 'delete')]
    #[IsGranted("ROLE_USER")]
    public function deleteProfile(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
     
        $user = $entityManager->getRepository(Users::class)->find($id);
        //$user = $this->getUser();
       
        if (!$user && $this->isCsrfTokenValid('delete/user/{id}'.$id, $request->request->get('_token'))) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }

        // Créez un formulaire de confirmation de suppression (un simple formulaire pour éviter les suppressions accidentelles).
        if ($request->isMethod('POST') && $request->request->get('confirmation') === 'yes') {
            $entityManager->remove($user);
            $entityManager->flush();

            $session = new Session();
            $session->invalidate();

        return $this->redirectToRoute('main');
        //return $this->redirectToRoute('profile_confirm_delete');
    }

    return $this->render('profile/delete_profile.html.twig', [
        'id' => $id,
        'lastname' => $user->getLastname(),
    ]);
    }

       
  

}
