<?php
namespace TestTaskIsolatedTransaction;

class MainTransactionScript
{
    /**
     * @var \PDO
     */
    private $PDO;
    /**
     * @var string
     */
    private $startMicroTime;

    public function __construct(\PDO $PDO, string $startMicroTime)
    {
        $this->PDO = $PDO;
        $this->startMicroTime = $startMicroTime;
    }

    public function execute(string $messageBody)
    {
        $eventDate = $this->convertMicrotimeToTimestamp($this->startMicroTime);

        $message =  json_decode($messageBody);
        $previousCount = 10;

        $sql = 'INSERT INTO collector (data, microtime)
VALUES (\'{"token": "A0EEBC99-9C0B-4EF8-BB6D-6BB9BD380A11", "row_count": 1, "numbers": [23, 34, 45]}\',
        \'2017-11-02 06:48:01.170357\'
  );';



        $valueObject = [
            'previousCount' => $previousCount,
            'numbers'       => (array)$message
        ];

        //$connect->beginTransaction();
        //
        //$connect->rollBack();
        //
        //$connect->commit();


        return json_encode($valueObject);
    }

    private function convertMicrotimeToTimestamp(string $microtime): string
    {
        list($usec, $sec) = explode(' ', $this->startMicroTime);
        $usec = round((float)$usec, 6);
        $usec = str_replace("0.", ".", (string)$usec);

        $date = date("Y-m-d H:i:s", $sec);

        return $date.$usec;
    }
}