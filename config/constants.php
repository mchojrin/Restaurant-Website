<?php
    session_start();
    // working on constants
    // Values come from the environment (Docker) and fall back to the original
    // local-development settings when no environment variables are provided.
    // Simple getenv() expressions are used on purpose: no helper functions that
    // could be redeclared if this file is included more than once.
    $dbHost = getenv('DB_HOST');
    if ($dbHost === false || $dbHost === '') {
        $dbHost = 'localhost';
    }

    $dbUser = getenv('DB_USER');
    if ($dbUser === false || $dbUser === '') {
        $dbUser = 'root';
    }

    // An explicitly empty value (e.g. DB_PASSWORD= in .env) stays empty.
    $dbPassword = getenv('DB_PASSWORD');
    if ($dbPassword === false) {
        $dbPassword = "";
    }

    $dbName = getenv('DB_NAME');
    if ($dbName === false || $dbName === '') {
        $dbName = 'food_order';
    }

    // Accept "/", "http://host", "http://host/app" and always end with exactly
    // one slash so it can be concatenated directly with relative paths.
    $siteUrl = getenv('SITEURL');
    if ($siteUrl === false || trim($siteUrl) === '') {
        $siteUrl = "http://localhost/web/";
    }
    $siteUrl = rtrim(trim($siteUrl), '/') . '/';

    define('LOCALHOST', $dbHost);
    define('DB_USERNAME', $dbUser);
    define("DBNAME", $dbName);
    define('DBPASSWORD', $dbPassword);
    define("SITEURL", $siteUrl);


    // The app was written for the pre-PHP 8.1 mysqli behaviour (return false on
    // failure). PHP 8.1+ defaults to throwing mysqli_sql_exceptions, which would
    // bypass the friendly checks below, so restore the legacy error mode.
    mysqli_report(MYSQLI_REPORT_OFF);

    $conn = mysqli_connect(LOCALHOST, DB_USERNAME, DBPASSWORD);
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }
    $db_conn = mysqli_select_db($conn, DBNAME) or die(mysqli_error($conn));
?>
