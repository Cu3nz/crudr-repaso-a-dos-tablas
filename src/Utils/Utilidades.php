<?php 
namespace App\Utils;

use App\Db\Articulos;
use App\Db\Categorias;

const MAY_ON = 1;
const MAY_OFF = 0;


class Utilidades{

    public static function sanearCampos($campo , $modo = MAY_OFF){

        //? Si el modo es igual a mayuscula, ponemos la primera letra de la palabra en mayuscula, quitamos el html y demas que se pueda meter por el input quitamos los espacios en blanco del input que queramos sanear
        //! si no (:)
        //? Quitamos el html y demas que se pueda ejecutar en el input, quitamos los espacios del campo
        return ($modo == MAY_ON) ? ucfirst(htmlspecialchars(trim($campo))) : htmlspecialchars(trim($campo));
    }



    //todo funcion para validar campos de texto en este caso solamente nombre. 

    public static function errorCampoTexto($campo , $valor , $logitud){

        if (strlen($valor) < $logitud){
            $_SESSION[$campo] = "**** ERRROR el campo $campo tiene que tener al menos $logitud de caracteres";
            return true; //! hay error
        }

        return false; //* No hay errores

    }


    //todo funcion para validar el campo de precio

    public static function errorCampoPrecio($campo , $valor , $min , $max){

        if ($valor < $min || $valor > $max ){
            $_SESSION[$campo] = "**** Error , el precio tiene que estar entre el rango $min y $max";
            return true; //! hay error
        }

        return false; //* No hay error.

    }



    //todo funcion que valida que se haya seleccionado una categoria valida, que este almacenada en la base de datos.

    public static function errorIdCategoria ($idCategoria){

        if (Categorias::existeidCategorias($idCategoria)){
            return true; 
        }
        $_SESSION['id_categoria'] = "Error, la categoria no es valido o no selecciono ninguna";
        return false;
    }



    //todo funcion que valida si el nombre esta repetido o no 

    public static function errorNombreRepetido($campo , $nombre , $id = null){

        if (Articulos::nombreArticuloRepetido($nombre , $id)){
            $_SESSION[$campo] = "Error, el nombre $nombre ya existe, no puedes crear dos articulos con el mismo nombre";
            return true; //! hay error.
        }
        return false; //* No hay error 

    }


    //todo Imagen 

    public static array $tiposMime = [
        'image/gif', 'image/png' ,'image/jpeg', 'image/bmp', 'image/webp'
    ];


    public static function errorTipoFotoYSize($tipo, $size){

        if (!in_array($tipo , self::$tiposMime)){
            $_SESSION['Imagen'] = "Error la imagen que has subido no es una imagen o no tiene una extension valida";
            return true;
        }

        if ($size > 200000){
            $_SESSION['Imagen'] = "Error la imagen no puede superar los 2MB";
            return true;
        }
        return false;
    }


    public static function mostrarErrores($error)
    {

        if (isset($_SESSION[$error])) {
            echo "<p class='italic text-red-600 mt-2'>{$_SESSION[$error]}</p>";
            unset($_SESSION[$error]);
        }
    }


}


    

?>