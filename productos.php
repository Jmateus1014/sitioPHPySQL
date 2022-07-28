<?php include('template/cabecera.php') ?>
<?php 
include('administrador/config/bd.php');
$sentenciasql=$conexion->prepare("SELECT * FROM libros");
$sentenciasql->execute();
$Libros=$sentenciasql->fetchall(PDO::FETCH_ASSOC);
?>
<?php foreach($Libros as $libro){ ?>
    <div class="col-md-3">
        <div class="card">
            <img class="card-img-top" src="<?= 'img/'.$libro['imagen'] ?>" alt="">
            <div class="card-body">
                <h4 class="card-title"><?= $libro['nombre'] ?></h4>
            </div>
        </div>
    </div>
<?php } ?>

<?php include('template/pie.php') ?>