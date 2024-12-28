<?php include("../../bd.php");

if (isset($_GET['txtID'])) {
    $id = $_GET['txtID'];

    // Consulta para obtener los datos del empleado
    $sentencia = $conexion->prepare("SELECT * FROM empleados WHERE id = :id");
    $sentencia->bindParam(':id', $id);
    $sentencia->execute();
    $empleado = $sentencia->fetch(PDO::FETCH_ASSOC);

    // Si no existe el empleado, redirigir
    if (!$empleado) {
        header("Location: index.php?mensaje=Empleado no encontrado");
        exit();
    }

    // Si se envía el formulario con los datos a actualizar
    if ($_POST) {
        // Recoger los datos del formulario
        $primernombre = $_POST['primernombre'];
        $segundonombre = $_POST['segundonombre'];
        $primerapellido = $_POST['primerapellido'];
        $segundoapellido = $_POST['segundoapellido'];
        $idpuesto = $_POST['idpuesto'];
        $fechaingreso = $_POST['fechaingreso'];

        // Subir la foto y el CV si se actualizan
        $foto = $_FILES['foto']['name'] ? $_FILES['foto']['name'] : $empleado['foto'];
        $cv = $_FILES['cv']['name'] ? $_FILES['cv']['name'] : $empleado['cv'];

        // Cambiar nombre de los archivos (si se suben nuevos archivos)
        $fecha_ = new DateTime();
        $nombreArchivo_foto = $foto != '' ? $fecha_->getTimestamp() . "_" . $foto : "";
        $nombreArchivo_cv = $cv != '' ? $fecha_->getTimestamp() . "_" . $cv : "";

        // Guardar los archivos (si existen)
        if ($foto != '') {
            $tmp_foto = $_FILES["foto"]['tmp_name'];
            move_uploaded_file($tmp_foto, "./" . $nombreArchivo_foto);
        }
        if ($cv != '') {
            $tmp_cv = $_FILES["cv"]['tmp_name'];
            move_uploaded_file($tmp_cv, "./" . $nombreArchivo_cv);
        }

        // Actualizar los datos en la base de datos
        $sentencia = $conexion->prepare("UPDATE empleados SET 
                                        primernombre = :primernombre, 
                                        segundonombre = :segundonombre, 
                                        primerapellido = :primerapellido, 
                                        segundoapellido = :segundoapellido, 
                                        foto = :foto, 
                                        cv = :cv, 
                                        idpuesto = :idpuesto, 
                                        fechaingreso = :fechaingreso 
                                        WHERE id = :id");

        // Vincular los parámetros
        $sentencia->bindParam(':primernombre', $primernombre);
        $sentencia->bindParam(':segundonombre', $segundonombre);
        $sentencia->bindParam(':primerapellido', $primerapellido);
        $sentencia->bindParam(':segundoapellido', $segundoapellido);
        $sentencia->bindParam(':foto', $nombreArchivo_foto);
        $sentencia->bindParam(':cv', $nombreArchivo_cv);
        $sentencia->bindParam(':idpuesto', $idpuesto);
        $sentencia->bindParam(':fechaingreso', $fechaingreso);
        $sentencia->bindParam(':id', $id);

        // Ejecutar la actualización
        $sentencia->execute();

        // Mensaje de éxito y redirección
        $mensaje = "Empleado actualizado con éxito";
        header("Location: index.php?mensaje=" . $mensaje);
        exit();
    }
} else {
    echo "No se ha proporcionado un ID válido.";
    exit();
}

?>

<?php include("../../templates/header.php"); ?>

<div class="card">
    <div class="card-header">
        <h2>Editar Empleado</h2>
    </div>
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="primernombre" class="form-label">Primer Nombre</label>
                <input type="text" class="form-control" name="primernombre" value="<?php echo $empleado['primernombre']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="segundonombre" class="form-label">Segundo Nombre</label>
                <input type="text" class="form-control" name="segundonombre" value="<?php echo $empleado['segundonombre']; ?>">
            </div>

            <div class="mb-3">
                <label for="primerapellido" class="form-label">Primer Apellido</label>
                <input type="text" class="form-control" name="primerapellido" value="<?php echo $empleado['primerapellido']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="segundoapellido" class="form-label">Segundo Apellido</label>
                <input type="text" class="form-control" name="segundoapellido" value="<?php echo $empleado['segundoapellido']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" class="form-control" name="foto">
                <img src="<?php echo $empleado['foto']; ?>" width="100" alt="Foto actual">
            </div>

            <div class="mb-3">
                <label for="cv" class="form-label">CV (PDF)</label>
                <input type="file" class="form-control" name="cv">
                <?php if ($empleado['cv']) { ?>
                    <a href="<?php echo $empleado['cv']; ?>" target="_blank">Ver CV actual</a>
                <?php } ?>
            </div>

            <div class="mb-3">
                <label for="idpuesto" class="form-label">Puesto</label>
                <select class="form-select" name="idpuesto" required>
                    <option value="">Seleccione un puesto</option>
                    <?php
                    // Consulta de puestos para llenar el select
                    $sentencia = $conexion->prepare("SELECT * FROM puestos");
                    $sentencia->execute();
                    $puestos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($puestos as $puesto) {
                        $selected = ($puesto['id'] == $empleado['idpuesto']) ? "selected" : "";
                        echo "<option value='{$puesto['id']}' $selected>{$puesto['nombredelpuesto']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fechaingreso" class="form-label">Fecha de Ingreso</label>
                <input type="date" class="form-control" name="fechaingreso" value="<?php echo $empleado['fechaingreso']; ?>" required>
            </div>

            <button type="submit" class="btn btn-success">Guardar cambios</button>
            <a href="index.php" class="btn btn-primary">Cancelar</a>
        </form>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
