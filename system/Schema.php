<?php
namespace Core;

class TableBlueprint
{
    public $fields = [];

    public function id()
    {
        $this->fields['id'] = "INT AUTO_INCREMENT PRIMARY KEY";
    }

    public function string($name, $length = 255)
    {
        $this->fields[$name] = "VARCHAR($length)";
        return $this;
    }

    public function text($name)
    {
        $this->fields[$name] = "TEXT";
        return $this;
    }

    public function integer($name)
    {
        $this->fields[$name] = "INT";
        return $this;
    }

  public function unique($name = null)
{
    // Jika unique() dipanggil tanpa parameter → ambil kolom terakhir
    if ($name === null) {

        // Ambil key kolom terakhir
        end($this->fields);
        $name = key($this->fields);

        // Kalau tetap null
        if ($name === null) {
            throw new \Exception("unique() dipanggil tapi tidak ada kolom.");
        }
    }

    // Tambahkan UNIQUE pada definisi kolom
    $this->fields[$name] .= " UNIQUE";
    return $this;
}


    // ============================
    // FOREIGN KEY BUILDER
    // ============================
    public function foreign($column, $ref, $on)
    {
        $this->fields["_fk_$column"] = [
            "column" => $column,
            "ref"    => $ref,
            "on"     => $on
        ];
    }

    public function timestamps()
    {
        $this->fields["created_at"] = "DATETIME";
        $this->fields["updated_at"] = "DATETIME";
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
        $foreignKeys = [];

        foreach ($blue->fields as $col => $type) {

            if (is_array($type)) {
                // Handle Foreign Key
                $foreignKeys[] =
                    "FOREIGN KEY (`{$type['column']}`) REFERENCES {$type['on']}({$type['ref']}) 
                     ON DELETE CASCADE ON UPDATE CASCADE";
            } else {
                $columns[] = "`$col` $type";
            }
        }

        if (!empty($foreignKeys)) {
            $columns = array_merge($columns, $foreignKeys);
        }

        $sql = "CREATE TABLE IF NOT EXISTS `$table` (" . implode(",", $columns) . ")";

        return $this->conn->query($sql);
    }
}