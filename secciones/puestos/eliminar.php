<?php
include("../../bd.php");

if (isset($_GET['txtID'])) {
    $txtID = (int) $_GET['txtID']; // Asegúrate de que el ID sea un número entero

    // Verificar si el registro existe antes de intentar eliminarlo
    $sentenciaVerificar = $conexion->prepare("SELECT * FROM puestos WHERE id = :id");
    $sentenciaVerificar->bindParam(":id", $txtID, PDO::PARAM_INT);
    $sentenciaVerificar->execute();
    $registro = $sentenciaVerificar->fetch(PDO::FETCH_ASSOC);

    if ($registro) {
        // Si el registro existe, proceder a eliminarlo
        $sentenciaEliminar = $conexion->prepare("DELETE FROM puestos WHERE id = :id");
        $sentenciaEliminar->bindParam(":id", $txtID, PDO::PARAM_INT);
        $sentenciaEliminar->execute();

        // Redirigir con un mensaje de éxito
        header("Location: index.php?mensaje=Registro eliminado correctamente");
        exit;
    } else {
        // Si el registro no existe, redirigir con un mensaje de error
        header("Location: index.php?error=El registro no existe o ya fue eliminado");
        exit;
    }
} else {
    // Si no se proporciona un ID válido, redirigir con un mensaje de error
    header("Location: index.php?error=No se proporcionó un ID válido");
    exit;
}
?>
