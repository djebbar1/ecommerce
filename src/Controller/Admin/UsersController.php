<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Form\UsersFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/users', name: 'admin_users_')]
class UsersController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UsersRepository $usersRepository): Response
    {
        $users = $usersRepository->findBy([], ['firstname' => 'asc']);
        return $this->render('admin\users\index.html.twig', compact('users'));
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = new Users();
        $userForm = $this->createForm(UsersFormType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $email = $user->getEmail();
            // $slug = $slugger->slug($email);
            // $user->setSlug($slug);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur ajouté avec succès');
            return $this->redirectToRoute('admin_users_index');
        }
        //$userForm = $this->createForm(UsersFormType::class, $user);
        //$userFormView = $userForm->createView();
        return $this->render('admin/users/add.html.twig', [
            //'userForm' => $userFormView,
            'userForm' => $userForm->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Users $user, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('USER_EDIT', $user);
        $userForm = $this->createForm(UsersFormType::class, $user);
        $userForm->handleRequest($request);

           if ($userForm->isSubmitted() && $userForm->isValid()) {
            $email = $user->getEmail();
            // $slug = $slugger->slug($email);
            // $user->setSlug($slug);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/users/edit.html.twig', [
            'userForm' => $userForm->createView(),
            'user' => $user
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    //#[Security('is_granted("ROLE_ADMIN")')]
    public function delete(Request $request, Users $user, EntityManagerInterface $em, $id): Response
    {
        print_r($id);
        die();
        $user = $this->getUser();

        if (!$user && $this->isCsrfTokenValid('delete/users/{id}'.$user->getId(), $request->request->get('_token'))) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }
        if ($request->isMethod('POST') && $request->request->get('confirmation') === 'yes') {
            $this->denyAccessUnlessGranted('USER_DELETE', $user);
            $em->remove($user);
            $em->flush();
            $session = new Session();
            $session->invalidate();
            $this->addFlash('success', 'User supprimée avec succès');
        //return $this->redirectToRoute('profile_confirm_delete');
    }
         
    return $this->render('/admin/users/delete.html.twig', [
        'user' => $user,
    ]);

       
    }
    
}