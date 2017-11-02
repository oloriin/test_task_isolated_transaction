<?php
require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../src/MainTransactionScript.php';


$dbName = 'test';
$host   = 'testtaskisolatedtransaction_postgres_1';
$dbUser = 'postgres';
$dbPass = 'kjshddfg_32sd';
$pdo = new \PDO("pgsql:dbname=$dbName;host=$host;", $dbUser, $dbPass);


$mainTransactionScript = new \TestTaskIsolatedTransaction\MainTransactionScript($pdo);

//$request = new \http\Env\Request();
$response = $mainTransactionScript->execute(json_encode([23]));
//$response = $mainTransactionScript->execute($request->getBody());

echo $response;