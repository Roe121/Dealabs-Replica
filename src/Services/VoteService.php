<?php

namespace App\Services;

use App\Entity\Comment;
use App\Entity\CommentVote;
use App\Entity\Vote;
use App\Entity\Deal;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class VoteService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function handleVote(User $user, Deal $deal, int $type): int
    {
        $vote = $this->em->getRepository(Vote::class)->findOneBy(['user' => $user, 'deal' => $deal]);

        if ($vote) {
            if ($vote->getType() === $type) {
                // Annuler le vote
                $this->em->remove($vote);
                $deal->setHotScore($deal->getHotScore() - 2 * $type);
            } else {
                // Inverser le vote
                $deal->setHotScore($deal->getHotScore() + 4 * $type);
                $vote->setType($type);
            }
        } else {
            // Nouveau vote
            $vote = new Vote();
            $vote->setUser($user);
            $vote->setDeal($deal);
            $vote->setType($type);
            $deal->setHotScore($deal->getHotScore() + 2 * $type);
            $this->em->persist($vote);
        }

        $this->em->persist($deal);
        $this->em->flush();

        return $deal->getHotScore();
    }

    public function handleCommentVote(User $user, Comment $comment, int $type): array
    {
        $vote = $this->em->getRepository(CommentVote::class)->findOneBy(['user' => $user, 'comment' => $comment]);

        if ($vote) {
            if ($vote->getType() === $type) {
                // Annuler le vote
                $this->em->remove($vote);
                $comment->setPositiveVotes($comment->getPositiveVotes() - ($type === 1 ? 1 : 0));
                $comment->setNegativeVotes($comment->getNegativeVotes() - ($type === -1 ? 1 : 0));
            } else {
                // Inverser le vote
                $comment->setPositiveVotes($comment->getPositiveVotes() + ($type === 1 ? 1 : 0));
                $comment->setNegativeVotes($comment->getNegativeVotes() + ($type === -1 ? 1 : 0));
                $vote->setType($type);
            }
        } else {
            // Nouveau vote
            $vote = new CommentVote();
            $vote->setUser($user);
            $vote->setComment($comment);
            $vote->setType($type);
            $comment->setPositiveVotes($comment->getPositiveVotes() + ($type === 1 ? 1 : 0));
            $comment->setNegativeVotes($comment->getNegativeVotes() + ($type === -1 ? 1 : 0));
            $this->em->persist($vote);
        }

        $this->em->persist($comment);
        $this->em->flush();

        return [
            'positiveVotes' => $comment->getPositiveVotes(),
            'negativeVotes' => $comment->getNegativeVotes(),
        ];
    }
}
