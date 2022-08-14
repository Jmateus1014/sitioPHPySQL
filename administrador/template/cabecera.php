<?php
    session_start();
    if(!isset($_SESSION['usuario'])){
        header("Location:../index.php");
    }else{
        if($_SESSION['usuario']=="ok"){
            $nombredeusuario=$_SESSION['nombredeUsuario'];
        }else{
            header("Location:index.php");
        }
    }
?>
<!doctype html>
<html lang="en">
<head>
    <title>Administrador del sitio web</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="shortcut icon" href="https://png.pngtree.com/png-vector/20191021/ourmid/pngtree-vector-setting-icon-png-image_1833733.jpg" type="image/jpg">
</head>
<body>

    <?php $url="http://".$_SERVER['HTTP_HOST']."/sitioPHPySQL" ?>

    <nav class="navbar navbar-expand navbar-light bg-light">
        <div class="nav navbar-nav">
            <a class="nav-item nav-link active" href="#">Administrador del sitio Web <span class="sr-only">(current)</span></a>
            <a class="nav-item nav-link" href="<?= $url ?>/administrador/inicio.php">Inicio</a>
            <a class="nav-item nav-link" href="<?= $url ?>/administrador/seccion/productos.php">Libros</a>
            <a class="nav-item nav-link" href="<?= $url ?>/administrador/seccion/Cerrar.php">Cerrar Sesion</a>
            <a class="nav-item nav-link" href="<?= $url ?>">Ver sitio web</a>
        </div>
    </nav>
    <div class="container">
        <br>
        <div class="row">