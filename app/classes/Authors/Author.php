<?php

namespace Authors;

require_once(__DIR__ . '/../Database/Connection.php');

use Database\Connection as Connection;

class Author
{
    protected $id;
    protected $first_name;
    protected $last_name;
    protected $short_bio;
    protected $is_deleted = 0;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    public function setShortBio($short_bio)
    {
        $this->short_bio = $short_bio;
    }

    public function setIsDeleted($is_deleted)
    {
        $this->is_deleted = $is_deleted;
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

    public function getShortBio()
    {
        return $this->short_bio;
    }

    public function getIsDeleted()
    {
        return $this->is_deleted;
    }

    public function store()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare(
            'INSERT INTO `authors`
            (`first_name`, `last_name`, `short_bio`, `is_deleted`)
            VALUES (:first_name, :last_name, :short_bio, :is_deleted)'
        );

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'short_bio' => $this->short_bio,
            'is_deleted' => $this->is_deleted
        ];

        $statement->execute($data);

        $connectionObj->destroy();
    }

    public static function getAllAuthors()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->query('SELECT * FROM authors WHERE is_deleted = 0');
        $authors = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $connectionObj->destroy();

        return $authors;
    }

    public static function getById($id)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('SELECT * FROM authors WHERE id = :id');
        $statement->bindParam(':id', $id, \PDO::PARAM_STR);
        $statement->execute();

        $author = $statement->fetch(\PDO::FETCH_ASSOC);

        return $author;
    }

    public static function getByName($first_name, $last_name)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('SELECT * FROM authors WHERE first_name = :first_name AND last_name = :last_name AND is_deleted = 0');
        $statement->bindParam(':first_name', $first_name, \PDO::PARAM_STR);
        $statement->bindParam(':last_name', $last_name, \PDO::PARAM_STR);
        $statement->execute();

        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        return $user;
    }

    public function update()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('UPDATE authors SET first_name = :first_name, last_name = :last_name, short_bio = :short_bio WHERE id = :id');
        $statement->bindParam(':first_name', $this->first_name, \PDO::PARAM_STR);
        $statement->bindParam(':last_name', $this->last_name, \PDO::PARAM_STR);
        $statement->bindParam(':short_bio', $this->short_bio, \PDO::PARAM_STR);
        $statement->bindParam(':id', $this->id, \PDO::PARAM_INT);
        $statement->execute();

        $connectionObj->destroy();
    }

    public function delete()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('UPDATE authors SET is_deleted = 1 WHERE id = :id');
        $statement->bindParam(':id', $this->id, \PDO::PARAM_INT);
        $statement->execute();

        $connectionObj->destroy();
    }
}
