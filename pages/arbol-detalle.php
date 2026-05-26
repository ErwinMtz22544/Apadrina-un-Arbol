<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("../includes/header.php"); 
include("../includes/db.php");

// 1. Obtener datos del árbol
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id > 0){
    $stmt = $conexion->prepare("SELECT * FROM arboles WHERE id_arbol = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $arbol = $resultado->fetch_assoc();
    if(!$arbol) { header("Location: catalogo.php"); exit(); }
} else { header("Location: catalogo.php"); exit(); }
?>

<style>
    :root { --verde-fuerte: #4a6733; --verde-claro: #5D8736; --gris-borde: #ddd; }
    
    .container-section-arbol { display: flex; gap: 40px; margin-top: 80px; align-items: flex-start; }
    .column-left { flex: 1.2; }
    .column-right { flex: 1; }
    .main-img { width: 100%; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

    /* Acordeones */
    details { border-bottom: 1px solid #eee; padding: 20px 0; cursor: pointer; }
    summary { font-weight: bold; list-style: none; display: flex; justify-content: space-between; align-items: center; }
    .btn-donar-verde { background: var(--verde-fuerte); color: white; border: none; padding: 10px 25px; border-radius: 8px; margin-top: 15px; cursor: pointer; font-weight: bold; }

    /* Modales */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: none; align-items: center; justify-content: center; }
    .modal-card { 
        background: white; 
        width: 500px; 
        border-radius: 20px; 
        padding: 35px; 
        position: relative; 
        box-sizing: border-box; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
        max-height: 90vh; 
        overflow-y: auto; 
    }
    .modal-card::-webkit-scrollbar { width: 8px; }
    .modal-card::-webkit-scrollbar-thumb { background: var(--verde-claro); border-radius: 10px; }

    .close-x { 
        position: absolute; 
        right: 15px; 
        top: 15px; 
        font-size: 24px; 
        border: none; 
        background: none; 
        cursor: pointer; 
        color: #888; 
        transition: 0.3s; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        padding: 5px; 
    }
    .close-x:hover { color: var(--verde-fuerte); transform: scale(1.1); }

    .label-bold { display: block; font-size: 14px; font-weight: bold; margin: 15px 0 8px; color: #333; }
    .input-field { width: 100%; padding: 12px; border: 1px solid var(--gris-borde); border-radius: 10px; box-sizing: border-box; font-size: 14px; }
    .row-inputs { display: flex; gap: 15px; margin-bottom: 10px; }
    .group-input { flex: 1; }
    .group-input label { display: block; font-size: 12px; font-weight: 600; color: #666; margin-bottom: 4px; }

    .metodo-pago-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 20px; }
    .pago-card { border: 1px solid var(--gris-borde); border-radius: 12px; padding: 15px 5px; text-align: center; cursor: pointer; transition: 0.3s; background: white; display: flex; flex-direction: column; align-items: center; }
    .pago-card input { display: none; }
    .pago-card i { display: block; font-size: 24px; color: var(--verde-fuerte); margin-bottom: 5px; }
    .pago-card span { font-size: 13px; font-weight: 600; color: #333; }
    .pago-card:has(input:checked) { border: 2px solid var(--verde-fuerte); background: #f0fdf4; }

    .selector-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 20px; }
    .option-card { border: 1px solid var(--gris-borde); border-radius: 12px; padding: 12px; text-align: center; cursor: pointer; transition: 0.3s; }
    .option-card input { display: none; }
    .option-card i { display: block; font-size: 22px; color: var(--verde-claro); margin-bottom: 5px; }
    .option-card span { font-size: 12px; font-weight: 600; }
    .option-card:has(input:checked) { border-color: var(--verde-claro); background: #f0fdf4; box-shadow: 0 0 0 1px var(--verde-claro); }

    .btn-confirm-pago { width: 100%; background: var(--verde-fuerte); color: white; border: none; padding: 16px; border-radius: 12px; font-weight: bold; font-size: 16px; cursor: pointer; margin-top: 15px; transition: 0.3s; }
</style>

<nav class="breadcrumbs container">
    <a href="../index.php"> Inicio</a> / <a href="../pages/catalogo.php">Catálogo</a> / <span class="current"><?php echo $arbol['nombre_comun']; ?></span>
</nav>

<main class="container container-section-arbol">
    <div class="column-left">
        <img src="../assets/img/arboles/<?php echo $arbol['imagen']; ?>" class="main-img">
        <div style="margin-top: 30px;">
            <h4 style="color: var(--verde-claro); font-size: 1.5rem;">Datos <strong>interesantes</strong></h4>
            <p style="line-height: 1.6; color: #444;"><?php echo htmlspecialchars($arbol['datos_interesantes']); ?></p>
        </div>
    </div>

    <div class="column-right">
        <h1 style="font-size: 2.2rem; margin-bottom: 10px;"><?php echo htmlspecialchars($arbol['nombre_cientifico']); ?></h1>
        <p style="color: #777; margin-bottom: 30px;">¡Gracias por dar este paso!</p>
        
        <div style="display: flex; gap: 10px; margin-bottom: 40px;">
            <button onclick="abrirModal('modalAdopcion')" style="background: #1a3c1a; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; flex: 1; font-weight: bold; font-size: 1rem;">
                + Adoptar árbol
            </button>
        </div>

        <details open>
            <summary>Descripción <i class="ri-arrow-down-s-line"></i></summary>
            <div style="padding: 15px 0; color: #555; line-height: 1.7;"><?php echo htmlspecialchars($arbol['descripcion_larga']); ?></div>
        </details>

        <details>
            <summary>¿No quieres adoptar, solo donar? <i class="ri-arrow-down-s-line"></i></summary>
            <div style="padding: 15px 0;">
                <p style="color: #666;">Tu donación hace posible el cuidado continuo de este ejemplar.</p>
                <button class="btn-donar-verde" onclick="abrirModal('modalDonar')">+ Donar</button>
            </div>
        </details>
    </div>
</main>

<div id="modalAdopcion" class="modal-overlay">
    <div class="modal-card">
        <button type="button" class="close-x" onclick="cerrarModal('modalAdopcion')"><i class="ri-close-line"></i></button>
        <h3 style="color: var(--verde-claro);">Apadrina tu próximo Árbol</h3>
        
        <form action="procesar_adopcion.php" method="POST">
            <input type="hidden" name="id_arbol" value="<?php echo $arbol['id_arbol']; ?>">
            
            <label class="label-bold">Nombre para tu futuro árbol</label>
            <input type="text" name="nombre_personalizado" placeholder="Ej. Roble" required class="input-field name-restrict" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+">

            <label class="label-bold">¿Cómo deseas adoptar?</label>
            <div class="selector-grid">
                <label class="option-card"><input type="radio" name="tipo_adopcion" value="Individual" checked><i class="ri-user-line"></i><span>Individual</span></label>
                <label class="option-card"><input type="radio" name="tipo_adopcion" value="Amigos"><i class="ri-group-line"></i><span>Amigos</span></label>
                <label class="option-card"><input type="radio" name="tipo_adopcion" value="Familia"><i class="ri-home-line"></i><span>Familia</span></label>
            </div>

            <label class="label-bold">Monto de aportación (MXN)</label>
            <input type="number" name="monto" value="100.00" min="1" required class="input-field">

            <label class="label-bold">Método de pago</label>
            <div class="metodo-pago-grid">
                <label class="pago-card"><input type="radio" name="metodo_pago" value="Tarjeta" checked onchange="toggleFacturacion(this, 'modalAdopcion')"><i class="ri-visa-line"></i><span>Tarjeta</span></label>
                <label class="pago-card"><input type="radio" name="metodo_pago" value="Oxxo" onchange="toggleFacturacion(this, 'modalAdopcion')"><i class="ri-store-2-line"></i><span>Oxxo</span></label>
                <label class="pago-card"><input type="radio" name="metodo_pago" value="PayPal" onchange="toggleFacturacion(this, 'modalAdopcion')"><i class="ri-paypal-line"></i><span>PayPal</span></label>
            </div>

            <div class="seccion-datos-tarjeta">
                <label class="label-bold">Datos de facturación</label>
                <div class="row-inputs">
                    <div class="group-input"><label>Número de Tarjeta</label><input type="text" name="num_tarjeta" class="card-mask input-field" placeholder="0000 0000 0000 0000" maxlength="19" required></div>
                    <div class="group-input"><label>Nombre del Facturador</label><input type="text" class="input-field name-restrict" placeholder="Ej. Bruno Diaz" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+" required></div>
                </div>
                <div class="row-inputs">
                    <div class="group-input"><label>Expira en</label><input type="text" placeholder="MM/YY" maxlength="5" class="input-field expiry-mask" required></div>
                    <div class="group-input"><label>CVV</label><input type="password" placeholder="CVC" maxlength="3" class="input-field cvv-restrict" pattern="\d{3}" required></div>
                </div>
            </div>
            <button type="submit" class="btn-confirm-pago">Apadrinar Ahora</button>
        </form>
    </div>
</div>

<div id="modalDonar" class="modal-overlay">
    <div class="modal-card">
        <button type="button" class="close-x" onclick="cerrarModal('modalDonar')"><i class="ri-close-line"></i></button>
        <h3 style="color: var(--verde-claro);">Hacer una Donación</h3>
        <form action="procesar_adopcion.php" method="POST">
            <input type="hidden" name="id_arbol" value="<?php echo $arbol['id_arbol']; ?>">
            <input type="hidden" name="tipo_adopcion" value="Donación">

            <label class="label-bold">¿Cuánto deseas donar?</label>
            <input type="number" name="monto" min="1" placeholder="Monto libre (MXN)" required class="input-field">

            <label class="label-bold">Método de pago</label>
            <div class="metodo-pago-grid">
                <label class="pago-card"><input type="radio" name="metodo_pago" value="Tarjeta" checked onchange="toggleFacturacion(this, 'modalDonar')" ><i class="ri-visa-line"></i><span>Tarjeta</span></label>
                <label class="pago-card"><input type="radio" name="metodo_pago" value="Oxxo" onchange="toggleFacturacion(this, 'modalDonar')"><i class="ri-store-2-line"></i><span>Oxxo</span></label>
                <label class="pago-card"><input type="radio" name="metodo_pago" value="PayPal" onchange="toggleFacturacion(this, 'modalDonar')"><i class="ri-paypal-line"></i><span>PayPal</span></label>
            </div>

            <div class="seccion-datos-tarjeta">
                <label class="label-bold">Datos de facturación</label>
                <div class="row-inputs">
                    <div class="group-input"><label>Número de Tarjeta</label><input type="text" name="num_tarjeta" class="card-mask input-field" placeholder="0000 0000 0000 0000" maxlength="19" required></div>
                    <div class="group-input"><label>Nombre del Facturador</label><input type="text" class="input-field name-restrict" placeholder="Ej. Bruno Diaz" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+" required></div>
                </div>
                <div class="row-inputs">
                    <div class="group-input"><label>Expira en</label><input type="text" placeholder="MM/YY" maxlength="5" class="input-field expiry-mask" required></div>
                    <div class="group-input"><label>CVV</label><input type="password" placeholder="CVC" maxlength="3" class="input-field cvv-restrict" pattern="\d{3}" required></div>
                </div>
            </div>
            <button type="submit" class="btn-confirm-pago">Donar Ahora</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function abrirModal(id) { document.getElementById(id).style.display = 'flex'; document.body.style.overflow = 'hidden'; }
    function cerrarModal(id) { document.getElementById(id).style.display = 'none'; document.body.style.overflow = 'auto'; }

    function toggleFacturacion(radio, modalId) {
        const seccion = document.querySelector(`#${modalId} .seccion-datos-tarjeta`);
        seccion.style.display = (radio.value === 'Tarjeta') ? 'block' : 'none';
    }

    // --- RESTRICCIONES Y MÁSCARAS ---
    
    // Restringir Nombres (Solo letras)
    document.querySelectorAll('.name-restrict').forEach(input => {
        input.addEventListener('keypress', (e) => { if (/[0-9]/.test(e.key)) e.preventDefault(); });
        input.addEventListener('input', (e) => { e.target.value = e.target.value.replace(/[0-9]/g, ''); });
    });

    // Restringir CVV (Solo 3 números)
    document.querySelectorAll('.cvv-restrict').forEach(input => {
        input.addEventListener('keypress', (e) => { if (!/[0-9]/.test(e.key)) e.preventDefault(); });
        input.addEventListener('input', (e) => { e.target.value = e.target.value.replace(/\D/g, ''); });
    });

    // Máscara Tarjeta
    document.querySelectorAll('.card-mask').forEach(input => {
        input.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\D/g, '').substring(0, 16);
            e.target.value = v.replace(/(\d{4})(?=\d)/g, '$1 ');
        });
    });

    // Máscara Expiración
    document.querySelectorAll('.expiry-mask').forEach(input => {
        input.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\D/g, '').substring(0, 4);
            if (v.length > 2) v = v.substring(0, 2) + '/' + v.substring(2);
            e.target.value = v;
        });
    });

    window.onclick = (e) => { if (e.target.className === 'modal-overlay') cerrarModal(e.target.id); };

    // Manejo de Alerta Success
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'adoptado') {
        Swal.fire({ title: '¡Gracias!', text: 'Registro completado con éxito', icon: 'success', confirmButtonColor: '#4a6733' });
        window.history.replaceState({}, document.title, window.location.pathname + '?id=' + urlParams.get('id'));
    }
</script>
<?php include("../includes/footer.php"); ?>