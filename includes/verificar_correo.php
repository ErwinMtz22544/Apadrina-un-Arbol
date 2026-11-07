<?php
// Archivo: includes/verificar_correo.php
include 'db.php';

// Verificamos que los datos lleguen por la URL
if (isset($_GET['email']) && isset($_GET['token'])) {
    
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Buscamos si existe un usuario con ese email y token que aún falte verificar
    $query_busqueda = new MongoDB\Driver\Query([
        'email' => $email, 
        'token' => $token, 
        'verificado' => 0
    ]);
    $usuarios_encontrados = $conexion->executeQuery('ArbolBD.usuarios', $query_busqueda)->toArray();

    if (count($usuarios_encontrados) > 0) {
        
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['email' => $email],
            ['$set' => ['verificado' => 1]]
        );
        
        try {
            $conexion->executeBulkWrite('ArbolBD.usuarios', $bulk);
            // Éxito
            header("Location: ../pages/login.php?status=cuenta_activada");
            exit();
        } catch (Exception $e) {
            // Error de base de datos
            header("Location: ../pages/login.php?error=error_db");
            exit();
        }

    } else {
        // El token no coincide, el correo ya fue verificado o el link expiró
        header("Location: ../pages/login.php?error=link_invalido");
        exit();
    }

} else {
    // Si alguien entra a este archivo sin darle clic al correo
    header("Location: ../pages/login.php");
    exit();
}
?>