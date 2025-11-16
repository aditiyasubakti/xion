<?php
namespace Core;

class TableBlueprint
{
    public $fields = [];
    public $foreignKeys = [];
    private $lastColumn = null;

    public function id()
    {
        $this->fields['id'] = "INT AUTO_INCREMENT PRIMARY KEY";
        $this->lastColumn = "id";
        return $this;
    }

    public function string($name, $length = 255)
    {
        $this->fields[$name] = "VARCHAR($length)";
        $this->lastColumn = $name;
        return $this;
    }

    public function text($name)
    {
        $this->fields[$name] = "TEXT";
        $this->lastColumn = $name;
        return $this;
    }

    public function integer($name)
    {
        $this->fields[$name] = "INT";
        $this->lastColumn = $name;
        return $this;
    }

    public function unique($name = null)
    {
        if ($name === null) {
            $name = $this->lastColumn;
        }

        if ($name === null) {
            throw new \Exception("unique() dipanggil tanpa kolom.");
        }

        $this->fields[$name] .= " UNIQUE";
        return $this;
    }

    public function foreign($column, $refColumn, $refTable)
    {
        $this->foreignKeys[] = [
            "column"   => $column,
            "refTable" => $refTable,
            "refColumn"=> $refColumn
        ];
        return $this;
    }

    public function timestamps()
    {
        $this->fields["created_at"] = "DATETIME";
        $this->fields["updated_at"] = "DATETIME";
        return $this;
    }
}


class Schema
{
    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function create($table, callable $callback)
    {
        $blue = new TableBlueprint();
        $callback($blue);

        $columns = [];

        foreach ($blue->fields as $col => $type) {
            $columns[] = "`$col` $type";
        }

        foreach ($blue->foreignKeys as $fk) {
            $columns[] =
                "FOREIGN KEY (`{$fk['column']}`) REFERENCES `{$fk['refTable']}`(`{$fk['refColumn']}`)
                 ON DELETE CASCADE 
                 ON UPDATE CASCADE";
        }

        $sql = "CREATE TABLE IF NOT EXISTS `$table` (" . implode(",", $columns) . ") ENGINE=InnoDB";

        return $this->conn->query($sql);
    }
}
