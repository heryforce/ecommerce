<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produit')
            ->setSearchFields(['id', 'nom', 'description', 'prix', 'image']);
    }

    public function configureFields(string $pageName): iterable
    {
        $nom = TextField::new('nom');
        $description = TextareaField::new('description');
        $prix = NumberField::new('prix');
        $imageFile = Field::new('imageFile');
        $categorie = AssociationField::new('categorie');
        $auteur = AssociationField::new('auteur');
        $id = IntegerField::new('id', 'ID');
        $image = ImageField::new('image');
        $commentaire = AssociationField::new('commentaire');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $nom, $description, $prix, $image, $categorie, $auteur];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $nom, $description, $prix, $image, $categorie, $auteur, $commentaire];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$nom, $description, $prix, $imageFile, $categorie, $auteur];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$nom, $description, $prix, $imageFile, $categorie, $auteur];
        }
    }
}
