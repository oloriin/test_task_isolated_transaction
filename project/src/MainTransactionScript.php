<?php
namespace TestTaskIsolatedTransaction;

class MainTransactionScript
{
    /** @var \PDO */
    private $pdo;

    public function __construct(\PDO $PDO)
    {
        $this->pdo = $PDO;
    }

    public function execute(string $messageBody): bool
    {
        $message =  json_decode($messageBody);

        $this->pdo->beginTransaction();
        //очень общий try и Exception
        try {
            //Привет фантомное чтение (как вариант собирать все одной процедурой)
            $sql = 'SELECT COUNT(id) count FROM collector';
            $query = $this->pdo->query($sql);
            $query->execute();
            $previousEventCount = $query->fetch()['count'];

            $valueObject = [
                'previous_count'=> $previousEventCount,
                'numbers'       => (array)$message,
                'token'         => $this->guidv4(random_bytes(16))
            ];

            $query = $this->pdo->prepare('INSERT INTO collector (data) VALUES (?);');
            $result = $query->execute([json_encode($valueObject)]);

            if ($result === true) {
                $this->pdo->commit();
                return true;
            } else {
                $this->pdo->rollBack();
                throw new \Exception($query->errorInfo());
            }
        } catch (\Exception $exception) {
            $this->pdo->rollBack();
            throw new \Exception($exception->getMessage());
        }

    }

    private function guidv4($data)
    {
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}