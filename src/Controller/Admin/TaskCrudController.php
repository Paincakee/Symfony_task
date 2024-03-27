<?php

namespace App\Controller\Admin;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Uid\Uuid;

class TaskCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Task::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // Index Only
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('user')->onlyOnIndex(),
            AssociationField::new('project')->onlyOnIndex(),
            DateTimeField::new('date')->onlyOnIndex(),
            // Creating Only
            AssociationField::new('user')->onlyWhenCreating(),
            AssociationField::new('project')->onlyWhenCreating(),
            // Updating only
            //Everywhere
            TextField::new('name'),
            TextareaField::new('description'),
            AssociationField::new('categories'),
            ChoiceField::new('priority')->setChoices([
                'High' => 'High',
                'Medium' => 'Medium',
                'Low' => 'Low',
            ]),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setDate(new \DateTime());

        // Call the parent method to persist the entity
        parent::persistEntity($entityManager, $entityInstance);
    }

}


