<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Seguridad
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/conexion.php";

//Cargar huespedes para autocompletar

$sqlHuespedes = "SELECT dni, nombre_completo, lugar_procedencia FROM huesped";
$stmt = $pdo->prepare($sqlHuespedes);
$stmt->execute();
$huespedes = $stmt->fetchAll();

$huespedesJson = json_encode($huespedes);


$rol = $_SESSION['rol'];
$nombre = $_SESSION['nombre_completo'];

// Detectar si viene desde mapa (habitación precargada)

$id_habitacion_seleccionada = $_GET['id_habitacion'] ?? null;

$habitacionSeleccionada = null;
if ($id_habitacion_seleccionada) {
    $sqlHabSel = "SELECT * FROM habitacion WHERE id_habitacion = :id LIMIT 1";
    $stmt = $pdo->prepare($sqlHabSel);
    $stmt->bindParam(':id', $id_habitacion_seleccionada, PDO::PARAM_INT);
    $stmt->execute();
    $habitacionSeleccionada = $stmt->fetch();
}

// Cargar habitaciones disponibles

$sqlHabitaciones = "SELECT * FROM habitacion WHERE estado = 'Disponible'";
$stmt = $pdo->prepare($sqlHabitaciones);
$stmt->execute();
$habitaciones = $stmt->fetchAll();

$tiposHabitacion = [];
foreach ($habitaciones as $h) {
    $tiposHabitacion[$h['tipo']] = true;
}


// Cargar tarifas

$sqlTarifas = "SELECT * FROM tarifa";
$stmt = $pdo->prepare($sqlTarifas);
$stmt->execute();
$tarifas = $stmt->fetchAll();


function obtenerTarifa($pdo, $tipo) {

    $sql = "SELECT * FROM tarifa WHERE tipo_habitacion = :tipo LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->execute();

    return $stmt->fetch();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Huéspedes</title>

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/estilos.css">
</head>

<body>

<div class="app-container">

    <!-- SIDEBAR -->
    <?php include "../includes/sidebar.php"; ?>

    <!-- CONTENIDO -->
    <main class="main-content">

        <header class="content-header">
            <div>
                <h1><i class='bx bx-user-circle'></i> REGISTRO DE HOSPEDAJE</h1>
                <p class="section-subtitle">Registro de ingreso de huéspedes en tiempo real</p>
            </div>
        </header>

        <form method="POST" action="../../controllers/RegistrarHuespedController.php" class="form-huesped" onsubmit="return validarFormulario()">



            <!-- DATOS HUESPED -->
                <div class="card card-grid-3">

                    <h3><i class='bx bx-search'></i> Búsqueda de Huésped</h3>

                    <div class="form-fields">

                        <div class="field-group">
                            <label>DNI</label>
                            <input name="dni" type="text" id="buscador_dni" placeholder="Buscar DNI">
                            <div id="res_dni" class="search-box"></div>
                        </div>

                        <div class="field-group">
                            <label>Nombre</label>
                            <input name="nombre_completo" type="text" id="buscador_nombre" placeholder="Buscar nombre">
                            <div id="res_nombre" class="search-box"></div>
                        </div>

                        <div class="field-group">
                            <label>Procedencia</label>
                            <input name="procedencia" type="text" id="buscador_proc" placeholder="Buscar procedencia">
                            <div id="res_proc" class="search-box"></div>
                        </div>

                    </div>
                </div>

            <!-- HABITACIÓN -->
            <div class="card card-grid-2">

                <h3><i class='bx bx-door-open'></i> Habitación</h3>

                <div class="form-fields">
                    <div class="field-group">
                        <label for="tipo_habitacion">Tipo de habitación</label>
                        <select name="tipo_habitacion" id="tipo_habitacion" <?= $id_habitacion_seleccionada ? 'disabled' : '' ?> required>
                            <option value="">Seleccione tipo</option>
                            <?php foreach ($tiposHabitacion as $tipo => $_): ?>
                                <option value="<?= htmlspecialchars($tipo) ?>" <?= ($id_habitacion_seleccionada && $habitacionSeleccionada && $habitacionSeleccionada['tipo'] === $tipo) ? 'selected' : '' ?> >
                                    <?= htmlspecialchars($tipo) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($id_habitacion_seleccionada && $habitacionSeleccionada): ?>
                            <input type="hidden" name="tipo_habitacion" value="<?= htmlspecialchars($habitacionSeleccionada['tipo']) ?>">
                        <?php endif; ?>
                    </div>
                    <div class="field-group">
                        <label for="habitacion_display">Número de habitación</label>
                        <select id="habitacion_display" <?= $id_habitacion_seleccionada ? 'disabled' : '' ?>>
                            <?php if ($id_habitacion_seleccionada && $habitacionSeleccionada): ?>
                                <option value="<?= $habitacionSeleccionada['id_habitacion'] ?>" data-tipo="<?= htmlspecialchars($habitacionSeleccionada['tipo']) ?>" selected>
                                    <?= $habitacionSeleccionada['numero'] ?>
                                </option>
                            <?php else: ?>
                                <option value="">Seleccione tipo</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="id_habitacion" id="id_habitacion" value="<?= $id_habitacion_seleccionada && $habitacionSeleccionada ? $habitacionSeleccionada['id_habitacion'] : '' ?>">

            </div>

            <!-- FECHAS -->
            <div class="card card-grid-2">

                <h3><i class='bx bx-calendar'></i> Fechas</h3>

                <div class="form-fields">
                    <div class="field-group">
                        <label for="fecha_ingreso">Ingreso</label>
                        <input type="datetime-local" name="fecha_ingreso" id="fecha_ingreso" required lang="es">
                    </div>
                    <div class="field-group">
                        <label for="fecha_salida">Salida</label>
                        <input type="datetime-local" name="fecha_salida" id="fecha_salida" required lang="es">
                    </div>
                </div>

            </div>

            <!-- PAGO -->
            <div class="card card-grid-2">

                <h3><i class='bx bx-credit-card'></i> Pago</h3>

                <div class="form-fields">
                    <div class="field-group">
                        <label for="medio_pago">Medio de pago</label>
                        <select name="medio_pago" id="medio_pago" required>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Yape">Yape</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label for="dias_estadia_input">Días de estadía</label>
                        <input type="number" id="dias_estadia_input" min="1" value="1" required>
                    </div>
                    <div class="field-group">
                        <label for="personas_extra">Personas extra</label>
                        <input type="number" name="personas_extra" id="personas_extra" min="0" value="0">
                    </div>
                    <div class="field-group">
                        <label for="costo_adicional">Costo adicional</label>
                        <input type="number" name="costo_adicional" id="costo_adicional" min="0" step="0.01" value="0.00">
                    </div>
                </div>

                <div class="total-row">
                    <span>Total estimado</span>
                    <span id="totalEstimadoText">S/. 0.00</span>
                </div>

            </div>

            <input type="hidden" name="id_tarifa" id="id_tarifa">
            <input type="hidden" name="monto_total" id="monto_total">
            <input type="hidden" name="dias_estadia" id="dias_estadia">

            <!-- BOTÓN -->
            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class='bx bx-save'></i> Registrar Ingreso
                </button>
            </div>

        </form>

    </main>

</div>

<script>

    const huespedes = <?= $huespedesJson ?>;

    const dniInput = document.getElementById("buscador_dni");
    const nombreInput = document.getElementById("buscador_nombre");
    const procInput = document.getElementById("buscador_proc");

    const bDni = document.getElementById("buscador_dni");
    const bNombre = document.getElementById("buscador_nombre");
    const bProc = document.getElementById("buscador_proc");

    const rDni = document.getElementById("res_dni");
    const rNombre = document.getElementById("res_nombre");
    const rProc = document.getElementById("res_proc");

    function filtrar(query, campo) {

        if (query.length < 1) return [];

        return huespedes.filter(h => {
            return h[campo].toLowerCase().includes(query.toLowerCase());
        }).slice(0, 5);
    }

    function mostrarResultados(lista, contenedor, tipo) {

        contenedor.innerHTML = "";

        lista.forEach(h => {

            let div = document.createElement("div");
            div.classList.add("resultado-item");

            div.innerHTML = `
                <strong>${h.nombre_completo}</strong><br>
                DNI: ${h.dni} <br>
                <small>${h.lugar_procedencia}</small>
            `;

            div.onclick = function () {
                dniInput.value = h.dni;
                nombreInput.value = h.nombre_completo;
                procInput.value = h.lugar_procedencia;

                rDni.innerHTML = "";
                rNombre.innerHTML = "";
                rProc.innerHTML = "";
            };

            contenedor.appendChild(div);
        });
    }

    // DNI
    bDni.addEventListener("keyup", function () {
        const res = filtrar(this.value, "dni");
        mostrarResultados(res, rDni);
    });

    // NOMBRE
    bNombre.addEventListener("keyup", function () {
        const res = filtrar(this.value, "nombre_completo");
        mostrarResultados(res, rNombre);
    });

    // PROCEDENCIA
    bProc.addEventListener("keyup", function () {
        const res = filtrar(this.value, "lugar_procedencia");
        mostrarResultados(res, rProc);
    });



    const tipoHabitacion = document.getElementById("tipo_habitacion");
    const habitacionDisplay = document.getElementById("habitacion_display");
    const ingreso = document.getElementById("fecha_ingreso");
    const salida = document.getElementById("fecha_salida");
    const diasEstadiaInput = document.getElementById("dias_estadia_input");
    const personasExtraInput = document.getElementById("personas_extra");
    const costoAdicionalInput = document.getElementById("costo_adicional");
    const totalEstimadoText = document.getElementById("totalEstimadoText");

    const diasInput = document.getElementById("dias_estadia");
    const totalInput = document.getElementById("monto_total");
    const tarifaInput = document.getElementById("id_tarifa");
    const idHabitacionHidden = document.getElementById("id_habitacion");

    const tarifas = <?php echo json_encode($tarifas); ?>;
    const habitacionesPorTipo = <?php echo json_encode(array_reduce($habitaciones, function($acc, $h) {
        $acc[$h['tipo']][] = $h;
        return $acc;
    }, [])); ?>;


    // Calcular dias
    function calcular() {
        let days = parseInt(diasEstadiaInput.value) || 0;

        if (ingreso.value && salida.value) {
            let f1 = new Date(ingreso.value);
            let f2 = new Date(salida.value);
            let diff = (f2 - f1) / (1000 * 60 * 60 * 24);

            if (diff > 0) {
                days = diff;
                diasEstadiaInput.value = diff;
            }
        }

        if (days <= 0) {
            diasInput.value = 0;
            totalInput.value = 0;
            totalEstimadoText.textContent = 'S/. 0.00';
            return;
        }

        diasInput.value = days;

        const selected = habitacionDisplay.options[habitacionDisplay.selectedIndex];
        const tipo = selected ? selected.getAttribute("data-tipo") : tipoHabitacion.value;
        const tarifa = tipo ? tarifas.find(t => t.tipo_habitacion === tipo) : null;

        if (!tarifa) {
            totalInput.value = 0;
            totalEstimadoText.textContent = 'S/. 0.00';
            return;
        }

        tarifaInput.value = tarifa.id_tarifa;

        const totalBase = days * parseFloat(tarifa.precio_base);
        const extra = parseFloat(costoAdicionalInput.value) || 0;
        const total = totalBase + extra;

        totalInput.value = total.toFixed(2);
        totalEstimadoText.textContent = 'S/. ' + total.toFixed(2);
    }

    function actualizarHabitaciones() {
        const tipo = tipoHabitacion.value;
        habitacionDisplay.innerHTML = '<option value="">Seleccione habitación</option>';
        idHabitacionHidden.value = '';

        const preloaded = habitacionDisplay.disabled; // si vino desde mapa está deshabilitado

        if (!tipo || !habitacionesPorTipo[tipo]) {
            habitacionDisplay.disabled = preloaded;
            return;
        }

        // Poblar opciones disponibles para el tipo
        habitacionesPorTipo[tipo].forEach(h => {
            const option = document.createElement('option');
            option.value = h.id_habitacion;
            option.dataset.tipo = h.tipo;
            option.textContent = h.numero;
            habitacionDisplay.appendChild(option);
        });

        habitacionDisplay.disabled = false;
    }

    ingreso.addEventListener("change", calcular);
    salida.addEventListener("change", calcular);
    tipoHabitacion.addEventListener("change", function() {
        actualizarHabitaciones();
        calcular();
    });

    habitacionDisplay.addEventListener('change', function() {
        idHabitacionHidden.value = this.value || '';
        calcular();
    });

    diasEstadiaInput.addEventListener("change", calcular);
    personasExtraInput.addEventListener("change", calcular);
    costoAdicionalInput.addEventListener("input", calcular);


    if (tipoHabitacion.value && habitacionDisplay.disabled === false) {
        actualizarHabitaciones();
    }

    document.getElementById('btnBuscar').addEventListener('click', function() {
        const event = new Event('keyup');
        buscador.dispatchEvent(event);
    });



    function validarFormulario() {

        let dias = document.getElementById("dias_estadia").value;

        if (dias <= 0) {
            alert("La fecha de salida debe ser mayor a la de ingreso");
            return false;
        }

        return true;
    }

</script>

</body>
</html>

