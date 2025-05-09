<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'lost_found_portal';
    private $username = 'root';
    private $password = 'admin';
    private $conn;

    public function connect()
    {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}
