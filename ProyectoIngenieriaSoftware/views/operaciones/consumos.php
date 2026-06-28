<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/conexion.php";

// Obtener registros ocupados

$sql = "SELECT rh.id_registro, h.id_huesped, h.nombre_completo, hab.numero, hab.id_habitacion, hab.tipo
        FROM registro_huesped rh
        INNER JOIN huesped h ON rh.id_huesped = h.id_huesped
        INNER JOIN habitacion hab ON rh.id_habitacion = hab.id_habitacion
        ORDER BY rh.id_registro DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$registros = $stmt->fetchAll();

$registrosJson = json_encode($registros);

// Productos disponibles
$sql = "SELECT * FROM producto WHERE stock > 0";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$productos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consumos</title>

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/estilos.css">
</head>

<body>

<div class="app-container">

<?php include "../includes/sidebar.php"; ?>

<main class="main-content">

    <header class="content-header">
        <div>
            <h1><i class='bx bx-cart-alt'></i> REGISTRO DE CONSUMOS</h1>
            <p class="section-subtitle">Consumos instantáneos por habitación</p>
        </div>
    </header>

    <form method="POST" action="../../controllers/RegistrarConsumoController.php" class="form-huesped">

        <!-- HUESPED ACTIVO -->
        <div class="card">

            <h3><i class='bx bx-user'></i> Huésped Activo</h3>

            <input type="text" id="buscador_huesped" class="search-input" placeholder="Buscar por nombre o habitación" autocomplete="off">
            <div id="resultados_huespedes" class="search-box"></div>

            <input type="hidden" name="id_registro" id="id_registro" required>
            <div id="datos_huespedes" style="display:none; margin-top:12px; padding:12px; background:#F8FAFC; border-radius:12px;">
                <div><strong>Nombre:</strong> <span id="nombre_huesped"></span></div>
                <div><strong>Habitación:</strong> <span id="numero_habitacion"></span> (<span id="tipo_habitacion"></span>)</div>
            </div>

        </div>

        <!-- PRODUCTO Y CANTIDAD -->
        <div class="card card-grid-2">

            <h3><i class='bx bx-package'></i> Producto y Cantidad</h3>

            <div class="form-fields">
                <div class="field-group">
                    <label for="id_producto">Producto</label>
                    <select name="id_producto" id="id_producto" required>
                        <option value="">Seleccione producto</option>
                        <?php foreach ($productos as $p): ?>
                            <option value="<?= $p['id_producto'] ?>" data-precio="<?= $p['precio_venta'] ?>" data-stock="<?= $p['stock'] ?>">
                                <?= $p['nombre_producto'] ?> - S/<?= $p['precio_venta'] ?> (Stock: <?= $p['stock'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" name="cantidad" id="cantidad" min="1" value="1" required>
                </div>
            </div>

        </div>

        <!-- MÉTODO DE PAGO -->
        <div class="card">

            <h3><i class='bx bx-credit-card'></i> Pago Instantáneo</h3>

            <select name="medio_pago" required>
                <option value="Efectivo">Efectivo</option>
                <option value="Tarjeta">Tarjeta</option>
                <option value="Yape">Yape</option>
            </select>

        </div>

        <div class="form-actions">
            <button class="btn-primary" type="submit">
                <i class='bx bx-save'></i> Registrar Consumo
            </button>
        </div>

    </form>

</main>

</div>

<script>

    // Autocompletado

    const registros = <?= $registrosJson; ?>;
    const buscador = document.getElementById("buscador_huesped");
    const resultados = document.getElementById("resultados_huespedes");
    const datosHuespedes = document.getElementById("datos_huespedes");
    const idRegistro = document.getElementById("id_registro");
    const nombreSpan = document.getElementById("nombre_huesped");
    const numeroSpan = document.getElementById("numero_habitacion");
    const tipoSpan = document.getElementById("tipo_habitacion");

    buscador.addEventListener("keyup", function() {
        const query = this.value.toLowerCase();

        if (query.length < 1) {
            resultados.innerHTML = "";
            return;
        }

        resultados.innerHTML = "";

        registros.forEach(r => {
            const nombreMatch = r.nombre_completo.toLowerCase().includes(query);
            const habitacionMatch = r.numero.toString().includes(query);

            if (nombreMatch || habitacionMatch) {
                const div = document.createElement("div");
                div.classList.add("resultado-item");
                div.innerHTML = `
                    <strong>${r.nombre_completo}</strong><br>
                    Habitación ${r.numero} (${r.tipo})
                `;
                div.addEventListener("click", function() {
                    seleccionarHuesped(r);
                });
                resultados.appendChild(div);
            }
        });
    });

    function seleccionarHuesped(registro) {
        idRegistro.value = registro.id_registro;
        buscador.value = `${registro.nombre_completo} - Hab ${registro.numero}`;
        
        nombreSpan.textContent = registro.nombre_completo;
        numeroSpan.textContent = registro.numero;
        tipoSpan.textContent = registro.tipo;
        
        datosHuespedes.style.display = "block";
        resultados.innerHTML = "";
    }

</script>

</body>
</html>
