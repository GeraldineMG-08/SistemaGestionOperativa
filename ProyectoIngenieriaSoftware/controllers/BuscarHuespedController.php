<?php
require_once "../config/conexion.php";

header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['buscar'])) {

    $buscar = trim($_GET['buscar']);
    $param = "%$buscar%";

    $sql = "SELECT id_huesped, dni, nombre_completo, lugar_procedencia
            FROM huesped
            WHERE dni LIKE :busqueda
               OR nombre_completo LIKE :busqueda
            LIMIT 5";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':busqueda', $param, PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode($stmt->fetchAll());
    exit();
}

echo json_encode([]);