<?php

namespace App\Controller\Admin;

use App\Entity\Commentaire;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

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
        return [
            IdField::new('id')->hideOnForm(),
            TextareaField::new('contenu')->hideOnForm(),
            DateTimeField::new('createdAt', "Date d'ajout")->setFormat('d/M/Y à H:m:s')->hideOnForm(),
            AssociationField::new('auteur'),
            AssociationField::new('produit'),
            TextEditorField::new('contenu')->onlyOnForms(),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $com = new $entityFqcn;
        $com->setCreatedAt(new \DateTime);
        return $com;
    }
}
