<?php

namespace Database;

class Connection
{
    const HOST = 'localhost';
    const DB_NAME = 'brainster_library';
    const USERNAME = 'root';
    const PASSWORD = '';

    protected $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO('mysql:host=' . self::HOST . ';dbname=' . self::DB_NAME, self::USERNAME, self::PASSWORD);
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function destroy()
    {
        $this->pdo = null;
    }
}
