<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Entity\Produit;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('EasyAdmin');
    }

    public function configureCrud(): Crud
    {
        return Crud::new();
    }

    public function configureMenuItems(): iterable
    {
        $submenu1 = [
            MenuItem::linkToCrud('Commentaires', 'fas fa-folder-open', Commentaire::class),
            MenuItem::linkToCrud('Catégories', 'fas fa-folder-open', Categorie::class),
            MenuItem::linkToCrud('Produits', 'fas fa-folder-open', Produit::class),
        ];

        $submenu2 = [
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-folder-open', User::class),
        ];

        yield MenuItem::subMenu('E-commerce', 'fas fa-folder-open')->setSubItems($submenu1);
        yield MenuItem::subMenu('Accès', 'fas fa-user')->setSubItems($submenu2);
    }
}
