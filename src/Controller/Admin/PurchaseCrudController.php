<?php

namespace App\Controller\Admin;

use App\Entity\Purchase;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PurchaseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Purchase::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Commandes :')
            ->setPageTitle('new', 'Créer une commande')
            ->setPaginatorPageSize(10)
            ->setPageTitle('detail', fn (Purchase $purchase) => (string) $purchase->getFullname())
            ->setEntityLabelInSingular('une commande');
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);
        $actions->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnIndex(),
            TextField::new('fullname', 'Nom complet'),
            DateField::new('purchasedAt', 'Passée le')
                ->setFormat('short')
                ->renderAsChoice(),
            MoneyField::new('total', 'Montant')
                ->setCurrency('EUR')
                ->setTextAlign('left')
                ->setFormTypeOption('divisor', 100),
            TextField::new('status', 'Statut'),
            AssociationField::new('user', 'Client'),
            AssociationField::new('purchaseItems', 'Détail de la commande')->hideOnIndex(),
        ];
    }
}
