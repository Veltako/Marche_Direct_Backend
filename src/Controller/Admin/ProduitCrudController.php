<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('productName', "Nom du produit"),
            TextEditorField::new('description', "Description"),
            MoneyField::new('prix')
            ->setCurrency('EUR')
            ->setStoredAsCents(false)
            ->setNumDecimals(2),
            AssociationField::new('format', "Format"),
            IntegerField::new('stock'),
            AssociationField::new('userProduct', "Commerçant"),
            ImageField::new('imageFileName', "Image")
            ->setUploadDir('public/images')
            ->setBasePath('images/'),
        ];
    }
}
