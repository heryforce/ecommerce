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
            MenuItem::linkToCrud('Commentaires', 'fas fa-comments', Commentaire::class),
            MenuItem::linkToCrud('Catégories', 'fas fa-tags', Categorie::class),
            MenuItem::linkToCrud('Produits', 'fas fa-candy-cane', Produit::class),
        ];

        $submenu2 = [
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class),
        ];

        yield MenuItem::subMenu('E-commerce', 'fas fa-euro-sign')->setSubItems($submenu1);
        yield MenuItem::subMenu('Accès', 'fas fa-user')->setSubItems($submenu2);
    }
}
