<?php

declare(strict_types=1);

namespace Comment;

use PDO;

class CommentMapper
{
    /**
     * @var PDO
     */
    private PDO $connection;

    /**
     * @param PDO $connection
     */
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * @return bool|array
     */
    public function apiGet(): bool|array
    {
        $statement = $this->connection->prepare('SELECT * FROM comments ORDER BY published_date DESC, published_time DESC');
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param string $name
     * @param string $text
     * @return bool
     */
    public function apiPost(string $name, string $text): bool
    {
        $published_date = date("Y-m-d", time());
        $published_time = date("H:i:s", time());

        $statement = $this->connection->prepare('INSERT INTO comments (name, text, published_time, published_date) VALUES (:name, :text, :published_time, :published_date)');
        $statement->bindParam(':name', $name);
        $statement->bindParam(':text', $text);
        $statement->bindParam(':published_time', $published_time);
        $statement->bindParam(':published_date', $published_date);
        $result = $statement->execute();
        return $result;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function apiDelete(string $id): bool
    {
        $statement = $this->connection->prepare('DELETE FROM comments WHERE id = :id');
        $statement->bindParam(':id', $id);
        $result = $statement->execute();
        return $result;
    }
}