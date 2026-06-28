<?php
// ====================================================================
// CAPA LÓGICA: CONTROLADOR DE AUTENTICACIÓN Y ROLES
// ARCHIVO: LoginController.php
// ====================================================================

// 1. Inicializamos de forma segura el manejo de sesiones en el servidor
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Importamos de manera relativa el componente de conexión de la capa de datos
require_once '../config/conexion.php';

// 3. Validamos que la solicitud de acceso sea estrictamente mediante el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Capturamos y limpiamos espacios vacíos de los inputs del formulario
    $username = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $password = isset($_POST['clave']) ? trim($_POST['clave']) : '';

    // Verificación de campos obligatorios
    if (empty($username) || empty($password)) {
        header("Location: ../login.php?error=campos_vacios");
        exit();
    }

    try {
        // 4. Preparamos la consulta SQL (Previene inyecciones SQL usando PDO)
        // Filtramos por usuario y validamos que su cuenta se encuentre "Activa"
        $sql = "SELECT id_usuario, nombre_completo, usuario, clave, rol, estado 
                FROM usuario 
                WHERE usuario = :usuario AND estado = 'Activo' 
                LIMIT 1";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        // Recuperamos el registro devuelto en forma de arreglo asociativo
        $user = $stmt->fetch();

        // 5. Proceso de validación de credenciales (Camino Feliz para PFS 1)
        // Nota: En este hito comparamos en texto plano directo según los datos semilla.
        if ($user && $password === $user['clave']) {
            
            // Regeneramos el ID de sesión por buenas prácticas de seguridad informática
            session_regenerate_id(true);

            // Guardamos las variables globales de sesión requeridas por el panel lateral (sidebar.php)
            $_SESSION['id_usuario']     = $user['id_usuario'];
            $_SESSION['nombre_completo'] = $user['nombre_completo'];
            $_SESSION['rol']             = $user['rol'];
            $_SESSION['usuario_login']   = $user['usuario'];

            // 6. Bifurcación y Enrutamiento según el nivel de Acceso y Rol
            if ($user['rol'] === 'Administradora') {
                // Redirección inmediata a la vista gerencial y financiera
                header("Location: ../views/seguridad/dashboard.php");
                exit();
            } else if ($user['rol'] === 'Recepcionista') {
                // Redirección inmediata a la pantalla operativa del mostrador
                header("Location: ../views/operaciones/mapa.php");
                exit();
            } else {
                // Si existe un rol no mapeado, destruye la sesión por seguridad
                session_destroy();
                header("Location: ../login.php?error=rol_no_autorizado");
                exit();
            }

        } else {
            // El usuario o la contraseña no coinciden con los registros de la BD
            header("Location: ../login.php?error=credenciales_incorrectas");
            exit();
        }

    } catch (PDOException $e) {
        // Captura fallas del motor y redirige informando el inconveniente técnico
        header("Location: ../login.php?error=falla_servidor");
        exit();
    }

} else {
    // Si intentan entrar escribiendo la URL del controlador en el navegador, se les bota al login
    header("Location: ../login.php");
    exit();
}