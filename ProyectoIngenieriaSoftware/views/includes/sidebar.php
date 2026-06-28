<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rol = $_SESSION['rol'] ?? 'Administradora';
$nombre_completo = $_SESSION['nombre_completo'] ?? 'Geraldine Mendoza';

$rutaActual = basename($_SERVER['PHP_SELF']);
?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<aside class="sidebar">

    <!-- CABECERA -->

    <div class="sidebar-header">

        <!-- LOGO -->
        <div class="logo-box">
            <img src="../../public/img/logo.png" alt="Logo" class="sidebar-logo">
        </div>

        <!-- USUARIO -->
        <div class="user-card">

            <div class="user-name-row">
                <i class='bx bx-user'></i>
                <span>
                    <?php echo htmlspecialchars($nombre_completo); ?>
                </span>
            </div>

            <div class="user-role">
                <?php echo htmlspecialchars($rol); ?>
            </div>

        </div>

    </div>

    <!-- MENÚ -->

    <div class="sidebar-menu">

        <span class="menu-title">MENÚ PRINCIPAL</span>

        <!-- SEGURIDAD -->
        <?php if($rol=="Administradora"): ?>

        <div class="menu-module">

            <div class="module-trigger" onclick="toggleModulo(this)">

                <div>
                    <i class='bx bx-lock-alt'></i>
                    <span>Gestión de Seguridad y Acceso</span>
                </div>

                <i class='bx bx-chevron-down arrow-icon'></i>

            </div>

            <div class="module-content">

                <a href="../seguridad/usuarios.php" class="<?= ($rutaActual=="usuarios.php")?'active':''; ?>">
                    <i class='bx bx-user-check'></i> Control de Usuarios
                </a>

                <a href="../seguridad/credenciales.php" class="<?= ($rutaActual=="credenciales.php")?'active':''; ?>">
                    <i class='bx bx-key'></i> Restablecer Contraseñas
                </a>

            </div>

        </div>

        <?php endif; ?>


        <!-- OPERACIONES -->
        <div class="menu-module <?= ($rol=="Recepcionista") ? 'active':''; ?>">

            <div class="module-trigger" onclick="toggleModulo(this)">

                <div>
                    <i class='bx bx-building-house'></i>
                    <span>Gestión de Procesos Operativos</span>
                </div>

                <i class='bx bx-chevron-down arrow-icon'></i>

            </div>

            <div class="module-content">

                <a href="../operaciones/mapa.php" class="<?= ($rutaActual=="mapa.php")?'active':''; ?>">
                    <i class='bx bx-grid-alt'></i> Mapa de Habitaciones
                </a>

                <a href="../operaciones/huespedes.php" class="<?= ($rutaActual=="huespedes.php")?'active':''; ?>">
                    <i class='bx bx-user-plus'></i> Registro de Huéspedes
                </a>

                <a href="../operaciones/consumos.php" class="<?= ($rutaActual=="consumos.php")?'active':''; ?>">
                    <i class='bx bx-cart'></i> Consumos Adicionales
                </a>

                <a href="../operaciones/salidas.php" class="<?= ($rutaActual=="salidas.php")?'active':''; ?>">
                    <i class='bx bx-log-out'></i> Salida de Huéspedes
                </a>

            </div>

        </div>


        <!-- REPORTES -->
        <div class="menu-module">

            <div class="module-trigger" onclick="toggleModulo(this)">

                <div>
                    <i class='bx bx-bar-chart-square'></i>
                    <span>Gestión de Análisis y Reportes</span>
                </div>

                <i class='bx bx-chevron-down arrow-icon'></i>

            </div>

            <div class="module-content">

                <?php if($rol=="Recepcionista"): ?>

                    <a href="../reportes/arqueo.php">
                        <i class='bx bx-wallet'></i> Arqueo Digital
                    </a>

                    <a href="../reportes/caja.php">
                        <i class='bx bx-money'></i> Cierre de Caja
                    </a>

                <?php endif; ?>

                <?php if($rol=="Administradora"): ?>

                    <a href="../reportes/historial.php">
                        <i class='bx bx-history'></i> Historial
                    </a>

                    <a href="../reportes/ocupacion.php">
                        <i class='bx bx-pie-chart-alt-2'></i> Ocupación
                    </a>

                    <a href="../reportes/dashboard.php">
                        <i class='bx bx-line-chart'></i> Dashboard
                    </a>

                <?php endif; ?>

            </div>

        </div>

    </div>

    <!-- FOOTER -->
    <div class="sidebar-footer">

        <a href="../../controllers/LogoutController.php" class="btn-logout">
            <i class='bx bx-log-out'></i>
            <span>Cerrar sesión</span>
        </a>

    </div>

</aside>

<script>
    function toggleModulo(element){

        const modulo = element.parentElement;

        document.querySelectorAll('.menu-module').forEach(m => {
            if(m !== modulo){
                m.classList.remove('active');
            }
        });

        modulo.classList.toggle('active');
    }
</script>