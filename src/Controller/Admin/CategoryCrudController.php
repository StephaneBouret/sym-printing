<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Catégories')
            ->setPageTitle('new', 'Ajouter une catégorie')
            ->setDefaultSort(['id' => 'ASC'])
            ->setPaginatorPageSize(10)
            ->setPageTitle('edit', fn (Category $category) => (string) 'Modifier la catégorie : ' . $category->getName());;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('name', 'Nom de la catégorie')
            ->setFormTypeOptions(['attr' => ['placeholder' => 'Ex: Flyer']]),
            TextField::new('slug', 'Slug')->onlyOnIndex(),
        ];
    }
}
