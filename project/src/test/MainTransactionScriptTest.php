<?php
namespace TestTaskIsolatedTransaction\Test;

require_once('../MainTransactionScript.php');
use \PHPUnit\Framework\TestCase;
use \TestTaskIsolatedTransaction\MainTransactionScript;

class MainTransactionScriptTest extends TestCase
{
    public function testExecute_validMessage_jsonResponse()
    {
        list($usec, $sec) = explode(' ', microtime());
        $usec = round((float)$usec, 6);
        $usec = str_replace("0.", ".", (string)$usec);

        $date = date("Y-m-d H:i:s", $sec);
        $eventDate = $date.$usec;

        $dbName = 'test';
        $host   = 'localhost';
        $dbUser = 'postgres';
        $dbPass = 'kjshddfg_32sd';
        $port   = 5434;
        $connect = new \PDO("pgsql:dbname=$dbName;host=$host;port=$port", $dbUser, $dbPass);

        $message = json_encode([223, 67, 234]);

        $class = new MainTransactionScript($message, $connect);

        $response = $class->execute();

        $this->assertJson($response);
    }
}