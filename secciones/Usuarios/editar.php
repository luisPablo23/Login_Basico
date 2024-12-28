<?php 
// Incluir el archivo de conexión a la base de datos
include("../../bd.php"); // Asegúrate de que la ruta sea correcta

// Verificar si se recibió el ID por GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del usuario
    try {
        $sentencia = $conexion->prepare("SELECT * FROM usuarios WHERE id = :id");
        $sentencia->bindParam(":id", $id);
        $sentencia->execute();
        $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);

        // Si el usuario no existe, redirigir con mensaje
        if (!$usuario) {
            header("Location: index.php?mensaje=Usuario no encontrado");
            exit;
        }
    } catch (Exception $ex) {
        echo "Error al obtener los datos del usuario: " . $ex->getMessage();
        exit;
    }
} else {
    header("Location: index.php?mensaje=ID no proporcionado");
    exit;
}

// Actualizar los datos del usuario al enviar el formulario
if ($_POST) {
    $nuevoUsuario = isset($_POST['usuario']) ? $_POST['usuario'] : "";
    $nuevoPassword = isset($_POST['password']) ? $_POST['password'] : "";
    $nuevoCorreo = isset($_POST['correo']) ? $_POST['correo'] : "";

    // Validar que los campos no estén vacíos
    if (empty($nuevoUsuario) || empty($nuevoCorreo)) {
        echo "<div class='alert alert-danger'>Los campos usuario y correo son obligatorios.</div>";
    } else {
        try {
            // Si se ha ingresado una nueva contraseña, encriptarla
            if (!empty($nuevoPassword)) {
                $nuevoPassword = password_hash($nuevoPassword, PASSWORD_BCRYPT); // Encriptar la nueva contraseña
            } else {
                // Si no se ingresa una nueva contraseña, mantener la contraseña actual
                $nuevoPassword = $usuario['password'];
            }

            // Actualizar los datos en la base de datos
            $sentencia = $conexion->prepare("UPDATE usuarios SET usuario = :usuario, password = :password, correo = :correo WHERE id = :id");
            $sentencia->bindParam(":usuario", $nuevoUsuario);
            $sentencia->bindParam(":password", $nuevoPassword);
            $sentencia->bindParam(":correo", $nuevoCorreo);
            $sentencia->bindParam(":id", $id);
            $sentencia->execute();

            // Redirigir con mensaje de éxito
            header("Location: index.php?mensaje=Usuario actualizado correctamente");
            exit;
        } catch (Exception $ex) {
            echo "<div class='alert alert-danger'>Error al actualizar el usuario: " . $ex->getMessage() . "</div>";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Editar Usuario</h1>
        <a href="index.php" class="btn btn-secondary mb-3">Regresar</a>
        <!-- Botón para regresar a la página principal de la webapp -->
        <a href="../../index.php" class="btn btn-secondary mb-3">Regresar a la página principal</a>

        <!-- Formulario para editar el usuario -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo htmlspecialchars($usuario['usuario']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Deja en blanco para mantener la actual">
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
</body>
</html>
