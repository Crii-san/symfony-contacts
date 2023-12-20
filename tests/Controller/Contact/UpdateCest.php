<?php

namespace App\Tests\Controller\Contact;

use App\Factory\ContactFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class UpdateCest
{
    public function formShowsContactDataBeforeUpdating(ControllerTester $I): void
    {
        $user = UserFactory::createOne(['roles' => ['ROLE_ADMIN']]);
        $I->amLoggedInAs($user->object());

        ContactFactory::createOne([
            'firstname' => 'Homer',
            'lastname' => 'Simpson',
        ]);

        $I->amOnPage('/contact/1/update');

        $I->seeInTitle('Édition de Simpson, Homer');
        $I->see('Édition de Simpson, Homer', 'h1');
    }

    public function accessIsRestrictedToAuthenticatedUsers(ControllerTester $I): void
    {
        ContactFactory::createOne();
        $I->amOnPage('/contact/1/update');
        $I->seeCurrentRouteIs('app_login');
    }
}
