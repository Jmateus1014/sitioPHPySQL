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
    ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <?php 
    include('../config/bd.php');
    $sentenciasql=$conexion->prepare("SELECT * FROM libros");
    $sentenciasql->execute();
    $Libros=$sentenciasql->fetchall(PDO::FETCH_ASSOC);
    ?>
    <h1>Reporte de libros</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($Libros as $libro){ ?>
                <tr>
                    <td><?= $libro['id'] ?></td>
                    <td><?= $libro['nombre'] ?></td>
                    <?php if(file_exists('../../img/'.$libro['imagen'])){?>
                        <td>
                            <img width="100" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/sitioweb/img/<?= $libro['imagen']; ?>">
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
<?php
$html=ob_get_clean();
//echo $html;

require_once '../library/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$pdf=new Dompdf();
$options=$pdf->getOptions();
$options->set(array('isRemoteEnabled'=>true));
$pdf->setOptions($options);
$pdf->loadHtml($html);
$pdf->setPaper('letter');
//$pdf->setPaper('A4','portrait');
$pdf->render();
$pdf->stream('Certificado.pdf',array('Attachment'=>false));

?>