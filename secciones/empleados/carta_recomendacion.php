<?php
// Iniciar el buffer de salida
ob_start();

// Incluir la librería FPDF
require_once('../../libs/fpdf/fpdf186/fpdf.php');

// Verificar si se pasa el ID del empleado
if (isset($_GET['txtID'])) {
    $empleado_id = $_GET['txtID'];

    // Conectar a la base de datos
    include("../../bd.php");

    // Consulta para obtener los datos del empleado
    $sentencia = $conexion->prepare("SELECT *,(SELECT nombredelpuesto FROM puestos WHERE puestos.id = empleados.idpuesto LIMIT 1) as puesto FROM empleados WHERE id = :id");
    $sentencia->bindParam(':id', $empleado_id);
    $sentencia->execute();
    $empleado = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($empleado) {
        // Limpiar cualquier salida previa
        ob_clean();

        // Crear el objeto FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Título del documento
        $pdf->Cell(200, 10, 'Carta de Recomendacion', 0, 1, 'C');
        $pdf->Ln(10); // Espacio

        // Contenido de la carta
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, "Fecha: " . date('d/m/Y'));
        $pdf->Ln(5);
        $pdf->MultiCell(0, 10, "A quien corresponda:");
        $pdf->Ln(10);
        $pdf->MultiCell(0, 10, "Por la presente, yo, [Nombre del jefe], recomiendo a " . $empleado['primernombre'] . " " . $empleado['segundonombre'] . " " . $empleado['primerapellido'] . " " . $empleado['segundoapellido'] . ", quien se desempenio como " . $empleado['puesto'] . " en nuestra empresa.");
        $pdf->Ln(10);
        $pdf->MultiCell(0, 10, "Durante su tiempo con nosotros, ha demostrado ser una persona muy responsable, comprometida con su trabajo y con gran capacidad de adaptacion a nuevas tareas.");
        $pdf->Ln(10);
        $pdf->MultiCell(0, 10, "Sin mas, recomiendo a " . $empleado['primernombre'] . " para cualquier nuevo reto profesional que decida asumir.");
        $pdf->Ln(10);
        $pdf->MultiCell(0, 10, "Atentamente,");
        $pdf->Ln(5);
        $pdf->MultiCell(0, 10, "[Firma del jefe]");
        $pdf->Ln(10);

        // Output: Guardar o mostrar el archivo PDF
        $pdf->Output();
    } else {
        echo "Empleado no encontrado.";
    }
} else {
    echo "ID de empleado no proporcionado.";
}

// Finalizar el buffer de salida y limpiar
ob_end_flush();
?>
