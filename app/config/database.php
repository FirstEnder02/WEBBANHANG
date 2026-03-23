<?php
class Database
{
    private $host = "localhost";
    private $db_name = "my_store";
    private $username = "root";
    private $password = "";
     private $port = "1024";
    public $conn;

    public function getConnection()
    {
        try {
            $conn = new PDO(
    "mysql:host=127.0.0.1;port=1024;dbname={$this->db_name};charset=utf8",
    $this->username,
    $this->password
);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn; // 🔥 QUAN TRỌNG
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}