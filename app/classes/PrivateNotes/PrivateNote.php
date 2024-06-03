<?php

namespace PrivateNotes;

require_once(__DIR__ . '/../Database/Connection.php');

use Database\Connection as Connection;

class PrivateNote
{
    protected $id;
    protected $private_note;
    protected $book_id;
    protected $user_id;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setPrivateNote($private_note)
    {
        $this->private_note = $private_note;
    }

    public function setBookId($book_id)
    {
        $this->book_id = $book_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPrivateNote()
    {
        return $this->private_note;
    }

    public function getBookId()
    {
        return $this->book_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function store()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare(
            'INSERT INTO `private_notes`
            ( `private_note`, `book_id`, `user_id`)
            VALUES ( :private_note, :book_id, :user_id)'
        );

        $data = [
            'private_note' => $this->private_note,
            'book_id' => $this->book_id,
            'user_id' => $this->user_id
        ];

        $statement->execute($data);

        $connectionObj->destroy();
    }

    public static function getAllByBookId($bookId)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('SELECT * FROM private_notes WHERE book_id = :book_id');
        $statement->bindParam(':book_id', $bookId, \PDO::PARAM_INT);
        $statement->execute();

        $privateNotes = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $connectionObj->destroy();

        return $privateNotes;
    }

    public static function getAllByBookIdAndUserId($bookId, $userId)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('SELECT * FROM private_notes WHERE book_id = :book_id AND user_id = :user_id');
        $statement->bindParam(':book_id', $bookId, \PDO::PARAM_INT);
        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $statement->execute();

        $privateNotes = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $connectionObj->destroy();

        return $privateNotes;
    }

    public static function getById($id)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('SELECT * FROM private_notes WHERE id = :id');
        $statement->bindParam(':id', $id, \PDO::PARAM_STR);
        $statement->execute();

        $privateNote  = $statement->fetch(\PDO::FETCH_ASSOC);

        return $privateNote;
    }

    public static function deletePrivateNotesByBookId($bookId)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $deleteStatement = $connection->prepare('DELETE FROM private_notes WHERE book_id = :book_id');
        $deleteStatement->bindParam(':book_id', $bookId, \PDO::PARAM_INT);
        $deleteStatement->execute();

        $connectionObj->destroy();
    }

    public function update()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('UPDATE private_notes SET private_note = :private_note WHERE id = :id');
        $statement->bindParam(':private_note', $this->private_note, \PDO::PARAM_STR);
        $statement->bindParam(':id', $this->id, \PDO::PARAM_INT);
        $statement->execute();

        $connectionObj->destroy();
    }

    public function delete()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('DELETE FROM private_notes WHERE id = :id');
        $statement->bindParam(':id', $this->id, \PDO::PARAM_INT);
        $statement->execute();

        $connectionObj->destroy();
    }
}
