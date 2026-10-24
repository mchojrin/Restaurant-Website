<?php

require_once __DIR__ . '/support/inline-functions.php';

loadFunctionFromFile(dirname(__DIR__) . '/admin/add_food.php', 'isOnlyLetters');

describe('isOnlyLetters', function () {
    it('accepts letters and spaces', function () {
        expect((bool) isOnlyLetters('Chicken Wings'))->toBe(true);
    });

    it('accepts a single word', function () {
        expect((bool) isOnlyLetters('Pizza'))->toBe(true);
    });

    it('rejects digits', function () {
        expect((bool) isOnlyLetters('Pizza123'))->toBe(false);
        expect((bool) isOnlyLetters('12345'))->toBe(false);
    });

    it('rejects punctuation and hyphens', function () {
        expect((bool) isOnlyLetters('Chicken-Wings'))->toBe(false);
        expect((bool) isOnlyLetters('Pizza!'))->toBe(false);
    });

    it('rejects an empty string', function () {
        expect((bool) isOnlyLetters(''))->toBe(false);
    });
});
