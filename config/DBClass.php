<?php
class DBClass {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "e_commerce";
    private $port = 3306;
    private $dbh;    private $stmt;

    public function __construct() {
        try {
            $this->dbh = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->database", $this->user, $this->password);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->dbh;
        } catch (PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }

    public function connect() {
        if (!$this->dbh) {
            $this->__construct();
        }
        return $this->dbh;
    }

    public function query($sql, $data = []) {
        try {
            $this->stmt = $this->dbh->prepare($sql);
            return $this->stmt->execute($data);
        } catch (PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }

    public function insert($table, $data) {
        $keys = array_keys($data);
        $columns = implode(", ", $keys);
        $placeholders = ":" . implode(", :", $keys);
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        try {
            $this->query($sql, $data);
            return $this->dbh->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Insert failed: " . $e->getMessage());
        }
    }


    // Add this method to your DBClass
    public function select($table, $conditions = [], $columns = '*') {
        $where = '';
        $params = [];
        
        if (!empty($conditions)) {
            $where = ' WHERE ';
            $conditionsParts = [];
            foreach ($conditions as $key => $value) {
                $conditionsParts[] = "$key = :$key";
                $params[":$key"] = $value;
            }
            $where .= implode(' AND ', $conditionsParts);
        }
        
        $sql = "SELECT $columns FROM $table $where";
        $this->query($sql, $params);
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add these methods to your DBClass class

    public function update($table, $data, $conditions) {
        $setParts = [];
        $whereParts = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            $setParts[] = "$key = :set_$key";
            $params[":set_$key"] = $value;
        }
        
        foreach ($conditions as $key => $value) {
            $whereParts[] = "$key = :where_$key";
            $params[":where_$key"] = $value;
        }
        
        $sql = "UPDATE $table SET " . implode(', ', $setParts) . 
            " WHERE " . implode(' AND ', $whereParts);
        
        return $this->query($sql, $params);
    }

    public function delete($table, $conditions) {
        $whereParts = [];
        $params = [];
        
        foreach ($conditions as $key => $value) {
            $whereParts[] = "$key = :$key";
            $params[":$key"] = $value;
        }
        
        $sql = "DELETE FROM $table WHERE " . implode(' AND ', $whereParts);
        return $this->query($sql, $params);
    }
}

$dbclassinstance = new DBClass();
$conn = $dbclassinstance->connect();