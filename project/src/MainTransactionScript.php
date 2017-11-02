<?php
namespace TestTaskIsolatedTransaction;

class MainTransactionScript
{
    /**
     * @var string
     */
    private $messageBody;
    /**
     * @var \PDO
     */
    private $PDO;

    public function __construct(string $messageBody, \PDO $PDO)
    {

        $this->messageBody = $messageBody;
        $this->PDO = $PDO;
    }

    public function execute()
    {
        $message =  json_decode($this->messageBody);
        $previousCount = 10;

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
}