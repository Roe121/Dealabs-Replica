<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Deal;
use App\Entity\Vote;
use App\Form\DealType;
use App\Repository\DealRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DealController extends AbstractController
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/deal_list', name: 'deal_list', methods: ['GET'])]
    public function list(Request $request, EntityManagerInterface $em): Response
    {

        $hotestDeals = $em->getRepository(Deal::class)->findHotestDeals(3);

        $categoryId = $request->query->get('category');
        $searchTerm = $request->query->get('q', '');

        $criteria = [];

        if ($categoryId) {
            $category = $em->getRepository(Category::class)->find($categoryId);
            $criteria['category'] = $category;
        }


        if (!empty($searchTerm)) {
            $criteria['name'] = '%' . $searchTerm . '%';
        }

        $userVotes = $em->getRepository(Vote::class)->findBy(['user' => $this->getUser()]);

        $votesMap = [];
        foreach ($userVotes as $vote) {
            $votesMap[$vote->getDeal()->getId()] = $vote;
        }

        $deals = $em->getRepository(Deal::class)->findByCriteria($criteria);


        return $this->render('deal/index.html.twig', [
            'deals' => $deals,
            'searchTerm' => $searchTerm,
            'hotestDeals' => $hotestDeals,
            'user_votes' => $votesMap,
        ]);
    }

    #[Route('/deal/{id}', name: 'deal_show')]
    public function show(int $id, DealRepository $dealRepository): Response
    {
        $deal = $dealRepository->find($id);

        $category = $deal->getCategory();
        $relatedDeals = $dealRepository->findRelatedDealsBycategory($category, $deal->getId());
        $user = $this->getUser();
        $user_vote = $this->userService->getUserVoteForDeal($user, $deal);

        return $this->render('deal/show.html.twig', [
            'deal' => $deal,
            'relatedDeals' => $relatedDeals,
            'user_vote' => $user_vote,
        ]);
    }


    #[Route('/deal_new', name: 'deal_new')]
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

        return $this->render('deal/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
