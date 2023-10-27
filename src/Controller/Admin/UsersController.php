<?php

namespace App\Controller\Admin;

use App\Controller\RegistrationController;
use App\Entity\Users;
use App\Form\UsersFormType;
use App\Repository\UsersRepository;
use App\Security\UsersAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Controller\RegisterController;

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
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, UserPasswordHasherInterface $userPasswordHasher, SendMailService $mail, JWTService $jwt, UserAuthenticatorInterface $userAuthenticator,  UsersAuthenticator $authenticator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = new Users();
        $userForm = $this->createForm(UsersFormType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $email = $user->getEmail();
            // $slug = $slugger->slug($email);
            // $user->setSlug($slug);
            // dd($userForm->get('password')->getData());
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $userForm->get('password')->getData()
                )
            );
            $em->persist($user);
            $em->flush();
            // do anything else you need here, like send an email
            
            // On génère le JWT de l'utilisateur
            // On crée le header
            $header = [
                'typ' => 'JWT',
                'alg' => 'hs256'
            ];

            // On crée le payload
            $payload = [
                'user_id' => $user->getId()
            ];

            // On génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
            
            // on envoie un mail
            $mail->send(
                'no-reply@monsite.net',
                $user->getEmail(),
                'Activation de votre compte sur le site e-commerce',
                'register',
                compact('user', 'token')
                /*[
                    'user' => $user
                ]*/
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        

        //     $this->addFlash('success', 'Utilisateur ajouté avec succès');
        //     return $this->redirectToRoute('admin_users_index');
         }
        //$userForm = $this->createForm(UsersFormType::class, $user);
        //$userFormView = $userForm->createView();
        return $this->render('admin/users/add.html.twig', [
            //'userForm' => $userFormView,
            'userForm' => $userForm->createView(),
        ]);
    }
    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UsersRepository $usersRepository, EntityManagerInterface $em): Response
    {
        // On vérifie si le token est valide, n'a pas expiré et n'a pas modifié
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))){
            // On récupère le payload
            $payload = $jwt->getPayload($token);

            // On récupère le user du token
            $user = $usersRepository->find($payload['user_id']);

            // On vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if($user && !$user->getIsVerified()){
                $user->setIsVerified(true);
                $em->flush($user);
                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('profile_index');
            }
        }

        // Ici un probème se pose dans le token
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }
    #[Route('/renvoiverif', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UsersRepository $usersRepository): Response
    {
        $user = $this->getUser();

        if(!$user){
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }
        if($user->getIsVerified()){
            $this->addFlash('warning', 'Cette utilisateur est déjà activé');
            return $this->redirectToRoute('profile_index');
        }
                    // On génère le JWT de l'utilisateur
            // On crée le header
            $header = [
                'typ' => 'JWT',
                'alg' => 'hs256'
            ];

            // On crée le payload
            $payload = [
                'user_id' => $user->getId()
            ];

            // On génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
            
            // on envoie un mail
            $mail->send(
                'no-reply@monsite.net',
                $user->getEmail(),
                'Activation de votre compte sur le site e-commerce',
                'register',
                compact('user', 'token')
                /*[
                    'user' => $user
                ]*/
            );
            $this->addFlash('success', 'Email de vérification envoyé');
            return $this->redirectToRoute('profile_index');
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Users $user, Request $request, EntityManagerInterface $em): Response
    {
        
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $userForm = $this->createForm(UsersFormType::class, $user);
        $userForm->handleRequest($request);
        
           if ($userForm->isSubmitted() && $userForm->isValid()) {
            //$email = $user->getEmail();
            // $slug = $slugger->slug($email);
            // $user->setSlug($slug);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin_users_index');
        }
        //$userForm = $this->createForm(UsersFormType::class, $user);
        //$userFormView = $userForm->createView();
        return $this->render('admin/users/edit.html.twig', [
            //'userForm' => $userFormView,
            'userForm' => $userForm->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    //#[Security('is_granted("ROLE_ADMIN")')]
    public function delete(Request $request, Users $user, EntityManagerInterface $em, $id): Response
    {
      
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