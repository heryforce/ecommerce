<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategorieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Categorie::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Categorie')
            ->setEntityLabelInPlural('Categorie')
            ->setSearchFields(['id', 'titre']);
    }

    public function configureFields(string $pageName): iterable
    {
        $titre = TextField::new('titre');
        $id = IntegerField::new('id', 'ID');
        $produits = AssociationField::new('produits');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $titre];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $titre, $produits];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$titre];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$titre];
        }
    }
}
