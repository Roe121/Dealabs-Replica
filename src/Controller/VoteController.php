<?php

namespace App\Controller;

use App\Entity\Deal;
use App\Services\VoteService as ServicesVoteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VoteController extends AbstractController
{
    #[Route('/vote/{id}', name: 'app_vote', methods: ['POST'])]
    public function vote(Deal $deal, Request $request, ServicesVoteService $voteService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'Vous devez être connecté pour voter.'], 403);  
        }

        $type = $data['type'] ?? null;
        if (!in_array($type, [1, -1])) {
            return new JsonResponse(['error' => 'Type de vote invalide.'], 400);
        }

        $hotScore = $voteService->handleVote($user, $deal, $type);

        return new JsonResponse(['hotScore' => $hotScore]);
    }
}
