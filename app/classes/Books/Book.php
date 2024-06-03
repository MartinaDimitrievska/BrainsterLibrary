<?php

namespace Books;

require_once(__DIR__ . '/../Database/Connection.php');
require_once(__DIR__ . '/../Comments/Comment.php');
require_once(__DIR__ . '/../PrivateNotes/PrivateNote.php');

use Database\Connection as Connection;
use Comments\Comment as Comment;
use PrivateNotes\PrivateNote as PrivateNote;

class Book
{
    protected $id;
    protected $image_url;
    protected $title;
    protected $year_published;
    protected $number_of_pages;
    protected $is_deleted = 0;
    protected $author_id;
    protected $category_id;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setImageUrl($image_url)
    {
        $this->image_url = $image_url;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setYearPublished($year_published)
    {
        $this->year_published = $year_published;
    }

    public function setNumberOfPages($number_of_pages)
    {
        $this->number_of_pages = $number_of_pages;
    }

    public function setIsDeleted($is_deleted)
    {
        $this->is_deleted = $is_deleted;
    }

    public function setAuthorId($author_id)
    {
        $this->author_id = $author_id;
    }

    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getYearPublished()
    {
        return $this->year_published;
    }

    public function getNumberOfPages()
    {
        return $this->number_of_pages;
    }

    public function getIsDeleted()
    {
        return $this->is_deleted;
    }

    public function getAuthorId()
    {
        return $this->author_id;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function store()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare(
            'INSERT INTO `books`
            ( `image_url`, `title`, `year_published`, `number_of_pages`, `is_deleted`, `author_id`, `category_id`)
            VALUES ( :image_url, :title, :year_published, :number_of_pages, :is_deleted, :author_id, :category_id)'
        );

        $data = [
            'image_url' => $this->image_url,
            'title' => $this->title,
            'year_published' => $this->year_published,
            'number_of_pages' => $this->number_of_pages,
            'is_deleted' => $this->is_deleted,
            'author_id' => $this->author_id,
            'category_id' => $this->category_id
        ];

        $statement->execute($data);

        $connectionObj->destroy();
    }

    public static function getAllBooks()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $query = "
            SELECT 
                books.*, 
                authors.first_name AS author_first_name, 
                authors.last_name AS author_last_name, 
                categories.title AS category_title
            FROM books
            LEFT JOIN authors ON books.author_id = authors.id
            LEFT JOIN categories ON books.category_id = categories.id
            WHERE books.is_deleted = 0
        ";

        $statement = $connection->query($query);
        $books = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $connectionObj->destroy();

        return $books;
    }

    public static function getById($id)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('
            SELECT books.*, 
                authors.first_name AS author_first_name, 
                authors.last_name AS author_last_name, 
                categories.title AS category_title
            FROM books
            JOIN authors ON books.author_id = authors.id
            JOIN categories ON books.category_id = categories.id
            WHERE books.id = :id
        ');
        $statement->bindParam(':id', $id, \PDO::PARAM_STR);
        $statement->execute();

        $book = $statement->fetch(\PDO::FETCH_ASSOC);

        return $book;
    }

    public static function getByCategory($categoryIds)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

        $query = "
            SELECT 
                books.*, 
                authors.first_name as author_first_name, 
                authors.last_name as author_last_name, 
                categories.title as category_title
            FROM books
            JOIN authors ON books.author_id = authors.id
            JOIN categories ON books.category_id = categories.id
            WHERE books.category_id IN ($placeholders) AND books.is_deleted = 0
        ";

        $statement = $connection->prepare($query);
        $statement->execute($categoryIds);

        $books = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $connectionObj->destroy();

        return $books;
    }

    public function update()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('UPDATE books SET image_url = :image_url, title = :title, year_published = :year_published, number_of_pages = :number_of_pages, author_id = :author_id, category_id = :category_id  WHERE id = :id');
        $statement->bindParam(':image_url', $this->image_url, \PDO::PARAM_STR);
        $statement->bindParam(':title', $this->title, \PDO::PARAM_STR);
        $statement->bindParam(':year_published', $this->year_published, \PDO::PARAM_STR);
        $statement->bindParam(':number_of_pages', $this->number_of_pages, \PDO::PARAM_STR);
        $statement->bindParam(':author_id', $this->author_id, \PDO::PARAM_STR);
        $statement->bindParam(':category_id', $this->category_id, \PDO::PARAM_STR);
        $statement->bindParam(':id', $this->id, \PDO::PARAM_INT);
        $statement->execute();

        $connectionObj->destroy();
    }

    public static function deleteBookAndCommentsAndPrivateNotes($id)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $updateStatement = $connection->prepare('UPDATE books SET is_deleted = 1 WHERE id = :id');
        $updateStatement->bindParam(':id', $id, \PDO::PARAM_INT);
        $updateStatement->execute();

        Comment::DeleteCommentsByBookId($id);
        PrivateNote::deletePrivateNotesByBookId($id);

        $connectionObj->destroy();
    }
}
