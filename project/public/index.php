<?php
require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../src/MainTransactionScript.php';


$dbName = 'test';
$host   = 'localhost';
$dbUser = 'postgres';
$dbPass = 'kjshddfg_32sd';

$connect = new PDO("pgsql:dbname=$dbName;host=$host", $dbUser, $dbPass);

$mainTransactionScript = new \TestTaskIsolatedTransaction\MainTransactionScript(http_get_request_body(), $connect);
$response = $mainTransactionScript->execute();

echo $response;