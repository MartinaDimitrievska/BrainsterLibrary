<?php

namespace Categories;

require_once(__DIR__ . '/../Database/Connection.php');

use Database\Connection as Connection;

class Category
{
    protected $id;
    protected $title;
    protected $is_deleted = 0;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setIsDeleted($is_deleted)
    {
        $this->is_deleted = $is_deleted;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitile()
    {
        return $this->title;
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
            'INSERT INTO `categories`
            (`title`, `is_deleted`)
            VALUES (:title, :is_deleted)'
        );

        $data = [
            'title' => $this->title,
            'is_deleted' => $this->is_deleted
        ];

        $statement->execute($data);

        $connectionObj->destroy();
    }

    public static function getAllCategories()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->query('SELECT * FROM categories WHERE is_deleted = 0');
        $categories = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $connectionObj->destroy();

        return $categories;
    }

    public function getById($id)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('SELECT * FROM categories WHERE id = :id');
        $statement->bindParam(':id', $id, \PDO::PARAM_STR);
        $statement->execute();

        $category = $statement->fetch(\PDO::FETCH_ASSOC);

        return $category;
    }

    public static function getByTitle($title)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('SELECT * FROM categories WHERE title = :title AND is_deleted = 0');
        $statement->bindParam(':title', $title, \PDO::PARAM_STR);
        $statement->execute();

        $category = $statement->fetch(\PDO::FETCH_ASSOC);

        $connectionObj->destroy();

        return $category;
    }

    public function update()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('UPDATE categories SET title = :title WHERE id = :id');
        $statement->bindParam(':title', $this->title, \PDO::PARAM_STR);
        $statement->bindParam(':id', $this->id, \PDO::PARAM_INT);
        $statement->execute();

        $connectionObj->destroy();
    }

    public function delete()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('UPDATE categories SET is_deleted = 1 WHERE id = :id');
        $statement->bindParam(':id', $this->id, \PDO::PARAM_INT);
        $statement->execute();

        $connectionObj->destroy();
    }
}
