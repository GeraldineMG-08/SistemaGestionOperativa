<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/conexion.php";


// Habitaciones ocupadas

$sql = "SELECT
            rh.id_registro,
            rh.fecha_ingreso,
            rh.fecha_salida,
            rh.dias_estadia,

            h.id_huesped,
            h.dni,
            h.nombre_completo,
            h.lugar_procedencia,

            hab.id_habitacion,
            hab.numero,
            hab.tipo,
            hab.estado

        FROM registro_huesped rh

        INNER JOIN huesped h
            ON rh.id_huesped = h.id_huesped

        INNER JOIN habitacion hab
            ON rh.id_habitacion = hab.id_habitacion

        WHERE hab.estado='Ocupada'

        ORDER BY hab.numero ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$registros = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Salida de Huéspedes</title>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="../../public/css/estilos.css">
</head>

<body>

<div class="app-container">

    <?php include "../includes/sidebar.php"; ?>

    <main class="main-content">

        <header class="content-header">
            <div>
                <h1>
                    <i class='bx bx-log-out-circle'></i>
                    SALIDA DE HUÉSPEDES
                </h1>

                <p class="section-subtitle">
                    Registre la salida del huésped para iniciar el proceso de limpieza de la habitación.
                </p>
            </div>
        </header>

        <div class="salidas-grid">

        <?php if(empty($registros)): ?>

            <div class="card-vacia">
                <i class='bx bx-hotel'></i>
                <h2>No existen habitaciones ocupadas</h2>
                <p>No hay huéspedes pendientes de registrar salida.</p>
            </div>

        <?php else: ?>

            <?php foreach($registros as $registro): ?>

            <div class="salida-card">

                <!-- HEADER -->
                <div class="salida-header">

                    <h2>
                        <i class='bx bx-bed'></i>
                        Habitación <?= htmlspecialchars($registro['numero']) ?>
                    </h2>

                    <span class="badge-<?= strtolower($registro['tipo']) ?>">
                        <?= htmlspecialchars($registro['tipo']) ?>
                    </span>

                </div>

                <!-- BODY -->
                <div class="salida-body">

                    <div class="info-item">
                        <i class='bx bx-user'></i>
                        <div>
                            <small>Huésped</small>
                            <strong><?= htmlspecialchars($registro['nombre_completo']) ?></strong>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class='bx bx-id-card'></i>
                        <div>
                            <small>DNI</small>
                            <strong><?= htmlspecialchars($registro['dni']) ?></strong>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class='bx bx-map'></i>
                        <div>
                            <small>Procedencia</small>
                            <strong><?= htmlspecialchars($registro['lugar_procedencia']) ?></strong>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class='bx bx-calendar'></i>
                        <div>
                            <small>Fecha de ingreso</small>
                            <strong><?= date("d/m/Y H:i", strtotime($registro['fecha_ingreso'])) ?></strong>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class='bx bx-time'></i>
                        <div>
                            <small>Días registrados</small>
                            <strong><?= htmlspecialchars($registro['dias_estadia']) ?></strong>
                        </div>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="salida-footer">

                    <form action="../../controllers/RegistrarSalidaController.php" method="POST">

                        <input type="hidden" name="id_registro" value="<?= $registro['id_registro']; ?>">

                        <button type="submit"
                                class="btn-salida"
                                onclick="return confirm('¿Registrar salida e iniciar limpieza?')">

                            <i class='bx bx-log-out-circle'></i>
                            Registrar Salida

                        </button>

                    </form>

                </div>

            </div>

            <?php endforeach; ?>

        <?php endif; ?>

        </div>

    </main>

</div>

</body>
</html>