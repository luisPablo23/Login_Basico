<?php
include("../../bd.php");

// Verificar si se ha recibido el ID para eliminar
if (isset($_GET['txtID'])) {
    $txtID = $_GET['txtID'];

    // Eliminar la foto y el CV de la carpeta antes de eliminar el registro (si es necesario)
    $sentencia = $conexion->prepare("SELECT foto, cv FROM empleados WHERE id = :id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $empleado = $sentencia->fetch(PDO::FETCH_ASSOC);

    // Borrar los archivos asociados
    if ($empleado) {
        if (file_exists($empleado['foto'])) {
            unlink($empleado['foto']);
        }
        if (file_exists($empleado['cv'])) {
            unlink($empleado['cv']);
        }
    }

    // Eliminar el registro de la base de datos
    $sentencia = $conexion->prepare("DELETE FROM empleados WHERE id = :id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    // Redirigir a la página principal con el mensaje de éxito
    $mensaje = "Registro eliminado correctamente.";
    header("Location: index.php?mensaje=" . urlencode($mensaje));
    exit();
}
?>
