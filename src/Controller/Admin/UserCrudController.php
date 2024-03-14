<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class UserCrudController extends AbstractCrudController
{
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('uuid')->onlyOnIndex(),
            TextField::new('email'),
            TextField::new('name'),
            ArrayField::new('roles')

        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Generate UUID for the new user
        $uuid = Uuid::v4();
        $defaultPassword = 'test11';

        // Set the UUID to the user entity
        $entityInstance->setUuid($uuid);

        // Hash the default password using the password hasher
        $hashedPassword = $this->userPasswordHasher->hashPassword($entityInstance, $defaultPassword);

        // Set the hashed password to the entity
        $entityInstance->setPassword($hashedPassword);

        // Call the parent method to persist the entity
        parent::persistEntity($entityManager, $entityInstance);
    }

}
