<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $repertoire = __DIR__;
        $contenuFichier = file_get_contents("{$repertoire}\data\Category.json");
        $contenuFichierDecode = json_decode($contenuFichier);

        foreach ($contenuFichierDecode as $element) {
            $name = $element->name;
            CategoryFactory::createOne(['name' => $name]);
        }
    }

}
