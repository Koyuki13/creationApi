<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Genre;
use App\Entity\Nationalite;
use App\Entity\Auteur;
use App\Entity\Editeur;
use App\Entity\Livre;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        //return parent::index();
        return $this->redirect($routeBuilder->setController(LivreCrudController::class)->generateUrl());

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Creation Api Rest');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Menu');
        //yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Auteur', 'fa fa-home', Auteur::class);
        yield MenuItem::linkToCrud('Editeur', 'fa fa-home', Editeur::class);
        yield MenuItem::linkToCrud('Genre', 'fa fa-home', Genre::class);
        yield MenuItem::linkToCrud('Livre', 'fa fa-home', Livre::class);
        yield MenuItem::linkToCrud('Nationalite', 'fa fa-home', Nationalite::class);
    }
}
