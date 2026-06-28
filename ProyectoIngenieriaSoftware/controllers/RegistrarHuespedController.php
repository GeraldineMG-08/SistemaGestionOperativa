<?php
//====================================================
// REGISTRAR HUESPED CONTROLLER
//====================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

require_once "../config/conexion.php";

try {

    $pdo->beginTransaction();

    //================================================
    // DATOS DEL FORMULARIO
    //================================================
    $dni          = $_POST['dni'];
    $nombre       = $_POST['nombre_completo'];
    $procedencia  = $_POST['procedencia'];

    $id_habitacion = $_POST['id_habitacion'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $fecha_salida  = $_POST['fecha_salida'];
    $medio_pago    = $_POST['medio_pago'];

    $id_tarifa     = $_POST['id_tarifa'];
    $dias_estadia  = $_POST['dias_estadia'];
    $monto_total   = $_POST['monto_total'];

    $id_usuario = $_SESSION['id_usuario'];

    //================================================
    // 1. BUSCAR O CREAR HUESPED
    //================================================
    $sql = "SELECT * FROM huesped WHERE dni = :dni";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dni', $dni);
    $stmt->execute();

    $huesped = $stmt->fetch();

    if ($huesped) {

        $id_huesped = $huesped['id_huesped'];

    } else {

        $sql = "INSERT INTO huesped (dni, nombre_completo, lugar_procedencia)
                VALUES (:dni, :nombre, :procedencia)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':dni' => $dni,
            ':nombre' => $nombre,
            ':procedencia' => $procedencia
        ]);

        $id_huesped = $pdo->lastInsertId();
    }

    //================================================
    // 2. OBTENER TURNO ACTIVO (último turno abierto)
    //================================================
    $sql = "SELECT id_turno FROM turno
            WHERE id_usuario = :id_usuario
            AND hora_cierre IS NULL
            ORDER BY id_turno DESC
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->execute();

    $turno = $stmt->fetch();

    if (!$turno) {
        throw new Exception("No hay turno activo");
    }

    $id_turno = $turno['id_turno'];

    //================================================
    // 3. INSERTAR REGISTRO HOSPEDAJE
    //================================================
    $sql = "INSERT INTO registro_huesped
    (id_huesped, id_habitacion, id_tarifa, id_turno,
     fecha_ingreso, fecha_salida, dias_estadia,
     monto_hospedaje_pagado, medio_pago)
    VALUES
    (:huesped, :habitacion, :tarifa, :turno,
     :ingreso, :salida, :dias,
     :monto, :pago)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':huesped' => $id_huesped,
        ':habitacion' => $id_habitacion,
        ':tarifa' => $id_tarifa,
        ':turno' => $id_turno,
        ':ingreso' => $fecha_ingreso,
        ':salida' => $fecha_salida,
        ':dias' => $dias_estadia,
        ':monto' => $monto_total,
        ':pago' => $medio_pago
    ]);

    //================================================
    // 4. ACTUALIZAR HABITACION A OCUPADA
    //================================================
    $sql = "UPDATE habitacion
            SET estado = 'Ocupada'
            WHERE id_habitacion = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_habitacion);
    $stmt->execute();

    //================================================
    // 5. CONFIRMAR TRANSACCIÓN
    //================================================
    $pdo->commit();

    //================================================
    // 6. REDIRECCIÓN
    //================================================
    header("Location: ../views/operaciones/mapa.php");
    exit();

} catch (Exception $e) {

    $pdo->rollBack();

    die("Error en registro: " . $e->getMessage());
}