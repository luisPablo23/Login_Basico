<?php
include("../../bd.php");

if ($_POST) {
    // Validación del nombre del puesto
    $nombredelpuesto = (isset($_POST["nombredelpuesto"]) ? $_POST["nombredelpuesto"] : "");

    // Preparar la inserción de los datos
    $sentencia = $conexion->prepare("INSERT INTO puestos (id, nombredelpuesto) VALUES (NULL, :nombredelpuesto)");

    // Asignar los valores que vienen del método POST (los que vienen del formulario)
    $sentencia->bindParam(":nombredelpuesto", $nombredelpuesto);

    try {
        $sentencia->execute();
        $mensaje = "Registro creado correctamente";
    } catch (PDOException $e) {
        $mensaje = "Error al crear el registro: " . $e->getMessage();
    }

    // Redirigir con el mensaje de éxito o error
    header("Location: index.php?mensaje=" . urlencode($mensaje));
    exit;
}
?>

<?php include("../../templates/header.php"); ?>
<br/>
<div class="card">
    <div class="card-header">Crear Puesto</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombredelpuesto" class="form-label">Puesto:</label>
                <input type="text" class="form-control" name="nombredelpuesto" id="nombredelpuesto"
                    aria-describedby="helpId" placeholder="Nombre del puesto" required />
                <small id="helpId" class="form-text text-muted">Ingrese el nombre del puesto</small>
            </div>
            <button type="submit" class="btn btn-success">Guardar registro</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-muted">Footer</div>
</div>
<?php include("../../templates/footer.php"); ?>
