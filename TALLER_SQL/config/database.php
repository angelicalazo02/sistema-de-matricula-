<?php
/**
 * Clase Database - Conexión PDO a MySQL (XAMPP)
 *
 * Principio DCL: cada perfil se conecta con SU usuario de MySQL.
 * Los permisos los controla MySQL (sql/3DCL.sql), no el código PHP.
 */
class Database {
    // ---- Ajustes de XAMPP (cambie solo si su instalación es distinta) ----
    const HOST    = "localhost";
    const PORT    = 3306;          // Puerto por defecto de XAMPP
    const DB_NAME = "universidad";

    // Usuarios de MySQL creados en sql/3DCL.sql
    const PERFILES = [
        "auth"       => ["usuario" => "auth",       "clave" => "auth123"],
        "secretario" => ["usuario" => "secretario", "clave" => "sec123"],
        "asistente"  => ["usuario" => "asistente",  "clave" => "asi123"],
    ];

    private $username;
    private $password;
    public  $conn;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Crea la conexión usando el usuario de MySQL asociado a un perfil.
     * Ejemplo: Database::conectar("asistente")
     */
    public static function conectar($perfil) {
        if (!isset(self::PERFILES[$perfil])) {
            throw new InvalidArgumentException("Perfil no válido: $perfil");
        }
        $cred = self::PERFILES[$perfil];
        return (new Database($cred["usuario"], $cred["clave"]))->getConnection();
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "mysql:host=" . self::HOST . ";port=" . self::PORT
                 . ";dbname=" . self::DB_NAME . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos. Verifique que MySQL esté iniciado en XAMPP. Detalle: "
                . htmlspecialchars($e->getMessage()));
        }
        return $this->conn;
    }
}
