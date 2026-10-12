<?php
include 'verificar_admin.php'; 
include '../includes/db.php'; 

// 1. Obtener mensajes (leido = 0 primero, luego por fecha)
$query_mensajes = new MongoDB\Driver\Query([], ['sort' => ['fecha' => -1]]);$mensajes = $conexion->executeQuery('ArbolBD.mensajes_soporte',$query_mensajes)->toArray();

// 2. Contar mensajes pendientes
$query_pendientes = new MongoDB\Driver\Query(['leido' => 0]);$total_pendientes = count($conexion->executeQuery('ArbolBD.mensajes_soporte',$query_pendientes)->toArray());

// 3. Usuarios y Roles
$query_usuarios = new MongoDB\Driver\Query([], ['sort' => ['id_rol' => 1, 'nombre_usuario' => 1]]);
$usuarios =$conexion->executeQuery('ArbolBD.usuarios', $query_usuarios)->toArray();$nombres_roles = [1 => 'Admin', 2 => 'Editor', 3 => 'Usuario'];

// 4. Historial
$query_historial = new MongoDB\Driver\Query([], ['sort' => ['fecha_limpieza' => -1], 'limit' => 5]);$historial = $conexion->executeQuery('ArbolBD.historial_limpieza',$query_historial)->toArray();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <title>Panel de Administración - Apadrina un Árbol</title>
    <style>
        /* Tus estilos originales se mantienen intactos */
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding: 40px 20px; }
        .admin-container { max-width: 1200px; margin: 0 auto; background: #ffffff; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .section-title { font-size: 28px; font-weight: 600; color: #1a1a1a; margin-bottom: 5px; display: flex; align-items: center; gap: 12px; }
        .section-subtitle { font-size: 14px; color: #777; margin-bottom: 30px; }
        .top-bar { display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 20px; }
        .btn-action { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-family: 'Poppins', sans-serif; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; }
        .btn-green { background-color: #5D8736; color: white; }
        .btn-green:hover { background-color: #4a6d2b; }
        .admin-table { width: 100%; border-collapse: collapse; margin-bottom: 50px; border-radius: 8px; overflow: hidden; border: 1px solid #eee; }
        .admin-table thead { background-color: #5D8736; color: white; }
        .admin-table th, .admin-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .admin-table tbody tr:hover { background-color: #f9fdf7; }
        .status-ok { color: #28a745; font-weight: 600; }
        .status-no { color: #dc3545; font-weight: 600; }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; text-transform: uppercase; font-weight: 600; }
        .rol-1 { background: #fce4ec; color: #d81b60; } 
        .rol-3 { background: #e8f5e9; color: #2e7d32; } 
        hr { border: 0; border-top: 1px solid #eee; margin: 40px 0; }
    </style>
</head>
<body>

<div class="admin-container">
    <div class="top-bar">
        <button onclick="limpiezaMasiva()" class="btn-action btn-green"><i class="ri-brush-line"></i> Limpiar Usuarios Fantasma</button>
        <a href="../index.php" class="btn-action btn-green"><i class="ri-home-4-line"></i> Regresar a inicio</a>
    </div>

    <h1 class="section-title">👥 Monitoreo de Accesos y Usuarios</h1>
    <p class="section-subtitle">Gestión de niveles de acceso y estado de verificación de la comunidad.</p>

    <table class="admin-table">
        <thead>
            <tr><th>ID</th><th>Usuario</th><th>Email</th><th>Rol</th><th>Verificado</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            <?php foreach($usuarios as$user): ?>
            <tr>
                <td><?php echo isset($user->id) ?$user->id : 'N/A'; ?></td>
                <td><strong><?php echo $user->nombre_usuario; ?></strong></td>
                <td><?php echo $user->email; ?></td>
                <td><span class="badge rol-<?php echo $user->id_rol; ?>"><?php echo isset($nombres_roles[$user->id_rol]) ? $nombres_roles[$user->id_rol] : 'Desconocido'; ?></span></td>
                <td><?php echo (isset($user->verificado) &&$user->verificado == 1) ? '<span class="status-ok">Sí</span>' : '<span class="status-no">No</span>'; ?></td>
                <td>
                    <form action="actualizar_rol.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id_usuario" value="<?php echo isset($user->id) ?$user->id : ''; ?>">
                        <select name="nuevo_rol" style="padding: 5px; border-radius: 4px; border: 1px solid #ddd;">
                            <option value="1" <?php if($user->id_rol == 1) echo 'selected'; ?>>Admin</option>
                            <option value="2" <?php if($user->id_rol == 2) echo 'selected'; ?>>Editor</option>
                            <option value="3" <?php if($user->id_rol == 3) echo 'selected'; ?>>Usuario</option>
                        </select>
                        <button type="submit" style="background: #28a745; color: white; border: none; padding: 6px 10px; cursor: pointer; border-radius: 4px; margin-left: 5px;">Actualizar</button>
                    </form>
                    <button onclick="cambiarPassword(<?php echo isset($user->id) ? $user->id : 0; ?>, '<?php echo$user->nombre_usuario; ?>')" style="background: #ffc107; color: black; border: none; padding: 6px 10px; cursor: pointer; border-radius: 4px; margin-left: 5px;"><i class="ri-key-line"></i></button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 5px;">
        <h2 class="section-title" style="margin-bottom: 0;">📬 Buzón de Soporte</h2>
        <?php if($total_pendientes > 0): ?>
            <span style="background-color: #ffc107; color: #000; padding: 5px 12px; border-radius: 20px; font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 5px;"><i class="ri-notification-3-line"></i> <?php echo $total_pendientes; ?> Pendientes</span>
        <?php else: ?>
            <span style="background-color: #e8f5e9; color: #2e7d32; padding: 5px 12px; border-radius: 20px; font-weight: 600; font-size: 14px;"><i class="ri-check-line"></i> Al día</span>
        <?php endif; ?>
    </div>
    <p class="section-subtitle">Dudas y reportes enviados por los usuarios desde la sección de ayuda.</p>
    
    <table class="admin-table">
        <thead>
            <tr><th>Remitente</th><th>Asunto</th><th>Mensaje</th><th>Fecha</th><th>Acciones</th></tr>
        </thead>
        <tbody>
        <?php foreach($mensajes as$msg): ?>
            <tr style="border-bottom: 1px solid #ddd; <?php echo ($msg->leido == 0) ? 'background-color: #f0fdf4; border-left: 4px solid #5D8736;' : ''; ?>">
                <td style="padding: 12px;"><strong><?php echo $msg->nombre; ?></strong><br><small><?php echo$msg->email; ?></small></td>
                <td style="padding: 12px;"><?php echo $msg->asunto; ?></td>
                <td style="padding: 12px;"><?php echo $msg->mensaje; ?></td>
                <td style="padding: 12px;"><?php echo isset($msg->fecha) ?$msg->fecha->toDateTime()->setTimezone(new DateTimeZone('America/Monterrey'))->format('d/m/Y H:i') : 'N/A'; ?></td>
                <td style="padding: 12px; text-align: center;">
                    <?php if($msg->leido == 0): ?>
                        <!-- Pasamos el _id de Mongo como string para que JS lo pueda procesar -->
                        <button onclick="marcarLeido('<?php echo (string)$msg->_id; ?>')" style="background: #ffc107; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-family: 'Poppins', sans-serif;"><i class="ri-check-double-line"></i> Marcar leído</button>
                    <?php else: ?>
                        <span style="color: #aaa; font-size: 12px;"><i class="ri-check-line"></i> Atendido</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
            <?php if(count($mensajes) == 0): ?>
                <tr><td colspan="5" style="text-align: center; color: #999; padding: 20px;">No hay mensajes nuevos.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <hr>
    <h2 class="section-title">📜 Registro Histórico de Limpiezas</h2>
    <p class="section-subtitle">Evidencia de mantenimiento del sistema (eliminación de usuarios fantasmas).</p>
    
    <table class="admin-table">
        <thead>
            <tr><th>Fecha y Hora</th><th>Usuarios Eliminados</th><th>Administrador Responsable</th></tr>
        </thead>
        <tbody>
            <?php foreach($historial as$row): ?>
            <tr>
                <td><?php echo isset($row->fecha_limpieza) ? (is_object($row->fecha_limpieza) ? $row->fecha_limpieza->toDateTime()->format('d/m/Y H:i') :$row->fecha_limpieza) : 'N/A'; ?></td>
                <td><strong><?php echo isset($row->cantidad_eliminados) ?$row->cantidad_eliminados : 0; ?></strong> cuentas</td>
                <td><?php echo isset($row->admin_que_limpio) ?$row->admin_que_limpio : 'Desconocido'; ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if(count($historial) == 0): ?>
                <tr><td colspan="3" style="text-align: center; color: #999; padding: 20px;">No se han realizado limpiezas aún.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/alertas.js"></script>
<script>
function limpiezaMasiva() {
    Swal.fire({
        title: '¿Ejecutar limpieza general?',
        text: "Se borrarán todos los usuarios que no verificaron su cuenta en más de 24 horas.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#5D8736',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, limpiar todo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "limpiar_usuario_fantasma.php";
        }
    })
}

const urlParams = new URLSearchParams(window.location.search);
const borrados = urlParams.get('limpieza');
if (borrados !== null) {
    Swal.fire({
        icon: 'success',
        title: '¡Sistema Limpio!',
        text: 'Se eliminaron ' + borrados + ' usuarios fantasma.',
        confirmButtonColor: '#5D8736'
    });
    window.history.replaceState({}, document.title, window.location.pathname);
}

function marcarLeido(id) {
    fetch(`marcar_mensaje_leido.php?id=${id}`)
    .then(response => {
        if(response.ok) {
            Swal.fire({
                title: '¡Hecho!',
                text: 'El mensaje ha sido marcado como atendido.',
                icon: 'success',
                confirmButtonColor: '#5D8736'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', 'No se pudo actualizar el mensaje.', 'error');
        }
    });
}
</script>
</body>
</html>