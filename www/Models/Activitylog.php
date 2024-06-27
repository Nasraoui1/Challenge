<?php 

namespace App\Models;

use App\Core\SQL;

class ActivityLog extends SQL
{
    public function getRecent(int $limit): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM ActivityLog ORDER BY action_time DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
