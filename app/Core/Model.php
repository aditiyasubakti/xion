<?php
namespace App\Core;

class Model
{
    protected $table;
    protected $conn;

    public function __construct()
    {
        $this->conn = db();
    }

    public function all()
    {
        $query = "SELECT * FROM {$this->table}";
        $result = $this->conn->query($query);

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function find($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function where($column, $value)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE $column = ?");
        $stmt->bind_param("s", $value);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function insert($data)
{
    $columns = implode(",", array_keys($data));
    $placeholders = implode(",", array_fill(0, count($data), "?"));

    $stmt = $this->conn->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");

    $values = array_values($data);
    $types = str_repeat("s", count($values));

    $stmt->bind_param($types, ...$values);
    return $stmt->execute();
}

public function updateData($id, $data)
{
    $set = implode(" = ?, ", array_keys($data)) . " = ?";
    $stmt = $this->conn->prepare("UPDATE {$this->table} SET $set WHERE id = ?");

    $values = array_values($data);
    $types  = str_repeat("s", count($values)) . "i";

    $values[] = $id;

    $stmt->bind_param($types, ...$values);
    return $stmt->execute();
}

public function delete($id)
{
    $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

}
