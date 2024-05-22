<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Produits :')
            ->setPageTitle('new', 'Créer un produit')
            ->setPaginatorPageSize(10)
            ->setPageTitle('edit', fn (Product $product) => (string) 'Modifier le produit : ' . $product->getName());
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            FormField::addColumn(6),
            TextField::new('name', 'Nom du produit'),
            MoneyField::new('price', 'Prix')
                ->setCurrency('EUR')
                ->setTextAlign('left')
                ->setFormTypeOption('divisor', 100),
            AssociationField::new('category', 'Catégorie du produit'),
            TextEditorField::new('shortDescription', 'Description du produit')->hideOnIndex(),
            ImageField::new('imageName', 'Image :')
                ->setBasePath('/images/products')
                ->onlyOnIndex(),
            TextField::new('imageFile', 'Fichier Image :')
                ->setFormType(VichImageType::class)
                ->setFormTypeOptions(['delete_label' => 'Supprimer l\'image'])
                ->hideOnIndex(),
        ];
    }
}
