<?php

use App\Db\Articulos;
use App\Db\Categorias;
session_start();
require_once __DIR__."/../vendor/autoload.php";

Articulos::generarArticulos(5);
Categorias::generarRegistros(5);

$articulo  = Articulos::read();


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fontawesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Pagina principal</title>
</head>
<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>
    <!-- Fontawesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body style="background-color: burlywood;">
    <div class="container p-12 mx-auto">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex flex-row-reverse">
                <a href="create.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded  mb-2"><i class="fas fa-plus mr-2"></i>Nuevo articulo</a>
            </div>
            <table class="w-full text-sm text-center text-bold rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Disponibilidad
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Precio
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Categoria
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($articulo as $item) {
                            $color = $item -> disponible == "SI" ? "text-green-600" : "text-red-600";
                            echo <<<TXT
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            <img class="w-10 h-10 rounded-full" src="./{$item -> imagen}" alt="Jese image">
                            <div class="ps-3">
                                <div class="text-base font-semibold">{$item -> nombre}</div>
                                
                            </div>
                        </th>
                        <td class="px-6 py-4 $color">
                           {$item -> disponible} 
                           </td>
                           <td class="px-6 py-4">
                           {$item -> precio}€ 
                        </td>
                        <td class="px-6 py-4">
                            {$item -> nomCat }
                        </td>
                        <td class="px-6 py-4">
                            <form action="delete.php" method="post">
                                <input type="hidden" name="id" value="{$item -> id}">
                                <a href="detalle.php?id={$item -> id}"><i class="fas fa-info text-blue-600 mr-2"></i></a>
                                <a href="update.php?id={$item -> id}"><i class="fas fa-edit text-yellow-600 mr-2"></i></a>
                                <button type="submit"><i class="fas fa-trash text-red-600"></i></button>
                            </form>
                        </td>
                    </tr>
                    TXT;
                }
                ?>

                </tbody>
            </table>
        </div>
    </div>
</body>

<?php 

if (isset($_SESSION['mensaje'])){
    echo <<<TXT
    <script>
    Swal.fire({
        icon: "success",
        title: "{$_SESSION['mensaje']}",
        showConfirmButton: false,
        timer: 1500
      });
    </script>
    TXT;
    unset($_SESSION['mensaje']);
}


?>

</html>