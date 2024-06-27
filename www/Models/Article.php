<?php
namespace App\Models;

use App\Core\SQL;

class Article extends SQL
{
    public function countAll(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Articles");
        return (int) $stmt->fetchColumn();
    }

    public function countByStatus(string $status): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM Articles WHERE status = :status");
        $stmt->execute(['status' => $status]);
        return (int) $stmt->fetchColumn();
    }

    public function getRecent(int $limit): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Articles ORDER BY published_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
