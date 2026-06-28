<?php
//======================================================================
// CONTROLADOR: HABILITAR HABITACIÓN
// ARCHIVO: HabilitarHabitacionController.php
//======================================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Solo usuarios autenticados
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

// Conexión
require_once "../config/conexion.php";

// Validar parámetro
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: ../views/operaciones/mapa.php");
    exit();

}

$idHabitacion = (int) $_GET['id'];

try {

    //==================================================
    // Verificar que exista la habitación
    //==================================================

    $sql = "SELECT estado
            FROM habitacion
            WHERE id_habitacion = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $idHabitacion, PDO::PARAM_INT);
    $stmt->execute();

    $habitacion = $stmt->fetch();

    if (!$habitacion) {

        header("Location: ../views/operaciones/mapa.php");
        exit();

    }

    //==================================================
    // Solo se puede habilitar si está en limpieza
    //==================================================

    if ($habitacion['estado'] !== 'Limpieza') {

        header("Location: ../views/operaciones/mapa.php");
        exit();

    }

    //==================================================
    // Cambiar estado
    //==================================================

    $sql = "UPDATE habitacion
            SET estado='Disponible'
            WHERE id_habitacion=:id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $idHabitacion, PDO::PARAM_INT);
    $stmt->execute();

    //==================================================
    // Regresar al mapa
    //==================================================

    header("Location: ../views/operaciones/mapa.php");
    exit();

} catch (PDOException $e) {

    die("Error: " . $e->getMessage());

}