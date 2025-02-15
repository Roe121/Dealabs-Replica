<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Vote;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UserController extends AbstractController
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // #[Route('/User_list', name: 'User_list', methods: ['GET'])]
    // public function list(Request $request, UserRepository $UserRepository, CategoryRepository $categoryRepository): Response
    // {


    //     $Users = $UserRepository->findAll();

    //     return $this->render('User/index.html.twig', [
    //         'Users' => $Users,
    //     ]);
    // }

    #[Route('/user/{id}', name: 'user_show')]
    public function show(int $id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->find($id);
        $userVotes = $em->getRepository(Vote::class)->findBy(['user' => $this->getUser()]);

        $votesMap = [];
        foreach ($userVotes as $vote) {
            $votesMap[$vote->getDeal()->getId()] = $vote;
        }


        return $this->render('user/show.html.twig', [
            'user' => $user,
            'user_votes' => $votesMap,
        ]);
    }

    #[Route('/user_new', name: 'user_new')]
    public function new(Request $request, EntityManagerInterface $em, Security $security, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre compte a été créé avec succès !');
            return $this->redirectToRoute('deal_list'); 
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
