<?php include ("../../bd.php");

if ($_POST) {
    // Recolectamos los datos del método POST
    $primernombre = isset($_POST["primernombre"]) ? $_POST["primernombre"] : "";
    $segundonombre = isset($_POST["segundonombre"]) ? $_POST["segundonombre"] : "";
    $primerapellido = isset($_POST["primerapellido"]) ? $_POST["primerapellido"] : "";
    $segundoapellido = isset($_POST["segundopellido"]) ? $_POST["segundopellido"] : "";

    // Para foto y CV cambiamos a $_FILES y agregamos ['name']
    $foto = isset($_FILES["foto"]['name']) ? $_FILES["foto"]['name'] : "";
    $cv = isset($_FILES["cv"]['name']) ? $_FILES["cv"]['name'] : "";

    $idpuesto = isset($_POST["idpuesto"]) ? $_POST["idpuesto"] : "";
    $fechaingreso = isset($_POST["fechaingreso"]) ? $_POST["fechaingreso"] : "";

    // Verificar que la fecha se recibe correctamente
    if ($fechaingreso) {
        $fechaingreso_formato = date("Y-m-d", strtotime($fechaingreso)); // convertir la fecha a formato MySQL
    } else {
        // Si la fecha no es válida, asignar valor nulo o manejar el error
        $fechaingreso_formato = null;
    }

    // Depuración de la fecha antes de insertar
    echo "Fecha recibida: " . $fechaingreso_formato . "<br>";

    // Preparar la inserción de los datos
    $sentencia = $conexion->prepare("INSERT INTO empleados 
    (id, primernombre, segundonombre, primerapellido, segundoapellido, foto, cv, idpuesto, fechaingreso)
    VALUES (NULL, :primernombre, :segundonombre, :primerapellido, :segundoapellido, :foto, :cv, :idpuesto, :fechaingreso)");

        $sentencia->bindParam(":primernombre", $primernombre);
        $sentencia->bindParam(":segundonombre", $segundonombre);
        $sentencia->bindParam(":primerapellido", $primerapellido);
        $sentencia->bindParam(":segundoapellido", $segundoapellido);
        $sentencia->bindParam(":foto", $nombreArchivo_foto);
        $sentencia->bindParam(":cv", $nombreArchivo_cv);
        $sentencia->bindParam(":idpuesto", $idpuesto);
        $sentencia->bindParam(":fechaingreso", $fechaingreso_formato);  // Asegúrate de que el parámetro sea correcto


    // Adjuntamos la foto con un nombre único
    $fecha_ = new DateTime();
    $nombreArchivo_foto = ($foto != '') ? $fecha_->getTimestamp() . "_" . $_FILES["foto"]['name'] : "";
    $tmp_foto = $_FILES["foto"]['tmp_name'];
    if ($tmp_foto != '') {
        move_uploaded_file($tmp_foto, "./" . $nombreArchivo_foto);
    }
    $sentencia->bindParam(":foto", $nombreArchivo_foto);

    // Adjuntamos el CV
    $nombreArchivo_cv = ($cv != '') ? $fecha_->getTimestamp() . "_" . $_FILES["cv"]['name'] : "";
    $tmp_cv = $_FILES["cv"]['tmp_name'];
    if ($tmp_cv != '') {
        move_uploaded_file($tmp_cv, "./" . $nombreArchivo_cv);
    }
    $sentencia->bindParam(":cv", $nombreArchivo_cv);

    // Asignamos los valores del puesto y la fecha de ingreso
    $sentencia->bindParam(":idpuesto", $idpuesto);
    $sentencia->bindParam(":fechaingreso", $fechaingreso_formato);

    try {
        $sentencia->execute();
        $mensaje = "Registro agregado exitosamente";
        header("Location: index.php?mensaje=" . urlencode($mensaje));
        exit;
    } catch (PDOException $e) {
        $mensaje = "Error al guardar: " . $e->getMessage();
        header("Location: index.php?mensaje=" . urlencode($mensaje));
        exit;
    }
}

// Consulta de puestos
$sentencia = $conexion->prepare("SELECT * FROM puestos");
$sentencia->execute();
$lista_puestos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include("../../templates/header.php"); ?>
<br/>
<div class="card">
    <div class="card-header">Datos del empleado</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="primernombre" class="form-label">Primer nombre</label>
                <input type="text" class="form-control" name="primernombre" id="primernombre" placeholder="Primer nombre" required />
            </div>

            <div class="mb-3">
                <label for="segundonombre" class="form-label">Segundo nombre</label>
                <input type="text" class="form-control" name="segundonombre" id="segundonombre" placeholder="Segundo nombre" />
            </div>

            <div class="mb-3">
                <label for="primerapellido" class="form-label">Primer apellido</label>
                <input type="text" class="form-control" name="primerapellido" id="primerapellido" placeholder="Primer apellido" required />
            </div>

            <div class="mb-3">
                <label for="segundopellido" class="form-label">Segundo apellido</label>
                <input type="text" class="form-control" name="segundopellido" id="segundopellido" placeholder="Segundo apellido" />
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" class="form-control" name="foto" id="foto" accept="image/*" required />
            </div>

            <div class="mb-3">
                <label for="cv" class="form-label">CV (PDF)</label>
                <input type="file" class="form-control" name="cv" id="cv" accept="application/pdf" required />
            </div>

            <div class="mb-3">
                <label for="idpuesto" class="form-label">Puesto</label>
                <select class="form-select" name="idpuesto" id="idpuesto" required>
                    <option value="" selected>Seleccione una opción</option>
                    <?php foreach ($lista_puestos as $registro) { ?>
                        <option value="<?php echo $registro['id']; ?>"><?php echo $registro['nombredelpuesto']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fechaingreso" class="form-label">Fecha de ingreso</label>
                <input type="date" class="form-control" name="fechaingreso" id="fechaingreso" required />
            </div>

            <button type="submit" class="btn btn-success">Guardar Registro</button>
            <a href="index.php" class="btn btn-primary">Cancelar</a>
        </form>
    </div>
</div>
<div class="card-footer text-muted"></div>
<?php include("../../templates/footer.php"); ?>
