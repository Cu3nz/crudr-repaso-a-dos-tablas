<?php 
namespace App\Db;

use PDO;

class Conexion{

    protected static $conexion;

    public function __construct()
    {
        self::setConexion();
    }



    public static function setConexion(){

        if (self::$conexion != null) return;


        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../../");
        $dotenv->load();

        $user = $_ENV['USER'];
        $pass = $_ENV['PASS'];
        $host = $_ENV['HOST'];
        $db = $_ENV['DB'];

        $dsn = ("mysql:host=$host;dbname=$db;charset=utf8mb4");

        $option = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

        try {
            self::$conexion = new PDO($dsn , $user , $pass , $option);
        } catch (\PDOException $ex) {
            die("Error en la clase conexion" . $ex -> getMessage());
        }



    }





}

?>