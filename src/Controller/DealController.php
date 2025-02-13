<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Deal;
use App\Enum\DealStatusEnum;
use App\Form\CommentType;
use App\Form\DealType;
use App\Repository\CategoryRepository;
use App\Repository\DealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DealController extends AbstractController
{

    #[Route('/deal_list', name: 'deal_list', methods: ['GET'])]
    public function list(Request $request, DealRepository $dealRepository, CategoryRepository $categoryRepository): Response
    {

        $hotestDeals = $dealRepository->findHotestDeals(3);

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

        $deals = $dealRepository->findByCriteria($criteria);

        return $this->render('deal/index.html.twig', [
            'deals' => $deals,
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


    #[Route('/deal_new', name: 'deal_new')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function new(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        
        $deal = new Deal();
        $form = $this->createForm(DealType::class, $deal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deal->setStatus(DealStatusEnum::ACTIVE); 
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
