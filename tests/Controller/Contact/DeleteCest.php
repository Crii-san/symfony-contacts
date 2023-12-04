<?php

namespace App\Tests\Controller\Contact;

use App\Factory\ContactFactory;
use App\Tests\Support\ControllerTester;

class DeleteCest
{
    public function form(ControllerTester $I): void
    {
        ContactFactory::createOne([
            'firstname' => 'Homer',
            'lastname' => 'Simpson',
        ]);

        $I->amOnPage('/contact/1/delete');

        $I->seeInTitle('Suppression de Simpson, Homer');
        $I->see('Suppression de Simpson, Homer', 'h1');
    }
}
