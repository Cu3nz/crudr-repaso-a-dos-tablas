<?php

require_once __DIR__."/../vendor/autoload.php";
session_start();
use App\Db\Articulos;

if (!isset($_POST['id'])){
    header("Location:index.php");
    die();
}

$idPost = $_POST['id'];

/* echo "$idPost"; */

$articulo = Articulos::detalle($idPost);

/* var_dump($articulo); */

if (!$articulo){
    header("Location:index.php");
    die(); 
}


if (basename($articulo -> imagen ) != "default.png"){
    unlink("./" . $articulo -> imagen);
}

Articulos::delete($idPost);
$_SESSION['mensaje'] = "El articulo se ha eliminado correctamente";
header("Location:index.php");




?>