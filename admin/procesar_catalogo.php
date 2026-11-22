<?php
session_start();
include("../includes/db.php");

// Seguridad
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] != 1)) {
    die("Acceso denegado");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'crear') {
        $opciones = ['sort' => ['id_arbol' => -1], 'limit' => 1];
        $queryMaxId = new MongoDB\Driver\Query([], $opciones);
        $ultimo = $conexion->executeQuery("ArbolBD.arboles", $queryMaxId)->toArray();
        $nuevo_id_numerico = (count($ultimo) > 0 && isset($ultimo[0]->id_arbol)) ? $ultimo[0]->id_arbol + 1 : 1;

        $nuevoArbol = [
            'id_arbol'           => $nuevo_id_numerico,
            'nombre_comun'       => trim($_POST['nombre_comun'] ?? ''),
            'nombre_cientifico'  => trim($_POST['nombre_cientifico'] ?? ''),
            'descripcion'        => trim($_POST['descripcion'] ?? ''),
            'imagen'             => trim($_POST['imagen'] ?? ''),
            'altura'             => trim($_POST['altura'] ?? ''),
            'epoca'              => trim($_POST['epoca'] ?? ''),
            'descripcion_larga'  => trim($_POST['descripcion_larga'] ?? ''),
            'datos_interesantes' => trim($_POST['datos_interesantes'] ?? '')
        ];

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert($nuevoArbol);
        $conexion->executeBulkWrite('ArbolBD.arboles', $bulk);
        
        header("Location: gestion_catalogo.php");
        exit();

    } elseif ($accion === 'editar') {
        
        // Limpiamos espacios invisibles del ID que a veces causan fallos en MongoDB
        $id_mongo = trim($_POST['id_mongo'] ?? '');
        
        if(!empty($id_mongo)) {
            try {
                $arbolActualizado = [
                    'nombre_comun'       => trim($_POST['nombre_comun'] ?? ''),
                    'nombre_cientifico'  => trim($_POST['nombre_cientifico'] ?? ''),
                    'descripcion'        => trim($_POST['descripcion'] ?? ''),
                    'imagen'             => trim($_POST['imagen'] ?? ''),
                    'altura'             => trim($_POST['altura'] ?? ''),
                    'epoca'              => trim($_POST['epoca'] ?? ''),
                    'descripcion_larga'  => trim($_POST['descripcion_larga'] ?? ''),
                    'datos_interesantes' => trim($_POST['datos_interesantes'] ?? '')
                ];

                $bulk = new MongoDB\Driver\BulkWrite;
                // Hacemos el update a ese _id específico
                $bulk->update(
                    ['_id' => new MongoDB\BSON\ObjectId($id_mongo)],
                    ['$set' => $arbolActualizado]
                );
                $conexion->executeBulkWrite('ArbolBD.arboles', $bulk);
                
                header("Location: gestion_catalogo.php");
                exit();
                
            } catch (Exception $e) {
                // Si falla, en lugar de no hacer nada, te mostrará el error en rojo
                die("<h3 style='color:red;'>Error al actualizar en MongoDB: " . $e->getMessage() . "</h3>");
            }
        } else {
            die("<h3 style='color:red;'>Error: No se recibió el ID del árbol para editar.</h3>");
        }

    } elseif ($accion === 'borrar') {
        $id_mongo = trim($_POST['id_arbol'] ?? '');
        if(!empty($id_mongo)) {
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->delete(['_id' => new MongoDB\BSON\ObjectId($id_mongo)]);
            $conexion->executeBulkWrite('ArbolBD.arboles', $bulk);
        }
        header("Location: gestion_catalogo.php");
        exit();
    }
}
?>