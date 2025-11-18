<?php
namespace Core;

class TableBlueprint
{
    public $fields = [];
    public $foreignKeys = [];

    private $lastColumn = null;
    private $pendingFK = [
        'column' => null,
        'refColumn' => null,
        'refTable' => null,
        'onUpdate' => "CASCADE",
        'onDelete' => "CASCADE"
    ];

    public function id()
    {
        $this->fields['id'] = "INT UNSIGNED AUTO_INCREMENT PRIMARY KEY";
        $this->lastColumn = 'id';
        return $this;
    }

    public function string($name, $length = 255)
    {
        $len = (int)$length;
        $this->fields[$name] = "VARCHAR($len)";
        $this->lastColumn = $name;
        return $this;
    }

    public function text($name)
    {
        $this->fields[$name] = "TEXT";
        $this->lastColumn = $name;
        return $this;
    }

    /**
     * integer($name, $unsigned = true, $nullable = false)
     * - $unsigned true -> add UNSIGNED
     * - $nullable false -> add NOT NULL
     */
    public function integer($name, $unsigned = true, $nullable = false)
    {
        $type = "INT";

        if ($unsigned) $type .= " UNSIGNED";
        if (!$nullable) $type .= " NOT NULL";

        $this->fields[$name] = $type;
        $this->lastColumn = $name;

        // prepare pending FK for chain methods
        $this->pendingFK = [
            'column' => $name,
            'refColumn' => null,
            'refTable' => null,
            'onUpdate' => "CASCADE",
            'onDelete' => "CASCADE"
        ];

        return $this;
    }

    public function unique($name = null)
    {
        if ($name === null) $name = $this->lastColumn;
        $this->fields[$name] .= " UNIQUE";
        return $this;
    }

    // chain: references()->on()->onUpdateDelete()
    public function references($refColumn)
    {
        $this->pendingFK['refColumn'] = $refColumn;
        return $this;
    }

    public function on($table)
    {
        $this->pendingFK['refTable'] = $table;
        return $this;
    }

    public function onUpdateDelete($update = "CASCADE", $delete = "CASCADE")
    {
        $this->pendingFK['onUpdate'] = strtoupper($update);
        $this->pendingFK['onDelete'] = strtoupper($delete);

        // push final FK to list
        $this->foreignKeys[] = $this->pendingFK;

        // reset pendingFK to defaults
        $this->pendingFK = [
            'column' => null,
            'refColumn' => null,
            'refTable' => null,
            'onUpdate' => "CASCADE",
            'onDelete' => "CASCADE"
        ];

        return $this;
    }

    // fallback (old style)
    public function foreign($column, $refColumn, $refTable)
    {
        $this->foreignKeys[] = [
            'column'    => $column,
            'refColumn' => $refColumn,
            'refTable'  => $refTable,
            'onUpdate'  => 'CASCADE',
            'onDelete'  => 'CASCADE'
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

    private $collectFK = false;
    // $fkJobs will be bound by reference to the external collector array
    private $fkJobs = null;

    public function startCollectingFK(&$list)
    {
        $this->collectFK = true;
        // bind property to external array by reference so pushes affect external var
        $this->fkJobs = &$list;
    }

    public function stopCollectingFK()
    {
        // stop collecting; DO NOT reset $this->fkJobs because it's a reference to external array
        $this->collectFK = false;
    }

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Create table (only columns). Foreign keys will be collected (if collecting enabled)
     * and executed later by ALTER TABLE in phase 2.
     */
    
    public function create($table, callable $callback)
    {
        $blue = new TableBlueprint();
        $callback($blue);

        $columns = [];

        foreach ($blue->fields as $col => $type) {
            $columns[] = "`$col` $type";
        }

        // collect foreign keys for phase 2 (do not include foreign key definition
        
        // inside CREATE TABLE to avoid dependency on table order)
        foreach ($blue->foreignKeys as $fk) {

            // validation: must have refTable and refColumn
            if (empty($fk['refTable']) || empty($fk['refColumn']) || empty($fk['column'])) {
                throw new \Exception("Error: Foreign key on `$table` is incomplete (column/refTable/refColumn required).");
            }

            if ($this->collectFK) {
                // ensure external collector exists as array
                if (!is_array($this->fkJobs)) {
                    // initialize external array if needed
                    $this->fkJobs = [];
                }
                $this->fkJobs[] = [
                    'table'     => $table,
                    'column'    => $fk['column'],
                    'refTable'  => $fk['refTable'],
                    'refColumn' => $fk['refColumn'],
                    'onUpdate'  => $fk['onUpdate'] ?? 'CASCADE',
                    'onDelete'  => $fk['onDelete'] ?? 'CASCADE'
                ];
            }
        }

        // build create table SQL (columns only)
        $sql = "CREATE TABLE IF NOT EXISTS `$table` (\n"
             . implode(",\n", $columns)
             . "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $res = $this->conn->query($sql);
        if ($res === false) {
            $err = $this->conn->error ?? "unknown error";
            throw new \Exception("Failed creating table `$table`: $err");
        }
        return $res;
        
    }
   public function foreignKeyExists($table, $column)
        {
            $table = $this->conn->real_escape_string($table);
            $column = $this->conn->real_escape_string($column);

            $sql = "SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE()
                    AND TABLE_NAME = '$table'
                    AND COLUMN_NAME = '$column'
                    AND REFERENCED_TABLE_NAME IS NOT NULL";

            $res = $this->conn->query($sql);
            return $res && $res->num_rows > 0;
        }


}
