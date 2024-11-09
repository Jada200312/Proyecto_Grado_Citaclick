<?php
include('../../back/conexion.php');

$response = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM servicios 
            WHERE id = ? 
            AND NOT EXISTS (
                SELECT 1 FROM reservas 
                WHERE servicio = ? 
                AND fechaReserva > CURDATE()
            )";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response = [
                'title' => '¡Corte eliminado!',
                'text' => 'El corte se ha eliminado exitosamente.',
                'icon' => 'success'
            ];
        } else {
            $response = [
                'title' => 'No se puede eliminar',
                'text' => 'No se puede eliminar el corte ya que existen reservas futuras.',
                'icon' => 'warning'
            ];
        }
    } else {
        $response = [
            'title' => 'Error',
            'text' => 'Error al eliminar el corte: ' . $stmt->error,
            'icon' => 'error'
        ];
    }

    $stmt->close();
    $conn->close();

} else {
    $response = [
        'title' => 'Error',
        'text' => 'ID no proporcionado.',
        'icon' => 'error'
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>