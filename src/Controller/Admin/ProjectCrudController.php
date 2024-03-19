<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            // Index only
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('user')->onlyOnIndex(),
            AssociationField::new('tasks'),
            // Creating only
            AssociationField::new('user')->onlyWhenCreating(),
            // Updating only
            // Everywhere
            TextField::new('name'),
            TextareaField::new('description'),
        ];
    }

}
