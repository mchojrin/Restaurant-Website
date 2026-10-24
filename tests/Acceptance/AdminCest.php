<?php

declare(strict_types=1);

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

/**
 * Read-only checks of the admin authentication flow.
 *
 * The credentials below are the local-dev seed from databasefiles/food_order.sql.
 */
final class AdminCest
{
    private const ADMIN_USERNAME = 'katojkalemba';
    private const ADMIN_PASSWORD = 'Kalemba@2000';

    public function loginPageIsReachable(AcceptanceTester $I): void
    {
        $I->amOnPage('/admin/login.php');

        $I->seeResponseCodeIs(200);
        $I->seeElement('form');
        $I->seeElement('input[name="username"]');
        $I->seeElement('input[name="password"]');
        $I->seeElement('button[name="login"]');
    }

    public function dashboardRequiresLogin(AcceptanceTester $I): void
    {
        // Make sure a previous test's admin session cannot mask the redirect.
        $I->amOnPage('/admin/logout.php');

        $I->amOnPage('/admin/index.php');

        $I->seeResponseCodeIs(200);
        $I->seeInCurrentUrl('login.php');
        $I->seeElement('input[name="username"]');
    }

    public function validCredentialsReachDashboard(AcceptanceTester $I): void
    {
        $I->amOnPage('/admin/login.php');
        $I->fillField(['name' => 'username'], self::ADMIN_USERNAME);
        $I->fillField(['name' => 'password'], self::ADMIN_PASSWORD);
        $I->click('button[name="login"]');

        $I->seeResponseCodeIs(200);
        $I->seeInCurrentUrl('/admin/');
        $I->see('dashboard');
    }

    /**
     * Guards the root-relative SITEURL redirect: SITEURL="/" plus a leading
     * slash in the path used to produce a protocol-relative "//admin/..." URL.
     * No query params means the handler redirects without deleting anything.
     */
    public function deleteCategoryWithoutParamsRedirectsToCategoryList(AcceptanceTester $I): void
    {
        $I->stopFollowingRedirects();

        $I->amOnPage('/admin/delete_category.php');

        $I->seeResponseCodeIs(302);
        $I->seeHttpHeader('Location', '/admin/category.php');
    }
}
