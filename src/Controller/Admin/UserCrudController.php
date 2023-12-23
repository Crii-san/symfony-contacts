<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstname'),
            TextField::new('lastname'),
            TextField::new('email'),
            TextField::new('password')->onlyOnForms()
            ->setFormType(PasswordType::class)
            ->setFormTypeOptions([
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'autocomplete' => 'off',
                ],
            ]),
            ArrayField::new('roles')
                ->formatValue(function ($value) {
                    if (in_array('ROLE_ADMIN', $value)) {
                        return '<span class="material-symbols-outlined">manage_accounts</span>
                                <span class="material-symbols-outlined">person</span>';
                    }
                    if (in_array('ROLE_USER', $value)) {
                        return '<span class="material-symbols-outlined">person</span>';
                    } else {
                        return '';
                    }
                }),
        ];
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->setUserPassword($entityInstance);

        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->setUserPassword($entityInstance);

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function setUserPassword($entityInstance): void
    {
        $request = $this->getContext()->getRequest();
        $password = $request->get('User')['password'] ?? null;

        if (!empty($password)) {
            $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $password);
            $entityInstance->setPassword($hashedPassword);
        }
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets->addCssFile('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined');
    }
}
