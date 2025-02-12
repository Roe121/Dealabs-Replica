<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Deal;
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\DealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DealController extends AbstractController
{

    #[Route('/deal_list', name: 'deal_list', methods: ['GET'])]
    public function list(Request $request, DealRepository $dealRepository, CategoryRepository $categoryRepository): Response
    {

        $hotestDeals = $dealRepository->findHotestDeals(3);

        $filter = $request->query->get('filter', 'all');
        $categoryId = $request->query->get('category');
        $searchTerm = $request->query->get('q', '');

        $criteria = [];

        if ($categoryId) {
            $category = $categoryRepository->find($categoryId);
            $criteria['category'] = $category;
        }


        if (!empty($searchTerm)) {
            $criteria['name'] = '%' . $searchTerm . '%';
        }

        if ($filter === 'enabled') {
            $criteria['enable'] = true;
        } elseif ($filter === 'disabled') {
            $criteria['enable'] = false;
        }

        $deals = $dealRepository->findByCriteria($criteria);

        return $this->render('deal/index.html.twig', [
            'deals' => $deals,
            'filter' => $filter,
            'searchTerm' => $searchTerm,
            'hotestDeals' => $hotestDeals,
        ]);
    }

    #[Route('/deal/{id}', name: 'deal_show')]
    public function show(int $id, DealRepository $dealRepository): Response
    {
        $deal = $dealRepository->find($id);

        $categories = $deal->getCategories();
        $relatedDeals = $dealRepository->findRelatedDealsByCategories($categories, $deal->getId());

        return $this->render('deal/show.html.twig', [
            'deal' => $deal,
            'relatedDeals' => $relatedDeals,
        ]);
    }
}
