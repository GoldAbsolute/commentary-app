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

}