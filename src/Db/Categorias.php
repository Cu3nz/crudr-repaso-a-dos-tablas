<?php


namespace App\Db;

use PDO;

class Categorias extends Conexion
{


    private int $id;
    private string $nombre;
    private string $descripcion;


    public function __construct()
    {

        parent::__construct();
    }





    //? --------------------------------------- CRUD --------------------------------------


    public function create()
    {

        $q = "INSERT INTO categorias (nombre, descripcion) values (:n , :d)";

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':d' => $this->descripcion
            ]);
        } catch (\PDOException $ex) {
            die("Error en el metodo create " . $ex->getMessage());
        }
        parent::$conexion = null;
    }





    //? --------------------------------------- FAKER --------------------------------------

    public static function hayRegistros()
    {

        parent::setConexion();

        $q = "SELECT * FROM categorias";

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute();
        } catch (\PDOException $ex) {
            die("Error en el metodo hayRegistros" . $ex->getMessage());
        }

        parent::$conexion = null;

        return $stmt->rowCount(); //* Devuelve false si no hay registros, true devuelve si hay registros
    }




    public static function generarRegistros($cantidad)
    {

        if (self::hayRegistros()) return;

        $faker = \Faker\Factory::create("es_ES");

        for ($i = 0; $i < $cantidad; $i++) {

            $nombre = $faker->word();

            $descripcion = $faker->text();

            (new Categorias)
                ->setNombre($nombre)
                ->setDescripcion($descripcion)
                ->create();
        }
    }



    //? --------------------------------------- OTROS METODOS --------------------------------------



    //todo Devolver todas las ids generadas en un array. 

    public static function devolverIdCategoria()
    {
        parent::setConexion();

        $q = "SELECT id from categorias";

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute();
        } catch (\PDOException $ex) {
            die("Error en el metodo devolveridCategoria" . $ex->getMessage());
        }

        parent::$conexion = null;

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    //todo Metodo utilizado en la validaciones de create y update, para saber si se ha selecionado una categoria valida en los options
    public static function existeidCategorias($idCategoria)
    {

        parent::setConexion();

        $q = "select id from categorias where id=:i";

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute([':i' => $idCategoria]);
        } catch (\PDOException $ex) {
            die("error en existeidCategorias" . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->rowCount(); //* Devuelve una fila si existe el id devuelve true, si no existe devuelve false porque devuelve 0 filas

    }

    //todo Metodo para options a la hora de crear un articulo, super importante
    public static function read(): array{
        parent::setConexion();
        $q = "select * from categorias order by id desc";

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute();
        } catch (\PDOException $ex) {
            die("Error en el metodo read de Autores , error = " . $ex->getMessage());
        }

        parent::$conexion = null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    //? --------------------------------------- SETTERS --------------------------------------






    /**
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of descripcion
     */
    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }
}
