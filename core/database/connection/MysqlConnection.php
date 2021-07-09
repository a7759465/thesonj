<?php


namespace core\database\connection;
class MysqlConnection extends Connection
{
    protected static $connection;

    public function getConnection()
    {
        return self::$connection;
    }

    public function select($sql, $bindings = [], $useReadPdo = true)
    {
        $statement = $this->pdo;
        $sth = $statement->prepare($sql);
        try {
            $sth->execute($bindings);
            return $sth->fetchAll();
        } catch (\PDOException $exception) {
            echo $exception->getMessage();
        }
    }
}