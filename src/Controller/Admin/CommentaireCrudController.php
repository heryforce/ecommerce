<?php

namespace App\Controller\Admin;

use App\Entity\Commentaire;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class CommentaireCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commentaire::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commentaire')
            ->setEntityLabelInPlural('Commentaire')
            ->setSearchFields(['id', 'contenu']);
    }

    public function configureFields(string $pageName): iterable
    {
        $contenu = TextareaField::new('contenu');
        $createdAt = DateTimeField::new('createdAt');
        $auteur = AssociationField::new('auteur');
        $produit = AssociationField::new('produit');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $contenu, $createdAt, $auteur, $produit];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $contenu, $createdAt, $auteur, $produit];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$contenu, $createdAt, $auteur, $produit];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$contenu, $createdAt, $auteur, $produit];
        }
    }
}
