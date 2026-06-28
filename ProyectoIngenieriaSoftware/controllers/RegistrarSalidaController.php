<?php
//=====================================================
// REGISTRAR SALIDA DE HUÉSPED
//=====================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

require_once "../config/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_registro = $_POST['id_registro'] ?? null;

    if (!$id_registro) {
        die("ID de registro no válido");
    }

    try {

        // 1. Obtener datos del registro
        $sql = "SELECT id_habitacion 
                FROM registro_huesped 
                WHERE id_registro = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id_registro);
        $stmt->execute();

        $registro = $stmt->fetch();

        if (!$registro) {
            die("Registro no encontrado");
        }

        $id_habitacion = $registro['id_habitacion'];

        // 2. Marcar salida del huésped
        $sqlUpdateRegistro = "UPDATE registro_huesped
                              SET fecha_salida = NOW()
                              WHERE id_registro = :id";

        $stmt = $pdo->prepare($sqlUpdateRegistro);
        $stmt->bindParam(':id', $id_registro);
        $stmt->execute();

        // 3. Cambiar estado de habitación a limpieza
        $sqlUpdateHab = "UPDATE habitacion
                         SET estado = 'Limpieza'
                         WHERE id_habitacion = :id";

        $stmt = $pdo->prepare($sqlUpdateHab);
        $stmt->bindParam(':id', $id_habitacion);
        $stmt->execute();

        // 4. Redirigir
        header("Location: ../views/operaciones/salidas.php?success=1");
        exit();

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

} else {
    header("Location: ../views/operaciones/salidas.php");
    exit();
}