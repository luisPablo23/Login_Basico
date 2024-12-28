<?php
include("../../bd.php");

// Consulta de empleados y puestos
$sentencia = $conexion->prepare("SELECT *,(SELECT nombredelpuesto
            FROM puestos WHERE puestos.id=empleados.idpuesto LIMIT 1) as puesto
            FROM empleados");
$sentencia->execute();
$lista_tbl_empleados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Verificar si hay un mensaje
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
?>

<?php include("../../templates/header.php");?>

<h2>Lista de empleados</h2>

<?php if ($mensaje != '') { ?>
    <div class="alert alert-success">
        <?php echo $mensaje; ?>
    </div>
<?php } ?>

<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-danger" href="crear.php" role="button">Nuevo Empleado</a>  
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table table-primary" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombres y Apellidos</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Cv</th>
                        <th scope="col">Puesto</th>
                        <th scope="col">Fecha de ingreso</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_tbl_empleados as $registro) { ?>
                    <tr class="">
                        <td scope="row"><?php echo $registro['id'];?></td>
                        <td><?php echo $registro['primernombre'] . " " . $registro['segundonombre'] . " " . $registro['primerapellido'] . " " . $registro['segundoapellido'];?></td>
                        <td><img width="30" src="<?php echo $registro['foto']; ?>" class="img-fluid rounded" alt="Foto del empleado" /></td>
                        <td><a href="<?php echo $registro['cv']; ?>"><?php echo $registro['cv']; ?></a></td>
                        <td><?php echo $registro['puesto']; ?></td>
                        <td><?php echo $registro['fechaingreso']; ?></td>
                        <td>
                            <a class="btn btn-primary" href="carta_recomendacion.php?txtID=<?php echo $registro['id']; ?>" role="button">Carta</a>
                            <a class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
                            <a class="btn btn-danger" href="eliminar.php?txtID=<?php echo $registro['id']; ?>" role="button">Eliminar</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>     
    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../templates/footer.php"); ?>
