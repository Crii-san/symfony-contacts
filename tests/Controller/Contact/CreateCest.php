<?php

namespace App\Tests\Controller\Contact;

use App\Tests\Support\ControllerTester;

class CreateCest
{
    public function form(ControllerTester $I): void
    {
        $I->amOnPage('/contact/create');

        $I->seeInTitle("Création d'un nouveau contact");
        $I->see("Création d'un nouveau contact", 'h1');
    }
}
