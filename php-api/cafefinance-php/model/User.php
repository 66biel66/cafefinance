<?php

class User
{
    public function __construct(private PDO $db)
    {
    }

    public function all(): array
    {
        $statement = $this->db->query('SELECT id, name, email FROM users ORDER BY id');

        return $statement->fetchAll();
    }
}
