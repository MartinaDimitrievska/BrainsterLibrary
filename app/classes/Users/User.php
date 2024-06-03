<?php

namespace Users;

require_once(__DIR__ . '/../Database/Connection.php');

use Database\Connection as Connection;

class User
{
    protected $id;
    protected $first_name;
    protected $last_name;
    protected $username;
    protected $email;
    protected $password;
    protected $is_deleted = 0;
    protected $role_id = 1;

    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setisDeleted($is_deleted)
    {
        $this->is_deleted = $is_deleted;
    }

    public function setRoleId($role_id)
    {
        $this->role_id = $role_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getisDeleted()
    {
        return $this->is_deleted;
    }

    public function getRoleId()
    {
        return $this->role_id;
    }

    public function store()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare(
            'INSERT INTO `users`
            (`first_name`, `last_name`, `username`, `email`, `password`, `is_deleted`, `role_id`)
            VALUES (:first_name, :last_name, :username, :email, :password, :is_deleted, :role_id)'
        );

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => password_hash($this->password, PASSWORD_DEFAULT),
            'is_deleted' => $this->is_deleted,
            'role_id' => $this->role_id
        ];

        $statement->execute($data);

        $connectionObj->destroy();
    }

    public function authenticate()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('SELECT * FROM users WHERE username = :username');
        $statement->bindParam(':username', $this->username, \PDO::PARAM_STR);
        $statement->execute();

        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        $connectionObj->destroy();

        if (!empty($user) && password_verify($this->password, $user['password'])) {
            return true;
        }

        return false;
    }

    public function getByUsername($username)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('SELECT * FROM users WHERE username = :username');
        $statement->bindParam(':username', $username, \PDO::PARAM_STR);
        $statement->execute();

        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        return $user;
    }

    public function getByEmail($email)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('SELECT * FROM users WHERE email = :email');
        $statement->bindParam(':email', $email, \PDO::PARAM_STR);
        $statement->execute();

        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        return $user;
    }
}
