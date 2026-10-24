<?php

declare(strict_types=1);

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

/**
 * Read-only checks of the public storefront.
 *
 * These tests hit the app over HTTP and never write to the database.
 */
final class StorefrontCest
{
    public function homepageShowsStorefront(AcceptanceTester $I): void
    {
        $I->amOnPage('/');

        $I->seeResponseCodeIs(200);
        $I->see('Explore Foods');
        $I->see('Food Menu');
        $I->seeElement('form[action$="food-search.php"]');
        $I->seeElement('input[name="search"]');
    }

    public function categoriesPageIsReachable(AcceptanceTester $I): void
    {
        $I->amOnPage('/categories.php');

        $I->seeResponseCodeIs(200);
        $I->seeElement('body');
    }

    public function foodsPageShowsFoodMenu(AcceptanceTester $I): void
    {
        $I->amOnPage('/foods.php');

        $I->seeResponseCodeIs(200);
        $I->see('Food Menu');
    }

    /**
     * The order form is rendered for any id, even if the seeded food row is
     * missing, so asserting on the form (not on food data) is stable.
     */
    public function orderPageShowsOrderForm(AcceptanceTester $I): void
    {
        $I->amOnPage('/order.php?id=1');

        $I->seeResponseCodeIs(200);
        $I->see('Fill this form to confirm your order.');
        $I->see('Selected Food');
        $I->seeElement('form.order');
        $I->seeElement('input[name="qty"]');
        $I->seeElement('input[name="full-name"]');
        $I->seeElement('input[name="submit"][value="Confirm Order"]');
    }
}
