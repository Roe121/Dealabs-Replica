<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\CommentVote;
use App\Entity\Deal;
use App\Entity\Vote;
use App\Form\DealType;
use App\Repository\DealRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DealController extends AbstractController
{

    private UserService $userService;
    private EntityManagerInterface $em;

    public function __construct(UserService $userService, EntityManagerInterface $em)
    {
        $this->userService = $userService;
        $this->em = $em;
    }

    #[Route('/deal_list', name: 'deal_list', methods: ['GET'])]
    public function list(Request $request): Response
    {

        $hotestDeals = $this->em->getRepository(Deal::class)->findHotestDeals(3);

        $categoryId = $request->query->get('category');
        $searchTerm = $request->query->get('q', '');

        $criteria = [];

        if ($categoryId) {
            $category = $this->em->getRepository(Category::class)->find($categoryId);
            $criteria['category'] = $category;
        }


        if (!empty($searchTerm)) {
            $criteria['name'] = '%' . $searchTerm . '%';
        }

        $userVotes = $this->em->getRepository(Vote::class)->findBy(['user' => $this->getUser()]);

        $votesMap = [];
        foreach ($userVotes as $vote) {
            $votesMap[$vote->getDeal()->getId()] = $vote;
        }

        $deals = $this->em->getRepository(Deal::class)->findByCriteria($criteria);


        return $this->render('deal/index.html.twig', [
            'deals' => $deals,
            'searchTerm' => $searchTerm,
            'hotestDeals' => $hotestDeals,
            'user_votes' => $votesMap,
        ]);
    }

    #[Route('/deal/{id}', name: 'deal_show')]
    public function show(int $id, DealRepository $dealRepository,EntityManagerInterface $em): Response
    {
        $deal = $dealRepository->find($id);

        $category = $deal->getCategory();
        $relatedDeals = $dealRepository->findRelatedDealsBycategory($category, $deal->getId());
        $user = $this->getUser();
        $user_vote = $this->userService->getUserVoteForDeal($user, $deal);

        $userCommentVotes = $em->getRepository(CommentVote::class)->findBy(['user' => $this->getUser()]);

        $votesMap = [];
        foreach ($userCommentVotes as $vote) {
            $votesMap[$vote->getComment()->getId()] = $vote;
        }

        return $this->render('deal/show.html.twig', [
            'deal' => $deal,
            'relatedDeals' => $relatedDeals,
            'user_vote' => $user_vote,
            'user_comment_votes' => $votesMap,
        ]);
    }


    #[Route('/deal_new', name: 'deal_new', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function new(Request $request, EntityManagerInterface $em, Security $security): Response
    {

        $deal = new Deal();
        $form = $this->createForm(DealType::class, $deal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deal->setUser($security->getUser());
            $em->persist($deal);
            $em->flush();

            $this->addFlash('success', 'Deal Publié avec succès');

            return $this->redirectToRoute('deal_list');
        }
        // dd($form->getErrors(true, false));
        return $this->render('deal/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
