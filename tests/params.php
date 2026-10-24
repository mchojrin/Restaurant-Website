<?php

/**
 * Extra Codeception parameters, available in suite configs as %NAME%.
 *
 * The acceptance suite runs from the host against the (already running)
 * Docker stack. Override the target with APP_URL, e.g.
 *
 *   APP_URL=http://localhost:9090 vendor/bin/codecept run Acceptance
 *   APP_URL=http://web vendor/bin/codecept run Acceptance
 */

$appUrl = getenv('APP_URL');
if ($appUrl === false || trim($appUrl) === '') {
    $appUrl = 'http://localhost:8080';
}

return [
    'APP_URL' => rtrim(trim($appUrl), '/'),
];
