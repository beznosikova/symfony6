<?php

namespace App\Controller\Admin;

use App\Entity\Survey;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class SurveyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Survey::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Survey')
            ->setEntityLabelInPlural('Surveys')
            ->setSearchFields(['name', 'status'])
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add('name');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            ChoiceField::new('status'),
            UrlField::new('apiUrl')->onlyOnIndex(),
            DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
        ];
    }
}
