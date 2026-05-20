<?php

class HealthController
{
    public function __construct(private PDO $db)
    {
    }

    public function index(): array
    {
        $this->db->query('SELECT 1');

        return [
            'status' => 'ok',
            'database' => 'connected',
        ];
    }
}
