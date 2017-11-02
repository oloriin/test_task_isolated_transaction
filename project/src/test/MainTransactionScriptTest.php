<?php
namespace TestTaskIsolatedTransaction\Test;

require_once('../MainTransactionScript.php');
use \PHPUnit\Framework\TestCase;
use \TestTaskIsolatedTransaction\MainTransactionScript;

class MainTransactionScriptTest extends TestCase
{
    public function testExecute_validMessage_correctRowInEvents()
    {
        $dbName = 'test';
        $host   = 'localhost';
        $dbUser = 'postgres';
        $dbPass = 'kjshddfg_32sd';
        $port   = 5434;
        $connect = new \PDO("pgsql:dbname=$dbName;host=$host;port=$port", $dbUser, $dbPass);
//        $connect->query('TRUNCATE collector, events')->execute();

        $message = json_encode([223, 67, 234]);

        $class = new MainTransactionScript($connect);


        $class->execute($message);


        $query = $connect->query('SELECT numbers FROM events LIMIT 1');
        $query->execute();
        $resultNumbers = $query->fetchColumn();

        $this->assertJson($resultNumbers);
        $this->assertSame(json_decode($message), json_decode($resultNumbers));
    }
}