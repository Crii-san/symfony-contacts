<?php

namespace App\Tests\Controller\Contact;

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
        $contact = ContactFactory::createOne(['firstname' => 'Joe', 'lastname' => 'Aaaaaaaaaaaaaaa']);
        for ($i = 0; $i < 5; ++$i) {
            ContactFactory::createOne();
        }
        $I->amOnPage('/contact');
        $I->click('li:first-child a');
        $I->seeCurrentRouteIs('detail_contact');
    }

    public function controlSortContact(ControllerTester $I): void
    {
        ContactFactory::createSequence([
            ['firstname' => 'Jean', 'lastname' => 'O'],
            ['firstname' => 'Jean', 'lastname' => 'C'],
            ['firstname' => 'Jean', 'lastname' => 'Z'],
            ['firstname' => 'Jean', 'lastname' => 'A'],
        ]);

        $I->amOnPage('/contact');

        $listContact = $I->grabMultiple('//ul/li/a');

        $listContact = array_map('trim', $listContact);

        $expected = [
            'A Jean',
            'C Jean',
            'O Jean',
            'Z Jean',
        ];

        $I->assertEquals($expected, $listContact, "L'ordre est incorrect");
    }
}
