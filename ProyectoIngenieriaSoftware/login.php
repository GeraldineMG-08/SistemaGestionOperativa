<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema - Hospedaje Marítimos</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="public/css/estilos.css">
</head>
<body class="login-body">

    <div class="login-container">
        
        <div class="login-header">
            <img src="public/img/logo2.png" alt="Logo Hospedaje Marítimos" class="login-logo">
            <h1 class="login-title">Inicio de Sesión</h1>
            <p class="login-subtitle">Sistema de Gestión Operativa "Hospedaje Marítimos"</p>
        </div>

        <form action="controllers/LoginController.php" method="POST" class="login-form">
            
            <div class="form-group">
                <label for="usuario" class="form-label">Usuario</label>
                <div class="input-icon-wrapper">
                    <i class='bx bx-user input-icon'></i>
                    <input type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario" required class="form-input" autocomplete="off">
                </div>
            </div>

            <div class="form-group">
                <label for="clave" class="form-label">Contraseña</label>
                <div class="input-icon-wrapper">
                    <i class='bx bx-key input-icon'></i>
                    <input type="password" id="clave" name="clave" placeholder="••••••••" required class="form-input">
                </div>
            </div>

            <button type="submit" class="btn-login">
                <span>INGRESAR AL SISTEMA</span> <i class='bx bx-log-in-circle'></i>
            </button>

            <div class="login-extra">
                <a href="#" class="link-forgot" onclick="alert('Funcionalidad en desarrollo para PFS 2: Contacte al Administrador.')">¿Olvidaste tu contraseña?</a>
            </div>

        </form>

    </div>

</body>
</html>