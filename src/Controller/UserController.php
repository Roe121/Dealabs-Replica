<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{

    // #[Route('/User_list', name: 'User_list', methods: ['GET'])]
    // public function list(Request $request, UserRepository $UserRepository, CategoryRepository $categoryRepository): Response
    // {


    //     $Users = $UserRepository->findAll();

    //     return $this->render('User/index.html.twig', [
    //         'Users' => $Users,
    //     ]);
    // }

    #[Route('/user/{id}', name: 'user_show')]
    public function show(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
