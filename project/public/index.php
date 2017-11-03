<?php
require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../src/MainTransactionScript.php';


$dbName = 'test';
$host   = 'testtaskisolatedtransaction_postgres_1';
$dbUser = 'postgres';
$dbPass = 'kjshddfg_32sd';
$pdo = new \PDO("pgsql:dbname=$dbName;host=$host;", $dbUser, $dbPass);


$mainTransactionScript = new \TestTaskIsolatedTransaction\MainTransactionScript($pdo);

$content = file_get_contents('php://input');

$message = '';
try{
    $response = $mainTransactionScript->execute($content);
    http_response_code(200);
} catch (\Exception $exception) {
    http_response_code(409);
    $message = $exception->getMessage();
}
echo $message;