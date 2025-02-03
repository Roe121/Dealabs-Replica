<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Controller\Admin\DealCrudController;
use App\Entity\Category;
use App\Entity\Deal;

class DashboardController extends AbstractDashboardController
{

    #[Route('/admin', name: 'admin')] // Vérifie bien cette annotation
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(DealCrudController::class)->generateUrl());

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mon Admin');
    }

    public function configureMenuItems(): iterable
    {
    yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

    yield MenuItem::linkToCrud('Deals', 'fa fa-tags', Deal::class);

    yield MenuItem::linkToCrud('Categories', 'fa fa-folder', Category::class);
    }
}
