<?php

namespace App\Controller;

use App\Entity\Merchant;
use App\Entity\Vote;
use App\Repository\MerchantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MerchantController extends AbstractController
{

    // #[Route('/Merchant_list', name: 'Merchant_list', methods: ['GET'])]
    // public function list(Request $request, MerchantRepository $MerchantRepository, CategoryRepository $categoryRepository): Response
    // {


    //     $Merchants = $MerchantRepository->findAll();

    //     return $this->render('Merchant/index.html.twig', [
    //         'Merchants' => $Merchants,
    //     ]);
    // }

    #[Route('/Merchant/{id}', name: 'merchant_show')]
    public function show(int $id, MerchantRepository $MerchantRepository, EntityManagerInterface $em): Response
    {
        $merchant = $MerchantRepository->find($id);
        $userVotes = $em->getRepository(Vote::class)->findBy(['user' => $this->getUser()]);

        $votesMap = [];
        foreach ($userVotes as $vote) {
            $votesMap[$vote->getDeal()->getId()] = $vote;
        }

        return $this->render('Merchant/show.html.twig', [
            'merchant' => $merchant,
            'user_votes' => $votesMap,
        ]);
    }
}
