<?php

class Controller
{
    private $host     = "host";
    private $username = "dbuser";
    private $password = "dbpass";
    private $database = "dbname";

    protected $connection;

    public function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erro de conexão: " . $e->getMessage();
            exit;
        }
    }

    public function insert($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        $query = $this->connection->prepare($sql);
        $this->bindValues($query, $data);
        return $query->execute();
    }

    public function select($table, $columns = "*", $where = [], $orderBy = null)
    {
        $sql = "SELECT $columns FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE " . $this->buildWhereClause($where);
        }
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }
        $query = $this->connection->prepare($sql);
        $this->bindValues($query, $where);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($table, $data, $where = [])
    {
        $set = "";
        foreach ($data as $key => $value) {
            $set .= "$key=:$key, ";
        }
        $set = rtrim($set, ", ");

        $sql = "UPDATE $table SET $set";
        if (!empty($where)) {
            $sql .= " WHERE " . $this->buildWhereClause($where);
        }
        $query = $this->connection->prepare($sql);
        $this->bindValues($query, $data + $where);
        return $query->execute();
    }

    public function delete($table, $where = [])
    {
        $sql = "DELETE FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE " . $this->buildWhereClause($where);
        }
        $query = $this->connection->prepare($sql);
        $this->bindValues($query, $where);
        return $query->execute();
    }

    private function buildWhereClause($where)
    {
        $conditions = "";
        foreach ($where as $column => $value) {
            $conditions .= "$column=:$column AND ";
        }
        return rtrim($conditions, " AND ");
    }

    private function bindValues($query, $values)
    {
        foreach ($values as $key => $value) {
            $query->bindValue(":$key", $value);
        }
    }
}