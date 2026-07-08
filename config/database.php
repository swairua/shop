<?php
class Database {
    private static $instance = null;
    private $connection;

    private $host = '127.0.0.1:3308';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'shop';

    private function __construct() {
        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($this->connection->connect_error) {
            die("Database connection failed: " . $this->connection->connect_error);
        }
        $this->connection->set_charset("utf8mb4");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }

    public function query($sql) {
        return $this->connection->query($sql);
    }

    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }

    public function insertId() {
        return $this->connection->insert_id;
    }

    public function affectedRows() {
        return $this->connection->affected_rows;
    }

    public function error() {
        return $this->connection->error;
    }

    public function beginTransaction() {
        $this->connection->begin_transaction();
    }

    public function commit() {
        $this->connection->commit();
    }

    public function rollback() {
        $this->connection->rollback();
    }
}
