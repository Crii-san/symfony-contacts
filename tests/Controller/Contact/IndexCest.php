<?php

namespace App\Tests\Controller\Contact;

use App\Factory\CategoryFactory;
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
            CategoryFactory::createOne();
            ContactFactory::createOne();
        }

        $I->amOnPage('/contact');
        $I->seeResponseCodeIs(200);
        $I->seeInTitle('Liste des contacts');
        $I->see('Liste des contacts', 'h1');
    }

    public function clickFirstLink(ControllerTester $I): void
    {
        CategoryFactory::createOne();
        ContactFactory::createOne(['firstname' => 'Joe', 'lastname' => 'Aaaaaaaaaaaaaaa']);
        for ($i = 0; $i < 5; ++$i) {
            CategoryFactory::createOne();
            ContactFactory::createOne();
        }
        $I->amOnPage('/contact');
        $I->click('ul.contacts > li:first-child a:nth-child(3)');
        $I->seeCurrentRouteIs('detail_contact');
    }

    public function controlSortContact(ControllerTester $I): void
    {
        CategoryFactory::createOne();
        ContactFactory::createSequence([
            ['firstname' => 'Jean', 'lastname' => 'O'],
            ['firstname' => 'Jean', 'lastname' => 'C'],
            ['firstname' => 'Jean', 'lastname' => 'Z'],
            ['firstname' => 'Jean', 'lastname' => 'A'],
        ]);

        $I->amOnPage('/contact');

        $listContact = $I->grabMultiple('//ul[@class="contacts"]/li/a[3]');

        $listContact = array_map(static function ($contact) {
            return preg_replace('/\s+/', ' ', trim($contact));
        }, $listContact);

        $expected = [
            'A Jean',
            'C Jean',
            'O Jean',
            'Z Jean',
        ];

        $I->assertEquals($expected, $listContact, "L'ordre est incorrect");
    }


    public function search(ControllerTester $I): void
    {
        for ($i = 0; $i < 2; ++$i) {
            CategoryFactory::createOne();
            ContactFactory::createOne();
        }
        CategoryFactory::createOne();
        ContactFactory::createOne(['firstname' => 'Abcdefg', 'lastname' => 'Dupont']);
        CategoryFactory::createOne();
        ContactFactory::createOne(['firstname' => 'Jean', 'lastname' => 'Abcdefg']);

        $chercheCaractere = 'Abcdefg';

        $I->amOnPage('/contact?search='.$chercheCaractere);
        $I->see('Dupont Abcdefg');
        $I->see('Abcdefg Jean');
    }
}
