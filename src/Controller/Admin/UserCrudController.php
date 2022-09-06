<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('User')
            ->setSearchFields(['id', 'username', 'roles', 'email']);
    }

    public function configureFields(string $pageName): iterable
    {
        $username = TextField::new('username');
        $roles = CollectionField::new('roles');
        $email = TextField::new('email');
        $id = IntegerField::new('id', 'ID');
        $password = TextField::new('password');
        $commentaires = AssociationField::new('commentaires');
        $produits = AssociationField::new('produits');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $username, $roles, $email];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $username, $roles, $password, $email, $commentaires, $produits];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$username, $roles, $email];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$username, $roles, $email];
        }
    }
}
