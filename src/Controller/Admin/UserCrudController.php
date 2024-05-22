<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
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
            ->setEntityPermission('ROLE_ADMIN')
            ->setPageTitle('index', 'Utilisateurs :')
            ->setPageTitle('new', 'Créer un utilisateur')
            ->setPageTitle('edit', fn (User $user) => (string) $user->getFirstname() . ' ' . $user->getLastname())
            ->setPageTitle('detail', fn (User $user) => (string) $user->getFirstname() . ' ' . $user->getLastname())
            ->setEntityLabelInSingular('un utilisateur')
            ->setPaginatorPageSize(10);
    }

    public function configureFields(string $pageName): iterable
    {
        $roles = ['ROLE_ADMIN', 'ROLE_USER'];
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('firstname', 'Prénom :')
                ->setFormTypeOptions(['attr' => ['placeholder' => 'Prénom de l\'utilisateur']]),
            TextField::new('lastname', 'Nom :')
                ->setFormTypeOptions(['attr' => ['placeholder' => 'Nom de l\'utilisateur']]),
            EmailField::new('email', 'Email :')
                ->setFormTypeOptions(['attr' => ['placeholder' => 'Email de l\'utilisateur']]),
            TextField::new('plainPassword', 'Mot de passe :')
                ->onlyWhenCreating()->setRequired(true)
                ->setFormTypeOptions(['attr' => ['placeholder' => 'Mot de passe de l\'utilisateur']])
                ->hideOnIndex(),
            FormField::addFieldset('Rôles de l\'utilisateur'),
            ChoiceField::new('roles')
                ->setChoices(array_combine($roles, $roles))
                ->allowMultipleChoices()
                ->renderExpanded()
                ->renderAsBadges(),
            FormField::addPanel('Changer le mot de passe')->setIcon('fas fa-key')->onlyWhenUpdating(),
            TextField::new('plainPassword', 'Nouveau mot de passe :')
                ->onlyWhenUpdating()->setRequired(false)
                ->setFormTypeOptions([
                    'attr' => ['placeholder' => 'Nouveau mot de passe de l\'utilisateur'],
                ])
                ->setHelp('Pour modifier le mot de passe, vous devez saisir un nouveau mot de passe. Sinon, laissez le champ vide.')
                ->hideOnIndex(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setFirstname(ucfirst($entityInstance->getFirstname()))
            ->setLastname(strtoupper($entityInstance->getLastname()));
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setFirstname(ucfirst($entityInstance->getFirstname()))
            ->setLastname(strtoupper($entityInstance->getLastname()));
        parent::updateEntity($entityManager, $entityInstance);
    }
}
