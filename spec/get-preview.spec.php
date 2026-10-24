<?php

require_once __DIR__ . '/support/inline-functions.php';

loadFunctionFromFile(dirname(__DIR__) . '/index.php', 'getPreview');

describe('getPreview', function () {
    it('returns text at the word limit unchanged', function () {
        expect(getPreview('one two three four five', 5))->toBe('one two three four five');
    });

    it('returns text below the word limit unchanged', function () {
        expect(getPreview('one two three', 10))->toBe('one two three');
    });

    it('truncates text above the limit to N words plus an ellipsis', function () {
        expect(getPreview('one two three four five', 3))->toBe('one two three...');
    });

    it('defaults to a ten word limit', function () {
        $elevenWords = 'one two three four five six seven eight nine ten eleven';

        expect(getPreview($elevenWords))
            ->toBe('one two three four five six seven eight nine ten...');
        expect(getPreview('one two three four five six seven eight nine ten'))
            ->toBe('one two three four five six seven eight nine ten');
    });

    it('strips HTML tags before counting and truncating words', function () {
        $html = '<p>alpha beta</p> <strong>gamma</strong> delta';

        expect(getPreview($html, 3))->toBe('alpha beta gamma...');
        expect(getPreview($html, 3))->not->toContain('<p>');
        expect(getPreview('<p>alpha beta</p>', 5))->toBe('alpha beta');
    });

    it('returns an empty string for empty input', function () {
        expect(getPreview(''))->toBe('');
    });
});
