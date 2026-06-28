<?php
// ====================================================================
// CAPA DE DATOS: CONFIGURACIÓN DE CONEXIÓN A LA BASE DE DATOS (PDO)
// ARCHIVO: conexion.php
// ====================================================================

$host    = 'localhost';
$db      = 'bd_hospedaje_maritimos'; // Tu base de datos del hotel
$user    = 'root';                   // Usuario por defecto de XAMPP
$password = '';                      // Contraseña por defecto vacía en XAMPP
$charset = 'utf8mb4';                // Permite eñes y caracteres especiales

// Configuración de la cadena de conexión (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opciones avanzadas de seguridad y comportamiento de PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Reporta errores como excepciones
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve los datos como arreglos asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactiva la emulación para mayor seguridad anti-SQLi
];

try {
    // Inicializamos la instancia de conexión PDO
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (\PDOException $e) {
    // Si la conexión falla, detiene el sistema y muestra el error (útil en desarrollo)
    die("Error crítico de conexión a la Base de Datos: " . $e->getMessage());
}