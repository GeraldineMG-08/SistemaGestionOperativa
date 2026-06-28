<?php
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

    $id_registro = $_POST['id_registro'];
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];
    $medio_pago = $_POST['medio_pago'];

    //=========================================
    // OBTENER PRODUCTO
    //=========================================
    $sql = "SELECT * FROM producto WHERE id_producto = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_producto);
    $stmt->execute();

    $producto = $stmt->fetch();

    if (!$producto) {
        throw new Exception("Producto no encontrado");
    }

    //=========================================
    // VALIDAR STOCK
    //=========================================
    if ($producto['stock'] < $cantidad) {
        throw new Exception("Stock insuficiente");
    }

    //=========================================
    // CALCULAR MONTO
    //=========================================
    $total = $producto['precio_venta'] * $cantidad;

    //=========================================
    // INSERTAR CONSUMO
    //=========================================
    $sql = "INSERT INTO detalle_consumo
    (id_registro, id_producto, cantidad, monto_consumo_pagado, medio_pago_consumo)
    VALUES
    (:registro, :producto, :cantidad, :monto, :pago)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':registro' => $id_registro,
        ':producto' => $id_producto,
        ':cantidad' => $cantidad,
        ':monto' => $total,
        ':pago' => $medio_pago
    ]);

    //=========================================
    // DESCONTAR STOCK
    //=========================================
    $sql = "UPDATE producto
            SET stock = stock - :cantidad
            WHERE id_producto = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cantidad' => $cantidad,
        ':id' => $id_producto
    ]);

    $pdo->commit();

    header("Location: ../views/operaciones/consumos.php");
    exit();

} catch (Exception $e) {

    $pdo->rollBack();
    die("Error consumo: " . $e->getMessage());
}