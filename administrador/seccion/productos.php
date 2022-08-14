<?php include('../template/cabecera.php') ?>
<?php 
$txtID=(isset($_POST['textID']))?$_POST['textID']:"";
$txtNombre=(isset($_POST['textNombre']))?$_POST['textNombre']:"";
$txtImagen=(isset($_FILES['textImagen']['name']))?$_FILES['textImagen']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

include('../config/bd.php');

switch($accion){
    case 'Agregar':
        //INSERT INTO `libros` (`id`, `nombre`, `imagen`) VALUES (NULL, ' libro de php', 'imagen.png');
        $sentenciasql=$conexion->prepare("INSERT INTO libros (nombre, imagen) VALUES (:nombre , :imagen)");
        $sentenciasql->bindParam(':nombre',$txtNombre);

        $fecha=new DateTime();
        $nombredelArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES['textImagen']['name']:"imagen.jpg";
        $tmpImagen=$_FILES['textImagen']['tmp_name'];

        if($tmpImagen!=""){
            move_uploaded_file($tmpImagen,"../../img/".$nombredelArchivo);
        }

        $sentenciasql->bindParam(':imagen',$nombredelArchivo);
        $sentenciasql->execute();
        header("Location:productos.php");
        break;
    case 'Modificar':
        //UPDATE `libros` SET `nombre` = 'node', `imagen` = 'hola.png' WHERE `libros`.`id` = 8;
        if($txtNombre!=""){
            $sentenciasql=$conexion->prepare("UPDATE libros SET nombre = :nombre WHERE libros . id = :id");
            $sentenciasql->bindParam(':nombre',$txtNombre);
            $sentenciasql->bindParam(':id',$txtID);
            $sentenciasql->execute();
            if($txtImagen!=""){
                $fecha=new DateTime();
                $nombredelArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES['textImagen']['name']:"imagen.jpg";
                $tmpImagen=$_FILES['textImagen']['tmp_name'];
                move_uploaded_file($tmpImagen,"../../img/".$nombredelArchivo);
                $sentenciasql=$conexion->prepare("SELECT imagen FROM libros WHERE id= :id");
                $sentenciasql->bindParam(':id',$txtID);
                $sentenciasql->execute();
                $Libro=$sentenciasql->fetch(PDO::FETCH_LAZY);
                if(isset($Libro['imagen']) && ($Libro['imagen']!='imagen.jpg')){
                    if(file_exists('../../img/'.$Libro['imagen'])){
                        unlink('../../img/'.$Libro['imagen']);
                    }
                }
                $sentenciasql=$conexion->prepare("UPDATE libros SET imagen = :imagen WHERE libros . id = :id");
                $sentenciasql->bindParam(':imagen',$nombredelArchivo);
                $sentenciasql->bindParam(':id',$txtID);
                $sentenciasql->execute();
            }
            header("Location:productos.php");
        }
        break;
    case 'Cancelar':
        header("Location:productos.php");
        break;
    case "Seleccionar":
        $sentenciasql=$conexion->prepare("SELECT * FROM libros WHERE id= :id");
        $sentenciasql->bindParam(':id',$txtID);
        $sentenciasql->execute();
        $Libro=$sentenciasql->fetch(PDO::FETCH_LAZY);
        $txtNombre=$Libro['nombre'];
        $txtImagen=$Libro['imagen'];
        break;
    case "Borrar":
        $sentenciasql=$conexion->prepare("SELECT imagen FROM libros WHERE id= :id");
        $sentenciasql->bindParam(':id',$txtID);
        $sentenciasql->execute();
        $Libro=$sentenciasql->fetch(PDO::FETCH_LAZY);
        if(isset($Libro['imagen']) && ($Libro['imagen']!='imagen.jpg')){
            if(file_exists('../../img/'.$Libro['imagen'])){
                unlink('../../img/'.$Libro['imagen']);
            }
        }
        $sentenciasql=$conexion->prepare("DELETE FROM libros WHERE id=:id");
        $sentenciasql->bindParam(':id',$txtID);
        $sentenciasql->execute();
        header("Location:productos.php");
        break;
}
$sentenciasql=$conexion->prepare("SELECT * FROM libros");
$sentenciasql->execute();
$Libros=$sentenciasql->fetchall(PDO::FETCH_ASSOC);
?>
<div class="col-md-5">
    <div class="card">
        <div class="card-header">
            Datos de libro
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="textID">ID:</label>
                    <input type="text" readonly class="form-control" id="textID" name="textID" placeholder="ID" value="<?= $txtID ?>">
                </div>
                <div class="form-group">
                    <label for="textNombre">Nombre:</label>
                    <input type="text" class="form-control" id="textNombre" name="textNombre" placeholder="Nombre del libro" value="<?= $txtNombre ?>">
                </div>
                <div class="form-group">
                    <label for="textImagen">Imagen:</label>
                    <input type="file" id="textImagen" name="textImagen" >
                </div>
                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" value="Agregar" <?= $accion=="Seleccionar"?"disabled":""; ?> name="accion" class="btn btn-success">Agregar</button>
                    <button type="submit" value="Modificar" <?= $accion!="Seleccionar"?"disabled":""; ?> name="accion" class="btn btn-warning">Modificar</button>
                    <button type="submit" value="Cancelar" <?= $accion!="Seleccionar"?"disabled":""; ?> name="accion" class="btn btn-info">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-md-7">
    <a href="reportes.php">Reporte pdf</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($Libros as $libro){ ?>
                <tr>
                    <td><?= $libro['id'] ?></td>
                    <td><?= $libro['nombre'] ?></td>
                    <?php if(file_exists('../../img/'.$libro['imagen'])){?>
                        <td>
                            <img width="100" src="<?= '../../img/'.$libro['imagen'] ?>">
                        </td>
                    <?php } ?>
                    <td>
                        <form method="post">
                            <input type="hidden" name="textID" id="textID" value="<?= $libro['id'] ?>">
                            <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>
                            <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>
                        </form> 
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('../template/pie.php') ?>