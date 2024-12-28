<?php
include("../../bd.php");

// Obtener los registros de la tabla `puestos`
$sentencia = $conexion->prepare("SELECT * FROM puestos");
$sentencia->execute();
$listaPuestos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include("../../templates/header.php"); ?>

<br/>
<div class="container">
    <h2>Gestión de Puestos</h2>

    <!-- Mostrar mensajes de éxito o error -->
    <?php if (isset($_GET['mensaje'])) { ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($_GET['mensaje']); ?>
        </div>
    <?php } ?>

    <?php if (isset($_GET['error'])) { ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php } ?>

    <div class="card">
        <div class="card-header">
            <a href="crear.php" class="btn btn-primary">Agregar Puesto</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Puesto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaPuestos as $puesto) { ?>
                        <tr>
                            <td><?php echo $puesto['id']; ?></td>
                            <td><?php echo $puesto['nombredelpuesto']; ?></td>
                            <td>
                                <a href="editar.php?txtID=<?php echo $puesto['id']; ?>" class="btn btn-warning">
                                    Editar
                                </a>
                                <a href="eliminar.php?txtID=<?php echo $puesto['id']; ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('¿Está seguro de que desea eliminar este registro?');">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-muted">
            Gestión de Puestos - Footer
        </div>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
