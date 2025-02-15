<?php

namespace App\Services;

use App\Entity\Vote;
use App\Entity\Deal;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(private EntityManagerInterface $em) {}

   public function getUserVoteForDeal(?User $user, Deal $deal): ?Vote
    {
        return $this->em->getRepository(Vote::class)->findOneBy(['user' => $user, 'deal' => $deal]);
    }
}
