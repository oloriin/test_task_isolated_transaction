<?php

$dbName = 'test';
$host   = 'localhost';
$dbUser = 'postgres';
$dbPass = 'kjshddfg_32sd';
$port   = 5434;
$pdo = new \PDO("pgsql:dbname=$dbName;host=$host;port=$port", $dbUser, $dbPass);


$q = $pdo->prepare('SELECT COUNT(token) count FROM events');
$q->execute();
$lastEvent = $q->fetch()['count'];

var_dump($lastEvent);
die();
while (1) {
    $q = $connect->query('SELECT * FROM events');

    $result = $q->execute();


    var_dump($result);

    sleep(1);

}