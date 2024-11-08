<?php

class Controller
{
    private string $host     = "host";
    private string $username = "dbuser";
    private string $password = "dbpass";
    private string $database = "dbname";

    protected PDO $connection;

    public function __construct()
    {
        try {
            $this->connection = new PDO(
                dsn: "mysql:host={$this->host};dbname={$this->database}",
                username: $this->username,
                password: $this->password,
                options: [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            throw new Exception("Connection error: " . $e->getMessage());
        }
    }
    public function insert(string $table, array $data)
    {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));
            $sql = "INSERT INTO $table ($columns) VALUES ($values)";
            $query = $this->connection->prepare($sql);
            $this->bindValues($query, $data);
            return $query->execute();
        } catch (PDOException $e) {
            return $this->errorAtempt(500, "Erro ao inserir registro: " . $e->getMessage());
        }
    }
    public function select(string $table, string $columns = "*", array $where = [], string $orderBy = null)
    {
        try {
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
        } catch (PDOException $e) {
            return $this->errorAtempt(500, "Erro ao buscar registro: " . $e->getMessage());
        }
    }
    public function update(string $table, array $data, array $where = [])
    {
        try {
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
        } catch (PDOException $e) {
            return $this->errorAtempt(500, "Erro ao atualizar registro: " . $e->getMessage());
        }
    }
    public function delete(string $table, array $where = [])
    {
        try {
            $sql = "DELETE FROM $table";
            if (!empty($where)) {
                $sql .= " WHERE " . $this->buildWhereClause($where);
            }
            $query = $this->connection->prepare($sql);
            $this->bindValues($query, $where);
            return $query->execute();
        } catch (PDOException $e) {
            return $this->errorAtempt(500, "Erro ao deletar registro: " . $e->getMessage());
        }
    }
    private function buildWhereClause(array $where)
    {
        $conditions = "";
        foreach ($where as $column => $value) {
            $conditions .= "$column=:$column AND ";
        }
        return rtrim($conditions, " AND ");
    }
    private function bindValues($query, array $values)
    {
        foreach ($values as $key => $value) {
            $query->bindValue(":$key", $value);
        }
    }
    public function errorAtempt($code, $message)
    {
        return $this->response($code, $message);
    }
    public function response(int $status, string $message, array $data = [], $type = false)
    {
        switch ($type) {
            case true:
                header('Content-Type: application/json');
                echo json_encode(["status" => $status, "message" => $message, "data" => $data], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                break;
            default:
                header('Content-Type: application/json');
                echo json_encode(["status" => $status, "message" => $message, "data" => $data], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }
}
