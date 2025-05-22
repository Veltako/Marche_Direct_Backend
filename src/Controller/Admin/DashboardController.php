<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Commande;
use App\Entity\Comment;
use App\Entity\Day;
use App\Entity\Etat;
use App\Entity\Format;
use App\Entity\Historique;
use App\Entity\Marche;
use App\Entity\Produit;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator){
        
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MarcheDirect');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Commande', 'fa fa-list', Commande::class);
        yield MenuItem::linkToCrud('Produit', 'fa fa-list', Produit::class);
        yield MenuItem::linkToCrud('Historique', 'fa fa-list', Historique::class);
        yield MenuItem::linkToCrud('User', 'fa fa-list', User::class);
        yield MenuItem::linkToCrud('Marche', 'fa fa-list', Marche::class);
        yield MenuItem::linkToCrud('Comment', 'fa fa-list', Comment::class);
        yield MenuItem::linkToCrud('Etat', 'fa fa-list', Etat::class);
        yield MenuItem::linkToCrud('Categorie', 'fa fa-list', Categorie::class);
        yield MenuItem::linkToCrud('Day', 'fa fa-list', Day::class);
        yield MenuItem::linkToCrud('Format', 'fa fa-list', Format::class);
        
        
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
