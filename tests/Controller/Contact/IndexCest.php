<?php

namespace App\Tests\Controller\Contact;

use App\Entity\Contact;
use App\Factory\ContactFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function _before(ControllerTester $I)
    {
    }

    // tests
    public function tryToTest(ControllerTester $I): void
    {
        for ($i = 0; $i < 5; ++$i) {
            ContactFactory::createOne();
        }

        $I->amOnPage('/contact');
        $I->seeResponseCodeIs(200);
        $I->seeInTitle('Liste des contacts');
        $I->see('Liste des contacts', 'h1');
    }

    public function clickFirstLink(ControllerTester $I): void
    {
        $I->amOnPage('/contact');

        $contact = new Contact();
        $contact->setFirstname('Joe');
        $contact->setLastname('Aaaaaaaaaaaaaaa');

        for ($i = 0; $i < 5; ++$i) {
            ContactFactory::createOne();
        }

        $I->click('li:first-child a');
        $I->seeCurrentRouteIs('detail_contact');
    }
}