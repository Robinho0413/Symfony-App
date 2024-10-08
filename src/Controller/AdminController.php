<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
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

    #[Route('/admin/utilisateurs/saisie/{id}', name: 'app_admin_users')]
    public function userForm(UserRepository $userRepository): Response
    {
        // Récupérer la liste des utilisateurs
        $users = $userRepository->findAll();

        // Rendre le template en passant les utilisateurs
        return $this->render('admin/user_list.html.twig', [
            'users' => $users,
        ]);
    }

    
}
