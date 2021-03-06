<?php


namespace core;

use core\database\connection\MysqlConnection;

class Database
{
    protected $connections = [];

    protected function getDefaultConnection()
    {
        return app('config')->get('database.default');
    }

    public function setDefaultConnection($name)
    {
        return app('config')->set('database.default', $name);
    }

    public function connection($name = null)
    {
        if (isset($this->connections[$name])) {
            return $this->connections[$name];
        }
        if ($name == null) {
            $name = $this->getDefaultConnection();
        }
        $config = app('config')->get('database.connections.' . $name);
        $connectionClass = null;
        switch ($config['driver']) {
            case 'mysql':
                $connectionClass = MysqlConnection::class;
                break;
        }
        $dsn = sprintf('%s:host=%s;dbname=%s', $config['driver'], $config['host'], $config['dbname']);
        try {
            $pdo = new \PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (\PDOException $e) {
//            dd($config);
            die($e->getMessage());
        }
        return $this->connections[$name] = new $connectionClass($pdo, $config);
    }

    public function __call($method, $parameters)
    {
        return $this->connection()->$method(...$parameters);
    }

}