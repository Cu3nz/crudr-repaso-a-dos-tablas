<?php

namespace App\Db;

use PDO;

require_once __DIR__ . "/../../vendor/autoload.php";

class Articulos extends Conexion
{


    private int $id;
    private string $nombre;
    private string $disponible;
    private float $precio;
    private string $imagen;
    private int $category_id;


    public function __construct()
    {

        parent::__construct();
    }





    //? --------------------------------------- CRUD --------------------------------------

    public function create()
    {

        $q = "INSERT INTO articulos (nombre, disponible , precio , imagen, category_id ) values (:n , :d , :p , :i , :c)";

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':d' => $this->disponible,
                ':p' => $this->precio,
                ':i' => $this->imagen,
                ':c' => $this->category_id,
            ]);
        } catch (\PDOException $ex) {
            die("Error en el metodo create mensaje  " . $ex->getMessage());
        }
    }



    public static function read()
    {

        parent::setConexion();

        //todo cogemos todos los atributos de la tabla articulos y de la tabla categorias nombre, en el where tenemos que igualar la categoria id que sea igual a la id de la tabla categorias

        //! Necesitamos poner un alias para cuando llamemos en el index $item -> nomCat.

        $q = "SELECT articulos. * , categorias.nombre as nomCat from categorias , articulos  where category_id = categorias.id order by articulos.id desc";

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute();
        } catch (\PDOException $ex) {
            die("Error en el metodo read de articulos " . $ex->getMessage());
        }

        parent::$conexion = null;

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }




    public static function detalle($id)
    {
        parent::setConexion();

        $q = "SELECT articulos.* , categorias.nombre as nomCat FROM  categorias , articulos where articulos.id = :i";

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute([':i' => $id]);
        } catch (\PDOException $ex) {
            die("Error en el metodo detalle " . $ex->getMessage());
        }
        parent::$conexion = null;

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function delete($idPOST)
    {

        parent::setConexion();

        $q = "DELETE FROM articulos where id = :i";

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute([':i' => $idPOST]);
        } catch (\PDOException $ex) {
            die("Error en el metodo delete " . $ex->getMessage());
        }

        parent::$conexion = null;
    }



    public  function update ($idGet){
        parent::setConexion();

        $q = "UPDATE articulos SET nombre = :n, disponible = :d, precio = :p, imagen = :i, category_id = :c WHERE id = :id";

        $stmt = parent::$conexion -> prepare($q);

        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':d' => $this->disponible,
                ':p' => $this->precio,
                ':i' => $this->imagen,
                ':c' => $this->category_id,
                ':id' => $idGet
            ]);

        } catch (\PDOException $ex) {
            die("Error en el metodo update " . $ex -> getMessage());
        }
    }






    //? --------------------------------------- FAKER --------------------------------------

    public static function hayRegistros()
    {

        parent::setConexion();

        $q = "SELECT * FROM articulos";

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute();
        } catch (\PDOException $ex) {
            die("Error en el metodo hayRegistros" . $ex->getMessage());
        }

        parent::$conexion = null;

        return $stmt->rowCount(); //* Devuelve false si no hay registros, true devuelve si hay registros
    }




    public static function generarArticulos($cantidad)
    {

        if (self::hayRegistros()) return;

        $faker = \Faker\Factory::create("es_ES");
        $faker->addProvider(new \Mmo\Faker\PicsumProvider($faker));


        for ($i = 0; $i < $cantidad; $i++) {

            $nombre = $faker->unique()->word();

            $disponible = $faker->randomElement(['SI', 'NO']);

            $precio = $faker->randomFloat(2, 5, 200);

            $imagen  = "img/" . $faker->picsum(dir: "./img", width: 640, height: 480, fullPath: false);

            $category_id = $faker->randomElement(Categorias::devolverIdCategoria())->id; //* Para sacar un id aleatoria para cada articulo

            (new Articulos)
                ->setNombre($nombre)
                ->setDisponible($disponible)
                ->setPrecio($precio)
                ->setImagen($imagen)
                ->setCategoryId($category_id)
                ->create();
        }
    }



    //? --------------------------------------- OTROS METODOS --------------------------------------


    //todo metodo que valida si hay un articulo con el mismo nombre o no 

    public static function nombreArticuloRepetido ($nombre , $id = null){

        parent::setConexion();

        $q = ($id == null) ? "SELECT nombre from articulos where nombre = :n" : "SELECT nombre from articulos where nombre = :n AND id != :i";

        $stmt = parent::$conexion -> prepare($q);

        $options = ($id == null) ? [':n' => $nombre] : [':n' => $nombre , ':i' => $id];

        try {
            $stmt -> execute($options);
        } catch (\PDOException $ex) {
            die("Error en el metodo nombreArticuloRepetido" . $ex -> getMessage());
        }

        parent::$conexion = null;

        return $stmt -> rowCount(); //* Si devuelve true es porque ya hay un articulo con ese nombre por lo tanto tendra que da un error, si devuelve false, se podra crear el articulo, ya que no existe un nombre igual en la base de datos.

    }


    //? --------------------------------------- SETTERS --------------------------------------




    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of disponible
     */
    public function setDisponible(string $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * Set the value of precio
     */
    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Set the value of imagen
     */
    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Set the value of category_id
     */
    public function setCategoryId(int $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }
}
