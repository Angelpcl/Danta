<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'danta11_dental';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_TIMEOUT => 5,
                    PDO::ATTR_PERSISTENT => false  // evita conexiones persistentes
                ]
            );
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            die("Error al conectar con la base de datos");
        }

        return $this->conn;
    }
}
