<?php

namespace App\Models;

use App\Core\SQL;

class Media extends SQL
{
    public function countAll(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Media");
        return (int) $stmt->fetchColumn();
    }

    public function getRecent(int $limit): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Media ORDER BY uploaded_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
