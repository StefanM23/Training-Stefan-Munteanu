<?php

session_start();
require_once 'config.php';
require_once 'languages.php';
//connextion phpMyAdmin
$dsn = 'mysql:host=' . LOCALHOST . ';dbname=' . DATABASE . ';charset=UTF8';
try {
    $connection = new PDO($dsn, USERNAME, PASSWORD);
} catch (PDOException $e) {
    die($e->getMessage());
}

function translateLabels($string)
{
    global $arr;
    $langCode = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

    if (isset($arr[$langCode][$string])) {
        $string = $arr[$langCode][$string];
    }

    return $string;
}
