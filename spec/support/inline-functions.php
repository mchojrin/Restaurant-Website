<?php

/**
 * Test-only loader for functions declared inline in the procedural production pages.
 *
 * The application has no autoloadable classes, and a few helpers are declared inline
 * inside conditional blocks in page files. Production files must stay untouched, so
 * the specs load the *exact* declaration from the real source at runtime: this helper
 * tokenises the file, extracts the named function declaration and evals only that
 * declaration. Nothing is copied or duplicated into the test suite.
 */

if (!function_exists('loadFunctionFromFile')) {
    /**
     * Define a named function by evaluating its declaration found in a PHP source file.
     *
     * Safe to call repeatedly: returns immediately when the function already exists.
     *
     * @throws RuntimeException When the file cannot be read or the function cannot be found/evaluated.
     */
    function loadFunctionFromFile(string $file, string $functionName): void
    {
        if (function_exists($functionName)) {
            return;
        }

        if (!is_file($file) || !is_readable($file)) {
            throw new RuntimeException(sprintf(
                'Cannot load function "%s": source file "%s" is missing or unreadable.',
                $functionName,
                $file
            ));
        }

        $source = file_get_contents($file);

        if ($source === false) {
            throw new RuntimeException(sprintf(
                'Cannot load function "%s": failed to read source file "%s".',
                $functionName,
                $file
            ));
        }

        $tokens = token_get_all($source);
        $tokenCount = count($tokens);
        $declaration = null;

        for ($index = 0; $index < $tokenCount; $index++) {
            $token = $tokens[$index];

            if (!is_array($token) || $token[0] !== T_FUNCTION) {
                continue;
            }

            // Locate the T_STRING naming this function, before the parameter list.
            $nameIndex = null;

            for ($cursor = $index + 1; $cursor < $tokenCount; $cursor++) {
                $candidate = $tokens[$cursor];

                if (is_array($candidate) && $candidate[0] === T_STRING) {
                    $nameIndex = $cursor;
                    break;
                }

                if ($candidate === '(' || $candidate === '{' || $candidate === ';') {
                    break;
                }
            }

            if ($nameIndex === null || $tokens[$nameIndex][1] !== $functionName) {
                continue;
            }

            // Capture the whole declaration, tracking brace depth so nested blocks and
            // interpolation (T_CURLY_OPEN / T_DOLLAR_OPEN_CURLY_BRACES) are handled.
            $captured = '';
            $depth = 0;
            $started = false;

            for ($cursor = $index; $cursor < $tokenCount; $cursor++) {
                $current = $tokens[$cursor];
                $captured .= is_array($current) ? $current[1] : $current;

                if ($current === '{') {
                    $depth++;
                    $started = true;
                } elseif (is_array($current) && ($current[0] === T_CURLY_OPEN || $current[0] === T_DOLLAR_OPEN_CURLY_BRACES)) {
                    $depth++;
                } elseif ($current === '}') {
                    $depth--;

                    if ($started && $depth === 0) {
                        break;
                    }
                }
            }

            if (!$started || $depth !== 0) {
                throw new RuntimeException(sprintf(
                    'Cannot load function "%s": unbalanced declaration found in "%s".',
                    $functionName,
                    $file
                ));
            }

            $declaration = $captured;
            break;
        }

        if ($declaration === null) {
            throw new RuntimeException(sprintf(
                'Cannot load function "%s": no declaration found in "%s".',
                $functionName,
                $file
            ));
        }

        eval($declaration);

        if (!function_exists($functionName)) {
            throw new RuntimeException(sprintf(
                'Cannot load function "%s": declaration from "%s" did not define it.',
                $functionName,
                $file
            ));
        }
    }
}
