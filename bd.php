<?php 
$servidor = "localhost"; 
$baseDeDatos="webapp"; 
$usuario="root"; 
$contrasenia=""; 

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$baseDeDatos", $usuario, $contrasenia);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Para manejar errores de forma más controlada
} catch (Exception $ex) {
    echo "Error: " . $ex->getMessage();
}
?>
