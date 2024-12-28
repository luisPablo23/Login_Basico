<?php
include("../../bd.php");

// Verificar si se recibe el ID a editar
if (isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    // Consultar el registro existente en la tabla "puestos"
    $sentencia = $conexion->prepare("SELECT * FROM puestos WHERE id = :id");
    $sentencia->bindParam(":id", $txtID);

    try {
        $sentencia->execute();
        $registro = $sentencia->fetch(PDO::FETCH_LAZY);

        // Obtener los datos actuales del registro
        $nombredelpuesto = $registro["nombredelpuesto"];
    } catch (PDOException $e) {
        $mensaje = "Error al cargar los datos: " . $e->getMessage();
        header("Location: index.php?mensaje=" . urlencode($mensaje));
        exit;
    }
}

// Verificar si se ha enviado el formulario para actualizar
if ($_POST) {
    $nombredelpuesto = (isset($_POST["nombredelpuesto"])) ? $_POST["nombredelpuesto"] : "";

    // Actualizar el registro en la base de datos
    $sentencia = $conexion->prepare("UPDATE puestos SET nombredelpuesto = :nombredelpuesto WHERE id = :id");
    $sentencia->bindParam(":nombredelpuesto", $nombredelpuesto);
    $sentencia->bindParam(":id", $txtID);

    try {
        $sentencia->execute();
        $mensaje = "Registro actualizado correctamente";
        header("Location: index.php?mensaje=" . urlencode($mensaje));
        exit;
    } catch (PDOException $e) {
        $mensaje = "Error al actualizar el registro: " . $e->getMessage();
        header("Location: index.php?mensaje=" . urlencode($mensaje));
        exit;
    }
}
?>

<?php include("../../templates/header.php"); ?>
<h2>Editar Puesto</h2>
<br/>
<div class="card">
    <div class="card-header">Datos del Puesto</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombredelpuesto" class="form-label">Puesto:</label>
                <input type="text" value="<?php echo htmlspecialchars($nombredelpuesto); ?>" class="form-control" 
                    name="nombredelpuesto" id="nombredelpuesto" placeholder="Nombre del puesto" required />
                <small id="helpId" class="form-text text-muted">Actualice el nombre del puesto</small>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="index.php" class="btn btn-primary">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-muted">Footer</div>
</div>
<?php include("../../templates/footer.php"); ?>
