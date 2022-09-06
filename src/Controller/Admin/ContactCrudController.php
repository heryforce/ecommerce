<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Contact')
            ->setEntityLabelInPlural('Contact')
            ->setSearchFields(['id', 'nom', 'prenom', 'email', 'contenu']);
    }

    public function configureFields(string $pageName): iterable
    {
        $nom = TextField::new('nom');
        $prenom = TextField::new('prenom');
        $email = TextField::new('email');
        $createdAt = DateTimeField::new('createdAt');
        $contenu = TextareaField::new('contenu');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $nom, $prenom, $email, $createdAt, $contenu];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $nom, $prenom, $email, $createdAt, $contenu];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$nom, $prenom, $email, $createdAt, $contenu];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$nom, $prenom, $email, $createdAt, $contenu];
        }
    }
}
