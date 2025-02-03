<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HeaderController extends AbstractController{
    #[Route('/header', name: 'header_partial')]
    public function header(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('partials/_header.html.twig', [
            'categories' => $categories,
            'searchTerm' => '',
        ]);
    }
}
