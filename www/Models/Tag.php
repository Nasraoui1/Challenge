<?php

namespace App\Models;

use App\Core\SQL;

class Tag extends SQL
{
    public function countAll(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Tags");
        return (int) $stmt->fetchColumn();
    }
}
