<?php

$dbName = 'test';
$host   = 'testtaskisolatedtransaction_postgres_1';
$dbUser = 'postgres';
$dbPass = 'kjshddfg_32sd';
$port   = 5432;
$pdo = new \PDO("pgsql:dbname=$dbName;host=$host;port=$port", $dbUser, $dbPass);

$query = $pdo->query('
    SELECT id 
    FROM events 
    ORDER BY id DESC 
    LIMIT 1;
');
$query->execute();
$lastEventId = $query->fetchColumn();

while (1) {
    $read = false;
    $query = $pdo->prepare('
        SELECT id, token, numbers, previous_count 
        FROM events
        WHERE id >= ?
        ORDER BY id ASC 
    ');
    $query->execute([$lastEventId]);

    foreach ($query->fetchAll() as $row) {
        echo 'Event: ' . $row['id']."\n";
        echo 'Token: ' . $row['token'] . "\n";
        echo 'Previous row count: ' . $row['previous_count'] . "\n";
        echo 'Numbers: [' . implode(', ', json_decode($row['numbers'])) . "]\n";
        echo "\n";

        $lastEventId = $row['id'];
        $read = true;
    }

    if ($read) {
        $lastEventId++;
    }
}
