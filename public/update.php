<?php

use App\Db\Articulos;
use App\Db\Categorias;
use App\Utils\Utilidades;

use const App\Utils\MAY_ON;

require_once __DIR__ . "/../vendor/autoload.php";  //? Esto es imporatente siempre se tiene que poner cuando se va a llamar a otra clase del proyecto
session_start();

$articulos = Categorias::read();
$idArticuloGet = $_GET['id'];

$articulo = Articulos::detalle($idArticuloGet); //* Para mostrar la imagen que hay actualmente y para el el action del formulario y para borrar la imagen anterior del articulo y poner la nueva que se ha subido.


/* echo $idArticuloGet; */


if (isset($_POST['btn'])) {
    //*procesamos los datos 

    $nombre = Utilidades::sanearCampos($_POST['nombre'], MAY_ON);
    $disponible = isset($_POST['disponible']) ? "SI" : "NO"; //? Si he hecho click pongo si, si no, pongo el valor de NO.
    $precio = (int) trim($_POST['precio']);
    $categoria_id = Utilidades::sanearCampos($_POST['categoria'], MAY_ON);

    //?validamos los datos

    $errores = false;


    if (Utilidades::errorCampoTexto("nombre", $nombre, 5)) {
        /* die("Error en nombre"); */
        $errores = true;
    }

    if (Utilidades::errorCampoPrecio("precio", $precio, 5, 200)) {
        /* die("Error en precio "); */
        $errores = true;
    }


    if (!Utilidades::errorIdCategoria($categoria_id)) {
        $errores = true;
    }


    if (Utilidades::errorNombreRepetido("nombre", $nombre , $idArticuloGet)) {
        /* die("Error en repetido "); */
        $errores = true;
    }


    if (!$errores) { //* Si no hay errores me lo compruebas
        if (is_uploaded_file($_FILES['imagen']['tmp_name'])) { //* si se ha subido una imagen a la carpeta temporal

            //todo comprobamos si es una imagen y si no supera los 2MB.

            if (Utilidades::errorTipoFotoYSize($_FILES['imagen']['type'], $_FILES['imagen']['size'])) { //? Si la extension no esta definida en el array, la funcion devuelve true, por lo tanto ERROR 
                $errores = true;
            } else { //* Si pasamos las validaciones.....

                $imagen = "img/" . uniqid() . "_" . $_FILES['imagen']['name']; //? Esto devuelve img/98685968_perfil.jpg;

                //todo Validamos si se ha podido mover la imagen que esta en la carpeta temporal a la de img

                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], "./" . $imagen)) { //? Si no se ha podido mover la imagen subida de la carpeta temporal a la de img, adivina..... ERROR. 
                    $errores = true;
                } else {


                    //* Si llega aqui es porque se ha subido, por lo tanto tengo que eliminar la anterior y subir la nueva

                    if (basename($articulo->imagen) != "default.jpeg") { //* Si el nombr de la imagen que esta guardada en la baase de datos es distinta a la de default la borramos
                        unlink("./" . $articulo->imagen); //? borramos la foto
                    }
                }
            }
        } else {

            $imagen = $articulo -> imagen; //? Si no sube una imagen le ponemos esta por defecto

        }
    }




    if ($errores) {
        /* die("Error"); */
        header("Location:{$_SERVER['PHP_SELF']}?id=$idArticuloGet");
        die();
    }

    

    (new Articulos)
        ->setNombre($nombre)
        ->setPrecio($precio)
        ->setDisponible($disponible)
        ->setCategoryId($categoria_id)
        ->setImagen($imagen)
        ->update($idArticuloGet);




    $_SESSION['mensaje'] = "El articulo se ha actualizado exitosamente";
    header("Location:index.php");
} else {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Tailwind css -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Fontawesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Sweetalert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <title>Crear articulo</title>
    </head>

    <body style="background-color:grey">
        <!-- Cargamos listado de los libros -->
        <h3 class="my-2 text-xl text-center">Actualizar Articulos</h3>
        <div class="w-1/2 mx-auto p-4 bg-gray-300 rounded-x1 shadow-x1">

            <form action="<?php echo $_SERVER['PHP_SELF']."?id=$idArticuloGet"?>" method="post" enctype="multipart/form-data">

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="titulo">
                        Nombre
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombre" value="<?php echo $articulo -> nombre ?>" name="nombre" type="text" placeholder="Escribe el nombre del articulo">
                    <?php
                    Utilidades::mostrarErrores("nombre")
                    ?>
                </div>

                <div class="mb-4">
                    <label for="disponible" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        PODUCTO DISPONIBLE</label>
                    <?php
                    $checked = $articulo -> disponible == "SI" ? "checked" : " ";
                     ?>
                    <input type="checkbox" <?php echo $checked ?> name="disponible" id="nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    <span class="text-gray-100 font-semibold" >SI</span>
                    
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="precio">
                        Precio
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  id="precio" value="<?php echo $articulo -> precio?>"  name="precio" type="text" placeholder="Introduce el precio" step="0.01">
                    <?php
                    Utilidades::mostrarErrores("precio");
                    ?>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mt-2 mb-2" for="autor_id">
                            Categoria
                        </label>
                        <!---//todo Aqui vamos a devolver el nombre y los apellidos del autor, pero nosotros vamos a devolver solamente el id del autor. -->
                        <select name="categoria" id="categoria">
                            <option> _______________ Selecciona una categoria______________</option>
                            <!--//! Recorremos lo que devuelve el metodo read-->
                            <?php
                            foreach ($articulos as $item) { //? Para mostrar los nombres de las categorias , revisar el metodo read de articulos
                                $categoria_selecionada = ($item -> id == $articulo -> category_id) ? "selected" : "";
                                echo "<option $categoria_selecionada value='{$item->id}'>{$item->nombre}</option>";
                            }
                            ?>
                        </select>

                        <?php
                        Utilidades::mostrarErrores("id_categoria");
                        ?>
                    </div>

                    <div class="mb-4 flex justify-between">
                        <div class="w-full">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="portada">
                                Imagen Articulo
                            </label>
                            <input type="file" name="imagen" oninput="img.src=window.URL.createObjectURL(this.files[0])" id="portada" accept="img/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <?php
                                Utilidades::mostrarErrores("Imagen");
                            ?>
                        </div>

                        <div>
                            <img src="<?php echo $articulo -> imagen ?>" class="w-100" id="img" alt="">
                        </div>
                    </div>

                    <div class="flex flex-row-reverse">
                        <button type="submit" name="btn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <i class="fas fa-save mr-2"></i>Actualizar
                        </button>
                        <button type="reset" class="mr-2 text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-blue-800">
                            <i class="fas fa-paintbrush mr-2"></i>LIMPIAR
                        </button>
                        <a href="index.php" class="mr-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-blue-800">
                            <i class="fas fa-backward mr-2"></i>VOLVER
                        </a>
                    </div>

            </form>

        </div>
    </body>

    </html>



<?php
}
?>