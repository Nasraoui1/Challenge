<?php

namespace App\Models;

use App\Core\SQL;

class Category extends SQL
{
    public function countAll(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Categories");
        return (int) $stmt->fetchColumn();
    }
}
