<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['rol'])) {
    header("Location: ../../login.php");
    exit();
}

require_once '../../config/conexion.php';

// Contadores de habitaciones

$totalHabitaciones = $pdo->query("SELECT COUNT(*) FROM habitacion")->fetchColumn();

$totalDisponibles = $pdo->query("
SELECT COUNT(*)
FROM habitacion
WHERE estado='Disponible'
")->fetchColumn();

$totalOcupadas = $pdo->query("
SELECT COUNT(*)
FROM habitacion
WHERE estado='Ocupada'
")->fetchColumn();

$totalLimpieza = $pdo->query("
SELECT COUNT(*)
FROM habitacion
WHERE estado='Limpieza'
")->fetchColumn();


// Consulta principal del mapa

$sql = "

SELECT
    h.id_habitacion,
    h.numero,
    h.tipo,
    h.estado,

    rh.id_registro,
    rh.fecha_ingreso,
    rh.fecha_salida,

    hu.nombre_completo,
    hu.dni

FROM habitacion h

LEFT JOIN registro_huesped rh
ON rh.id_registro=(

        SELECT MAX(id_registro)

        FROM registro_huesped

        WHERE id_habitacion=h.id_habitacion

)

LEFT JOIN huesped hu
ON hu.id_huesped=rh.id_huesped

ORDER BY h.numero ASC

";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$habitaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Mapa de Habitaciones</title>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<link rel="stylesheet" href="../../public/css/estilos.css">

</head>

<body>

<div class="app-container">

<?php include '../includes/sidebar.php'; ?>


<main class="main-content">

    <header class="content-header">

        <div>

            <h1>

                <i class='bx bx-building-house'></i>

                MAPA DE HABITACIONES

            </h1>

            <p class="section-subtitle">

                Estado actual de todas las habitaciones del hospedaje.

            </p>

        </div>

    </header>


    <section class="filter-bar">

        <button type="button" class="btn-filter active" data-filter="all">

            <i class='bx bx-grid-alt'></i>

            Todas

            (<?= $totalHabitaciones ?>)

        </button>


        <button type="button" class="btn-filter disponible" data-filter="Disponible">

            <i class='bx bx-check-circle'></i>

            Disponibles

            (<?= $totalDisponibles ?>)

        </button>


        <button type="button" class="btn-filter ocupada" data-filter="Ocupada">

            <i class='bx bx-user'></i>

            Ocupadas

            (<?= $totalOcupadas ?>)

        </button>


        <button type="button" class="btn-filter limpieza" data-filter="Limpieza">

            <i class='bx bx-brush'></i>

            Limpieza

            (<?= $totalLimpieza ?>)

        </button>

    </section>


    <section class="rooms-grid">

<?php foreach($habitaciones as $hab): ?>

<?php

$estadoClase="";

$icono="";

switch($hab['estado']){

    case "Disponible":

        $estadoClase="card-disponible";
        $icono="bx-door-open";

    break;

    case "Ocupada":

        $estadoClase="card-ocupada";
        $icono="bx-user-check";

    break;

    case "Limpieza":

        $estadoClase="card-limpieza";
        $icono="bx-brush";

    break;

}

?>

<div class="room-card <?= $estadoClase ?>" data-state="<?= htmlspecialchars($hab['estado']) ?>">

    <div class="room-header">

        <span class="room-number">

            Habitación <?= htmlspecialchars($hab['numero']) ?>

        </span>

        <span class="room-type">

            <?= htmlspecialchars($hab['tipo']) ?>

        </span>

    </div>

    <div class="room-body">

        <i class='bx <?= $icono ?> room-icon'></i>

        <span class="status-text">

            <?= strtoupper($hab['estado']) ?>

        </span>

</div>

<div class="room-footer">

<?php if($hab['estado']=="Disponible"): ?>

    <a href="huespedes.php?id_habitacion=<?= $hab['id_habitacion'] ?>"
       class="btn-room-action action-checkin">

        <i class='bx bx-user-plus'></i>

        Registrar ingreso

    </a>

<?php elseif($hab['estado']=="Ocupada"): ?>

    <a href="salidas.php?id_registro=<?= $hab['id_registro'] ?>"
       class="btn-room-action action-details">

        <i class='bx bx-show'></i>

        Ver detalles

    </a>

<?php elseif($hab['estado']=="Limpieza"): ?>

    <a href="../../controllers/HabilitarHabitacionController.php?id=<?= $hab['id_habitacion'] ?>"
       class="btn-room-action action-clean">

        <i class='bx bx-check-double'></i>

        Habilitar habitación

    </a>

<?php endif; ?>

</div>

</div>

<?php endforeach; ?>

</section>

</main>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const filters = document.querySelectorAll('.btn-filter');
        const cards = document.querySelectorAll('.room-card');

        filters.forEach(button => {
            button.addEventListener('click', () => {
                const filter = button.dataset.filter;

                filters.forEach(btn => btn.classList.toggle('active', btn === button));

                cards.forEach(card => {
                    const state = card.dataset.state;
                    const shouldShow = filter === 'all' || state === filter;
                    card.style.display = shouldShow ? '' : 'none';
                });
            });
        });
    });
</script>

</body>

</html>