<?php

namespace App\Controller\Admin;

use App\Entity\Marche;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MarcheCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Marche::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('marcheName', "Nom du marché"),
            TextField::new('place', "Lieu"),
            TextField::new('hourly', "Horaire"),
            AssociationField::new('days', 'Days')
                ->setFormTypeOption('by_reference', false),
            ImageField::new('imageFileName')
                ->setUploadDir('public/images')
                ->setBasePath('images/'),
            TextField::new('description'),
            AssociationField::new('commercant_marche', 'Commercant'),
        ];
    }
}