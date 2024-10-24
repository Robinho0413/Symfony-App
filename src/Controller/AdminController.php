<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/utilisateurs', name: 'app_admin_users')]
    public function userList(UserRepository $userRepository): Response
    {
        // Récupérer la liste des utilisateurs
        $users = $userRepository->findAll();

        // Rendre le template en passant les utilisateurs
        return $this->render('admin/user_list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/utilisateurs/saisie/{id?}', name: 'app_admin_user_form')]
    public function userForm(
        Request $request, 
        EntityManagerInterface $entityManager, 
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository, 
        ?string $id = null
    ): Response {
        // Vérifie si l'id est un entier. Si ce n'est pas le cas, redirige vers la création d'un utilisateur.
        if ($id !== null && !ctype_digit($id)) {
            $this->addFlash('error', 'ID invalide, redirection vers la création d\'un nouvel utilisateur.');
            return $this->redirectToRoute('app_admin_user_form');
        }

        // Récupère l'utilisateur si l'id est présent, sinon crée un nouvel utilisateur
        $user = $id ? $userRepository->find((int) $id) : new User();

        // Si l'utilisateur avec cet id n'existe pas et l'id est non nul, redirige vers la création d'un nouvel utilisateur
        if ($id && !$user) {
            $this->addFlash('error', 'Utilisateur non trouvé, création d\'un nouvel utilisateur.');
            return $this->redirectToRoute('app_admin_user_form');
        }

        // Création du formulaire
        $form = $this->createForm(UserFormType::class, $user, ['is_edit' => $id !== null]);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide, enregistre les données
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du mot de passe
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                // Hash le mot de passe et l'assigne à l'utilisateur
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            } elseif (!$id) {
                // Si c'est une création, le mot de passe est obligatoire
                $this->addFlash('error', 'Le mot de passe est requis pour la création d\'un utilisateur.');
                return $this->redirectToRoute('app_admin_user_form');
            }

            // Persist si c'est une création
            if (!$id) {
                $entityManager->persist($user);
            }

            // Enregistre les modifications en base de données
            $entityManager->flush();

            // Redirige vers une page après la création/modification
            return $this->redirectToRoute('app_admin_users'); // Exemple : liste des utilisateurs
        }

        // Rendre le template en passant le formulaire et la variable isEdit
        return $this->render('admin/user_form.html.twig', [
            'form' => $form->createView(),
            'isEdit' => $id !== null,
        ]);
    }

    
}
