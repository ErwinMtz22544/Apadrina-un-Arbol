<?php
// Ocultamos advertencias menores al usuario, pero las registramos (Buena práctica de seguridad)
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
include("../includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
   // MANEJO DE SESIÓN 
if (!isset($_SESSION['id_usuario'])) {
    // Si no hay ID, algo salió mal en el login, mandamos a loguearse de nuevo al usuario
    header("Location: login.php?error=sesion_invalida");
    exit();
}

//omamos el ID 
$id_usuario = $_SESSION['id_usuario'];

    // 2. CAPTURA DE DATOS INTELIGENTE (Evita errores del modal "Donar")
    // Usamos el operador '??' para asignar valores por defecto si el campo no existe
    $id_arbol      = $_POST['id_arbol'] ?? 0;
    
    // Si es una donación, no mandan nombre, le ponemos uno por defecto:
    $nombre_arbol  = $_POST['nombre_personalizado'] ?? 'Aporte de Mantenimiento'; 
    $tipo_adopcion = $_POST['tipo_adopcion'] ?? 'Individual';
    $monto         = $_POST['monto'] ?? 0;
    $metodo_pago   = $_POST['metodo_pago'] ?? 'Tarjeta';

    // 3. VALIDACIÓN DE PAGO (Simulación)
    if ($metodo_pago === 'Tarjeta') {
        $num_tarjeta = $_POST['num_tarjeta'] ?? '';
        if (empty($num_tarjeta)) {
            header("Location: arbol-detalle.php?id=$id_arbol&error=datos_incompletos");
            exit();
        }
    }

    // 4. INSERCIÓN BLINDADA (Consultas Preparadas contra SQL Injection)
    // Usamos '?' en lugar de inyectar las variables directamente
    $query = "INSERT INTO apadrinamientos (id_usuario, id_arbol, nombre_arbol, tipo_adopcion, monto, metodo_pago) 
              VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($query);

    if ($stmt) {
        // Enlazamos las variables a los '?'. 
        // 'i' = integer (número entero), 's' = string (texto), 'd' = double (decimales)
        // Orden: id_usuario(i), id_arbol(i), nombre(s), tipo(s), monto(d), metodo(s) => "iissds"
        $stmt->bind_param("iissds", $id_usuario, $id_arbol, $nombre_arbol, $tipo_adopcion, $monto, $metodo_pago);

        if ($stmt->execute()) {
            // ÉXITO: Regresamos con la bandera 'status=adoptado' para que SweetAlert salte
            header("Location: arbol-detalle.php?id=$id_arbol&status=adoptado");
            exit();
        } else {
            // Guardamos el error real en un log del servidor (no lo mostramos al usuario)
            error_log("Error de DB en adopción: " . $stmt->error);
            echo "Ocurrió un error al procesar tu adopción. Inténtalo más tarde.";
        }
        $stmt->close();
    } else {
        error_log("Error preparando la consulta: " . $conexion->error);
        echo "Error interno del servidor.";
    }

} else {
    // Si alguien intenta entrar a este archivo escribiendo la URL directamente, lo pateamos al catálogo
    header("Location: catalogo.php");
    exit();
}
?>