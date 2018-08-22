<?php
namespace TestTaskIsolatedTransaction\Test;

require_once('../MainTransactionScript.php');
use \PHPUnit\Framework\TestCase;
use \TestTaskIsolatedTransaction\MainTransactionScript;

class MainTransactionScriptTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testExecute_validMessage_correctRowInEvents()
    {
        $dbName = 'test';
        $host   = 'testtaskisolatedtransaction_postgres_1';
        $dbUser = 'postgres';
        $dbPass = 'kjshddfg_32sd';
        $port   = 5434;
        $connect = new \PDO("pgsql:dbname=$dbName;host=$host;port=$port", $dbUser, $dbPass);

        $message = json_encode([223, 67, 234]);

        $class = new MainTransactionScript($connect);


        $class->execute($message);


        $query = $connect->query('SELECT numbers FROM events ORDER BY id DESC LIMIT 1');
        $query->execute();
        $resultNumbers = $query->fetchColumn();

        $this->assertJson($resultNumbers);
        $this->assertSame(json_decode($message), json_decode($resultNumbers));
    }
}