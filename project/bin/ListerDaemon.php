<?php

$dbName = 'test';
$host   = 'localhost';
$dbUser = 'postgres';
$dbPass = 'kjshddfg_32sd';
$port   = 5434;
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
        var_dump($row);
        $lastEventId = $row['id'];
        $read = true;
    }

    if ($read) {
       $lastEventId++;
    }

}
