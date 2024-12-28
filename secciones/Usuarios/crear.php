<?php
// Incluir archivo de conexión a la base de datos
include("../../bd.php"); // Asegúrate de que esta ruta sea correcta

// Verificar si el formulario ha sido enviado
if ($_POST) {
    // Recoger los datos del formulario
    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";
    $correo = isset($_POST['correo']) ? $_POST['correo'] : "";

    // Validar que los campos no estén vacíos
    if ($usuario && $password && $correo) {
        // Encriptar la contraseña antes de guardarla
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Preparar la sentencia SQL para insertar el usuario
            $sentencia = $conexion->prepare("INSERT INTO usuarios (usuario, password, correo) VALUES (:usuario, :password, :correo)");
            $sentencia->bindParam(":usuario", $usuario);
            $sentencia->bindParam(":password", $passwordHash);
            $sentencia->bindParam(":correo", $correo);
            $sentencia->execute();

            // Redirigir a la página principal con un mensaje de éxito
            header("Location: index.php?mensaje=Usuario creado correctamente");
            exit(); // Asegurar que el código se detenga aquí después de la redirección
        } catch (Exception $ex) {
            // Mostrar mensaje de error si ocurre algún problema
            echo "Error al agregar el usuario: " . $ex->getMessage();
        }
    } else {
        // Si los campos están vacíos, mostrar un mensaje de error
        echo "<div class='alert alert-danger'>Por favor, complete todos los campos.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Crear Usuario</h1>
        <a href="index.php" class="btn btn-secondary mb-3">Regresar</a>
        <!-- Botón para regresar a la página principal de la webapp -->
        <a href="../../index.php" class="btn btn-secondary mb-3">Regresar a la página principal</a>

        <!-- Formulario para crear un nuevo usuario -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</body>
</html>
