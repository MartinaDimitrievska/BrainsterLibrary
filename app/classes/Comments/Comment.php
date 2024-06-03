<?php

namespace Comments;

require_once(__DIR__ . '/../Database/Connection.php');

use Database\Connection as Connection;

class Comment
{
    protected $id;
    protected $comment;
    protected $is_deleted = 0;
    protected $is_approved = 0;
    protected $book_id;
    protected $user_id;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function setIsDeleted($is_deleted)
    {
        $this->is_deleted = $is_deleted;
    }

    public function setIsApproved($is_approved)
    {
        $this->is_approved = $is_approved;
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

    public function getComment()
    {
        return $this->comment;
    }

    public function getIsDeleted()
    {
        return $this->is_deleted;
    }

    public function getIsApproved()
    {
        return $this->is_approved;
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
            'INSERT INTO `comments`
            ( `comment`, `is_deleted`, `is_approved`, `book_id`, `user_id`)
            VALUES ( :comment, :is_deleted, :is_approved, :book_id, :user_id )'
        );

        $data = [
            'comment' => $this->comment,
            'is_deleted' => $this->is_deleted,
            'is_approved' => $this->is_approved,
            'book_id' => $this->book_id,
            'user_id' => $this->user_id
        ];

        $statement->execute($data);

        $connectionObj->destroy();
    }

    public static function getAllComments()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $query = "
        SELECT comments.*, comments.comment AS comment
        FROM comments
        LEFT JOIN books ON comments.book_id = books.id
        LEFT JOIN users ON comments.user_id = users.id
        WHERE comments.is_deleted = 0 AND comments.is_approved = 1
        ";

        $statement = $connection->query($query);
        $comments = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $connectionObj->destroy();

        return $comments;
    }

    public static function getById($bookId, $userId = null)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $query = "
            SELECT comments.*, users.username,
            CASE
                WHEN comments.is_approved = 1 THEN 'approved'
                WHEN comments.is_approved = 0 AND comments.user_id = :user_id THEN 'pending'
                ELSE 'hidden'
            END AS display_status
            FROM comments
            JOIN users ON comments.user_id = users.id
            WHERE comments.book_id = :book_id AND (comments.is_approved = 1 OR (comments.is_approved = 0 AND comments.user_id = :user_id))
        ";

        $data = [
            'book_id' => $bookId,
            'user_id' => $userId,
        ];

        $statement = $connection->prepare($query);
        $statement->execute($data);

        $comment = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $comment;
    }

    public static function userHasCommented($user_id, $book_id)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare('
            SELECT COUNT(*) as count
            FROM comments
            WHERE user_id = :user_id AND book_id = :book_id AND is_deleted = 0
        ');

        $statement->bindParam(':user_id', $user_id, \PDO::PARAM_STR);
        $statement->bindParam(':book_id', $book_id, \PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        $connectionObj->destroy();

        return $result['count'] > 0;
    }

    public static function getUnapprovedComments()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare(
            'SELECT comments.*, users.username
            FROM comments
            JOIN users ON comments.user_id = users.id
            WHERE comments.is_approved = 0 AND comments.is_deleted = 0'
        );

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function approveComment($commentId)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare(
            'UPDATE comments
            SET is_approved = 1,
                is_deleted = 0
            WHERE id = :comment_id'
        );

        $statement->execute(['comment_id' => $commentId]);
    }

    public static function rejectComment($commentId)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare(
            'UPDATE comments
            SET is_approved = 0,
                is_deleted = 1
            WHERE id = :comment_id'
        );

        $statement->execute(['comment_id' => $commentId]);
    }

    public static function getAllCommentsForUserAndBook($userId, $bookId)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $query = "
            SELECT comments.*, comments.comment AS comment, users.username
            FROM comments
            LEFT JOIN books ON comments.book_id = books.id
            LEFT JOIN users ON comments.user_id = users.id
            WHERE (comments.is_deleted = 0 AND comments.is_approved = 1 AND comments.book_id = :book_id)
                OR (comments.is_deleted = 0 AND comments.is_approved = 0 AND comments.user_id = :user_id AND comments.book_id = :book_id)
        ";

        $statement = $connection->prepare($query);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $statement->bindValue(':book_id', $bookId, \PDO::PARAM_INT);
        $statement->execute();

        $comments = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $connectionObj->destroy();

        return $comments;
    }

    public static function getApprovedComments()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare(
            'SELECT comments.*, users.username
            FROM comments
            JOIN users ON comments.user_id = users.id
            WHERE comments.is_approved = 1'
        );

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getRejectedComments()
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare(
            'SELECT comments.*, users.username
            FROM comments
            JOIN users ON comments.user_id = users.id
            WHERE comments.is_approved = 0 AND comments.is_deleted = 1'
        );

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function approveRejectedComment($commentId)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $statement = $connection->prepare(
            'UPDATE comments SET is_approved = 1, is_deleted = 0 WHERE id = :comment_id'
        );

        $statement->bindParam(':comment_id', $commentId, \PDO::PARAM_INT);
        $statement->execute();
    }

    public static function deleteCommentsByBookId($bookId)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $deleteStatement = $connection->prepare('DELETE FROM comments WHERE book_id = :book_id');
        $deleteStatement->bindParam(':book_id', $bookId, \PDO::PARAM_INT);
        $deleteStatement->execute();

        $connectionObj->destroy();
    }

    public static function deleteComment($commentId)
    {
        $connectionObj = new Connection();
        $connection = $connectionObj->getPdo();

        $deleteStatement = $connection->prepare('DELETE FROM comments WHERE id = :comment_id');
        $deleteStatement->bindParam(':comment_id', $commentId, \PDO::PARAM_INT);
        $success = $deleteStatement->execute();

        $connectionObj->destroy();

        return $success;
    }
}
