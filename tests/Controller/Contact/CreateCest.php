<?php

namespace App\Tests\Controller\Contact;

use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class CreateCest
{
    public function form(ControllerTester $I): void
    {
        $post = UserFactory::createOne(['roles' => ['ROLE_USER']]);
        $realPost = $post->object();
        $I->amLoggedInAs($realPost);

        $I->amOnPage('/contact/create');

        $I->seeInTitle("Création d'un nouveau contact");
        $I->see("Création d'un nouveau contact", 'h1');
    }
}
