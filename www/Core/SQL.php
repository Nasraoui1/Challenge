<?php

namespace App\Core;

use PDO;
use PDOException;

class SQL
{
    private static $instance = null; // Static instance to hold the database connection
    protected $pdo;
    protected $table;
    private $dbname = 'postgres';
    private $id;

    public function __construct()
    {
        $this->connect();
        try {
            $this->pdo = new PDO("pgsql:host=postgres;dbname=" . $this->dbname . ";port=5432", "postgres", "postgres");
        } catch (\Exception $e) {
            die("Erreur SQL : " . $e->getMessage());
        }

        $classChild = get_called_class();
        $this->table = "chall_" . strtolower(str_replace("App\\Models\\", "", $classChild));
    }

    private function connect()
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO("pgsql:host=postgres;dbname=" . $this->dbname . ";port=5432", "postgres", "postgres", [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (\PDOException $e) {
                die("Erreur SQL : " . $e->getMessage());
            }
        }
        $this->pdo = self::$instance;
    }

    public static function getConnection()
    {
        if (self::$instance === null) {
            (new self())->connect();
        }
        return self::$instance;
    }

    public function save()
    {
        $columnsAll = get_object_vars($this);
        $columnsToDelete = get_class_vars(get_class());
        $columns = array_diff_key($columnsAll, $columnsToDelete);

        if (empty($this->getId())) {
            unset($columns['id']);
            $sql = "INSERT INTO " . $this->table . " (" . implode(', ', array_keys($columns)) . ")  
            VALUES (:" . implode(',:', array_keys($columns)) . ")";
        } else {
            foreach ($columns as $column => $value) {
                $sqlUpdate[] = $column . "=:" . $column;
            }
            $sql = "UPDATE " . $this->table . " SET " . implode(', ', $sqlUpdate) . " WHERE id=" . $this->getId();
        }
        $queryPrepared = $this->pdo->prepare($sql);
        foreach ($columns as $key => $value) {
            $type = is_bool($value) ? PDO::PARAM_BOOL : (is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            $queryPrepared->bindValue(":$key", $value, $type);
        }
        $queryPrepared->execute($columns);

        if (isset($isUpdate)) {
            return $this->getId();
        }
        return $this->pdo->lastInsertId($this->table . "_id_seq");
    }

    public function login(string $email, string $password): array
    {
        $sql = "SELECT id, password, status FROM " . $this->table . " WHERE email = :email";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die('SQL Error: ' . $e->getMessage());
        }

        if ($user = $stmt->fetch()) {
            if (password_verify($password, $user['password'])) {
                if ($user['status'] == 1) {
                    $_SESSION['user_id'] = $user['id'];
                    return [
                        'success' => true,
                        'message' => 'Vous êtes connecté avec votre adresse email ' . $email
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Votre compte n\'est pas activé.'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Mot de passe incorrect.'
                ];
            }
        }
        return [
            'success' => false,
            'message' => 'Adresse email inconnue.'
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    // The remaining methods are adapted from the original file

    public function emailExists($email): bool {
        $sql = "SELECT COUNT(*) FROM " . $this->table . " WHERE email = :email";
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute([':email' => $email]);
        $number = $queryPrepared->fetchColumn();
        return $number > 0;
    }

    public function getOneBy(array $data, string $return = "array")
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE ";
        foreach ($data as $column => $value) {
            $sql .= " " . $column . "=:" . $column . " AND";
        }
        $sql = substr($sql, 0, -3);
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute($data);

        if ($return == "object") {
            $queryPrepared->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        } else {
            $queryPrepared->setFetchMode(PDO::FETCH_ASSOC);
        }
        return $queryPrepared->fetch(); // pour récupérer le résultat de la requête (un seul enregistrement)
    }

    public function checkUserCredentials(string $email, string $password): ?object
    {
        $user = $this->getOneBy(['email' => $email], 'object');
        if ($user && password_verify($password, $user->getPassword())) {
            return $user;
        }
        return null;
    }

    public function getAllData(string $return = "array")
    {
        $sql = "SELECT * FROM " . $this->table;
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute();

        if ($return == "object") {
            // les resultats seront sous forme d'objet de la classe appelée
            $queryPrepared->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        } else {
            // pour récupérer un tableau associatif
            $queryPrepared->setFetchMode(PDO::FETCH_ASSOC);
        }
        return $queryPrepared->fetchAll();
    }

    public function getAllDataWithWhere(array $whereClause = null, string $return = "array")
    {
        $sql = "SELECT * FROM " . $this->table;

        if ($whereClause) {
            $sql .= " WHERE ";
            $conditions = [];
            foreach ($whereClause as $column => $value) {
                $conditions[] = "$column = :$column";
            }
            $whereClauseString = implode(' AND ', $conditions);
            $sql .= $whereClauseString;
        }

        $queryPrepared = $this->pdo->prepare($sql);
        if ($whereClause) {
            $parameters = array_combine(array_keys($whereClause), array_values($whereClause)); // Assuming $whereClause is an associative array
            $queryPrepared->execute($parameters);
        } else {
            $queryPrepared->execute();
        }

        if ($return == "object") {
            $queryPrepared->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        } else {
            $queryPrepared->setFetchMode(PDO::FETCH_ASSOC);
        }
        return $queryPrepared->fetchAll();
    }

    public function getDataObject(): array
    {
        return array_diff_key(get_object_vars($this), get_class_vars(get_class())); //mettre dans un tableau les données de l'objet
    }

    public function setDataFromArray(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function delete(array $data)
    {
        $recordToDelete = $this->getOneBy($data);
        if (!$recordToDelete) {
            return false;
        }
        $sql = "DELETE FROM " . $this->table . " WHERE ";
        foreach ($data as $column => $value) {
            $sql .= " " . $column . "=:" . $column . " AND";
        }
        $sql = substr($sql, 0, -3);
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute($data);
        return $queryPrepared->rowCount() > 0;
    }

    public function countElements($typeColumn = null, $typeValue = null): int
    {
        if ($typeColumn && $typeValue) {
            $sql = "SELECT COUNT(*) FROM " . $this->table . " WHERE " . $typeColumn . " = :typeValue";
            $queryPrepared = $this->pdo->prepare($sql);
            $queryPrepared->execute(['typeValue' => $typeValue]);
        } else {
            $sql = "SELECT COUNT(*) FROM " . $this->table;
            $queryPrepared = $this->pdo->prepare($sql);
            $queryPrepared->execute();
        }

        return $queryPrepared->fetchColumn();
    }

    public function generateId($sequenceName)
    {
        $query = "SELECT nextval(:sequenceName)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':sequenceName', $sequenceName);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $generatedId = $result['nextval'];
        return $generatedId;
    }

    public function populate(int $id): object
    {
        $class = get_called_class();
        $object = new $class();
        return $object->getOneBy(["id" => $id], "object");
    }

    public function getAllDataGroupBy(array $where, array $groupBy = null, string $return = "array"): array
    {
        $sql = "SELECT ";
        $sql .= $groupBy['condition'];
        $sql .= " FROM " . $this->table;
        if ($where) {
            $sql .= " WHERE ";
            $conditions = [];
            foreach ($where as $column => $value) {
                $conditions[] = "$column = :$column";
            }
            $whereClauseString = implode(' AND ', $conditions);
            $sql .= $whereClauseString;
        }
        $sql .= " GROUP BY " . $groupBy['name'];
        $sql .= " ORDER BY " . $groupBy['name'] . ' ASC';

        $queryPrepared = $this->pdo->prepare($sql);

        if ($where) {
            $parameters = array_combine(array_keys($where), array_values($where)); // Assuming $whereClause is an associative array
            $queryPrepared->execute($parameters);
        } else {
            $queryPrepared->execute();
        }

        if ($return == "object") {
            $queryPrepared->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        } else {
            $queryPrepared->setFetchMode(PDO::FETCH_ASSOC);
        }

        return $queryPrepared->fetchAll();
    }
}
