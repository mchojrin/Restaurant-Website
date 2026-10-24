<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * Inherited Methods
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * Asserts an HTTP header on the last response.
     *
     * Codeception 5.3 / module-phpbrowser does not provide a header assertion,
     * so read the Symfony BrowserKit response through the PhpBrowser module.
     */
    public function seeHttpHeader(string $name, string $value): void
    {
        /** @var array<string, \Codeception\Module> $modules */
        $modules = $this->getScenario()->current('modules');
        /** @var \Codeception\Module\PhpBrowser $phpBrowser */
        $phpBrowser = $modules['PhpBrowser'];
        $actual = $phpBrowser->client->getInternalResponse()->getHeader($name);

        $this->assertSame(
            $value,
            $actual,
            sprintf('Expected HTTP header "%s: %s", got "%s".', $name, $value, var_export($actual, true))
        );
    }
}
