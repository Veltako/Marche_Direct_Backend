<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    private $passwordEncoder;
    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            TextField::new('password')
            ->onlyWhenCreating(),
            ArrayField::new('roles', 'Roles'),
            TextField::new('userName', "Nom d'utilisateur"),
            TextField::new('tel', "Numéro de téléphone"),
            TextField::new('nameBusiness', "nom de l'entreprise"),
            ImageField::new('imageFileName') 
            ->setUploadDir('public/images')
            ->setBasePath('images/'),
            TextEditorField::new('descriptionCommerce'),
            TextField::new('numSiret', "Numéro de Siret"),
            DateTimeField::new('dateDeCreation'),
            AssociationField::new('commercant_marche', "Marchés"),
            AssociationField::new('userCategorie', "Catégories")
        ];
    }
}
