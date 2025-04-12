<?php

class Database
{
    private $pdo;

    public function __construct($path)
    {
        $this->pdo = new PDO("sqlite:" . $path);
    }

    public function Execute($sql)
    {
        return $this->pdo->exec($sql);
    }

    public function Fetch($sql)
    {
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function Create($table, $data)
    {
        $keys = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $stmt = $this->pdo->prepare("INSERT INTO $table ($keys) VALUES ($placeholders)");
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function Read($table, $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function Update($table, $id, $data)
    {
        $fields = implode(", ", array_map(fn($k) => "$k = :$k", array_keys($data)));
        $data['id'] = $id;
        $stmt = $this->pdo->prepare("UPDATE $table SET $fields WHERE id = :id");
        return $stmt->execute($data);
    }

    public function Delete($table, $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM $table WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function Count($table)
    {
        $result = $this->pdo->query("SELECT COUNT(*) as count FROM $table")->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}
