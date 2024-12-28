<?php 
include("C:/xampp/htdocs/webapp/bd.php"); // Ruta absoluta

// Consultar los usuarios
try {
    $sentencia = $conexion->prepare("SELECT * FROM usuarios");
    $sentencia->execute();
    $usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $ex) {
    echo "Error al consultar la base de datos: " . $ex->getMessage();
}

// Eliminar un usuario
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    try {
        $eliminar = $conexion->prepare("DELETE FROM usuarios WHERE id = :id");
        $eliminar->bindParam(":id", $id);
        $eliminar->execute();
        header("Location: index.php"); // Redirige para actualizar la lista
    } catch (Exception $ex) {
        echo "Error al eliminar el usuario: " . $ex->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .table th {
            background-color: #f8f9fa;
            color: #495057;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .btn {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestión de Usuarios</h1>

        <div class="d-flex justify-content-between mb-3">
            <a href="crear.php" class="btn btn-primary">Agregar Usuario</a>
            <!-- Botón para regresar a la página principal de la webapp -->
            <a href="../../index.php" class="btn btn-secondary">Regresar a la página principal</a>
        </div>

        <!-- Tabla de usuarios -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $usuario['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="index.php?eliminar=<?php echo $usuario['id']; ?>" class="btn btn-danger btn-sm" 
                               onclick="return confirm('¿Está seguro de eliminar este usuario?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
