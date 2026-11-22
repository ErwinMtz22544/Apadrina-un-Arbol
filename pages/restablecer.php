<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/db.php';

$token = trim($_GET['token'] ?? '');
$email = trim($_GET['email'] ?? '');
$valido = false;

if (!empty($token) && !empty($email)) {
    $query = new MongoDB\Driver\Query(['email' => $email, 'token' => $token]);
    $cursor = $conexion->executeQuery("ArbolBD.usuarios", $query)->toArray();
    if (count($cursor) > 0) {
        $valido = true;
    }
}

include("../includes/header.php");
?>

<style>
    .reset-page {
        min-height: calc(100vh - 120px);
        padding: 130px 20px 80px;
        background-color: #f4f7f2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Poppins', sans-serif;
    }

    .reset-card, .reset-card * {
        box-sizing: border-box;
    }

    .reset-card {
        width: 100%;
        max-width: 480px;
        background: #ffffff;
        border-radius: 22px;
        padding: 40px 36px;
        box-shadow: 0 12px 35px rgba(26, 60, 26, 0.08);
        border: 1px solid #e6eee2;
    }

    .reset-header {
        text-align: center;
        margin-bottom: 28px;
    }

    .reset-badge-icon {
        width: 62px;
        height: 62px;
        margin: 0 auto 14px;
        border-radius: 16px;
        background: #f0fdf4;
        color: #5D8736;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }

    .reset-badge-icon.error {
        background: #ffebee;
        color: #d32f2f;
    }

    .reset-header h2 {
        margin: 0 0 8px;
        font-size: 24px;
        font-weight: 700;
        color: #1a3c1a;
    }

    .reset-header h2.error-title {
        color: #c62828;
    }

    .reset-header p {
        margin: 0;
        font-size: 14px;
        color: #666;
        line-height: 1.5;
    }

    .reset-field {
        margin-bottom: 18px;
    }

    .reset-field label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 7px;
    }

    .reset-input-box {
        position: relative;
        width: 100%;
    }

    .reset-icon-left {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
        font-size: 18px;
        pointer-events: none;
    }

    .reset-input {
        width: 100%;
        height: 48px;
        padding: 0 46px 0 42px;
        border: 1.5px solid #dce3da;
        border-radius: 12px;
        font-size: 14px;
        font-family: inherit;
        color: #222;
        background: #fafcf9;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
    }

    .reset-input:focus {
        border-color: #5D8736;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(93, 135, 54, 0.14);
    }

    .reset-input::-ms-reveal,
    .reset-input::-ms-clear {
        display: none;
    }

    .reset-eye-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 34px;
        height: 34px;
        padding: 0;
        margin: 0;
        background: transparent;
        border: none;
        border-radius: 8px;
        color: #777;
        font-size: 19px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        user-select: none;
        -webkit-user-select: none;
        touch-action: none;
        transition: color 0.2s, background-color 0.2s;
    }

    .reset-eye-btn:hover {
        color: #4a6733;
        background-color: #f0f5ed;
    }

    .req-box {
        background: #f8faf7;
        border: 1px solid #e7efe4;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 20px;
    }

    .req-title {
        font-size: 12px;
        font-weight: 600;
        color: #4a6733;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin: 0 0 10px;
    }

    .req-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 12px;
    }

    .req-item {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 12.5px;
        color: #777;
        transition: color 0.2s;
    }

    .req-item i {
        font-size: 15px;
        flex-shrink: 0;
    }

    .req-item.ok {
        color: #3f6d23;
        font-weight: 500;
    }

    .req-item.ok i {
        color: #5D8736;
    }

    .match-msg {
        display: none;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        font-size: 13px;
        font-weight: 500;
    }

    .match-msg.show { display: flex; }
    .match-msg.valid { color: #3f6d23; }
    .match-msg.invalid { color: #d32f2f; }

    .reset-submit {
        width: 100%;
        height: 50px;
        margin-top: 10px;
        background: #5D8736;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        font-family: inherit;
        text-decoration: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background-color 0.2s, opacity 0.2s, transform 0.15s;
    }

    .reset-submit:hover:not(:disabled) {
        background: #4a6733;
        transform: translateY(-1px);
    }

    .reset-submit:disabled {
        background: #b7c7ae;
        cursor: not-allowed;
        opacity: 0.85;
    }

    @media (max-width: 520px) {
        .reset-card { padding: 30px 22px; }
        .req-list { grid-template-columns: 1fr; }
    }
</style>

<main class="reset-page">
    <div class="reset-card">
        <?php if ($valido): ?>
            <div class="reset-header">
                <div class="reset-badge-icon">
                    <i class="ri-key-2-line"></i>
                </div>
                <h2>Nueva Contraseña</h2>
                <p>Enlace verificado correctamente. Elige una nueva contraseña segura para recuperar el acceso a tu cuenta.</p>
            </div>

            <form action="../includes/actualizar_password_manual_be.php" method="POST" id="formRestablecer">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

                <div class="reset-field">
                    <label for="nuevaPass">Nueva contraseña</label>
                    <div class="reset-input-box">
                        <i class="ri-lock-2-line reset-icon-left"></i>
                        <input type="password" name="nueva_pass" id="nuevaPass" class="reset-input" placeholder="Escribe tu nueva contraseña" autocomplete="new-password" required>
                        <button type="button" class="reset-eye-btn" data-target="nuevaPass" title="Mantener presionado para ver" aria-label="Mantener presionado para ver contraseña">
                            <i class="ri-eye-line"></i>
                        </button>
                    </div>
                </div>

                <div class="req-box">
                    <p class="req-title">Requisitos de seguridad</p>
                    <ul class="req-list">
                        <li class="req-item" id="req-len"><i class="ri-checkbox-blank-circle-line"></i> Mín. 8 caracteres</li>
                        <li class="req-item" id="req-upper"><i class="ri-checkbox-blank-circle-line"></i> Una mayúscula</li>
                        <li class="req-item" id="req-lower"><i class="ri-checkbox-blank-circle-line"></i> Una minúscula</li>
                        <li class="req-item" id="req-num"><i class="ri-checkbox-blank-circle-line"></i> Un número</li>
                        <li class="req-item" id="req-spec"><i class="ri-checkbox-blank-circle-line"></i> Un carácter especial</li>
                    </ul>
                </div>

                <div class="reset-field">
                    <label for="confirmarPass">Confirmar contraseña</label>
                    <div class="reset-input-box">
                        <i class="ri-shield-check-line reset-icon-left"></i>
                        <input type="password" name="confirmar_pass" id="confirmarPass" class="reset-input" placeholder="Repite tu nueva contraseña" autocomplete="new-password" required>
                        <button type="button" class="reset-eye-btn" data-target="confirmarPass" title="Mantener presionado para ver" aria-label="Mantener presionado para ver confirmación">
                            <i class="ri-eye-line"></i>
                        </button>
                    </div>
                    <div id="matchMsg" class="match-msg"></div>
                </div>

                <button type="submit" id="btnGuardarPass" class="reset-submit" disabled>
                    <i class="ri-check-double-line"></i> Actualizar Contraseña
                </button>
            </form>
        <?php else: ?>
            <div class="reset-header">
                <div class="reset-badge-icon error">
                    <i class="ri-error-warning-line"></i>
                </div>
                <h2 class="error-title">Enlace Inválido</h2>
                <p>Lo sentimos, este enlace de recuperación ya expiró, fue utilizado anteriormente o no es válido.</p>
            </div>

            <a href="login.php" class="reset-submit">
                <i class="ri-arrow-left-line"></i> Volver a Iniciar Sesión
            </a>
        <?php endif; ?>
    </div>
</main>

<?php if ($valido): ?>
<script>
    // 1. Ojitos: mostrar solo al mantener presionado
    document.querySelectorAll('.reset-eye-btn').forEach((btn) => {
        const input = document.getElementById(btn.dataset.target);
        const icon = btn.querySelector('i');

        const mostrar = (e) => {
            e.preventDefault();
            input.type = 'text';
            icon.classList.replace('ri-eye-line', 'ri-eye-off-line');
        };
        const ocultar = () => {
            input.type = 'password';
            icon.classList.replace('ri-eye-off-line', 'ri-eye-line');
        };

        btn.addEventListener('pointerdown', mostrar);
        btn.addEventListener('pointerup', ocultar);
        btn.addEventListener('pointerleave', ocultar);
        btn.addEventListener('pointercancel', ocultar);
        btn.addEventListener('blur', ocultar);
        btn.addEventListener('contextmenu', (e) => e.preventDefault());
    });

    // 2. Validación en tiempo real
    const nuevaInput = document.getElementById('nuevaPass');
    const confirmarInput = document.getElementById('confirmarPass');
    const matchMsg = document.getElementById('matchMsg');
    const btnGuardar = document.getElementById('btnGuardarPass');

    const reglas = {
        len:   { el: document.getElementById('req-len'),   test: (v) => v.length >= 8 },
        upper: { el: document.getElementById('req-upper'), test: (v) => /[A-Z]/.test(v) },
        lower: { el: document.getElementById('req-lower'), test: (v) => /[a-z]/.test(v) },
        num:   { el: document.getElementById('req-num'),   test: (v) => /[0-9]/.test(v) },
        spec:  { el: document.getElementById('req-spec'),  test: (v) => /[^A-Za-z0-9\s]/.test(v) }
    };

    function validarFormulario() {
        const pass = nuevaInput.value;
        const conf = confirmarInput.value;
        let cumpleTodo = true;

        Object.values(reglas).forEach((regla) => {
            const ok = regla.test(pass);
            const icono = regla.el.querySelector('i');
            regla.el.classList.toggle('ok', ok);
            icono.className = ok ? 'ri-checkbox-circle-fill' : 'ri-checkbox-blank-circle-line';
            if (!ok) cumpleTodo = false;
        });

        let coinciden = false;
        if (conf.length === 0) {
            matchMsg.className = 'match-msg';
            matchMsg.innerHTML = '';
        } else if (pass === conf) {
            coinciden = true;
            matchMsg.className = 'match-msg show valid';
            matchMsg.innerHTML = '<i class="ri-checkbox-circle-fill"></i> Las contraseñas coinciden';
        } else {
            matchMsg.className = 'match-msg show invalid';
            matchMsg.innerHTML = '<i class="ri-close-circle-fill"></i> Las contraseñas no coinciden';
        }

        btnGuardar.disabled = !(cumpleTodo && coinciden);
    }

    nuevaInput.addEventListener('input', validarFormulario);
    confirmarInput.addEventListener('input', validarFormulario);

    // 3. Alertas del servidor
    const params = new URLSearchParams(window.location.search);
    const err = params.get('error');
    if (err === 'no_coinciden') {
        Swal.fire('Error', 'Las contraseñas no coinciden.', 'error');
    } else if (err === 'requisitos') {
        Swal.fire('Contraseña insegura', 'Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.', 'warning');
    }
</script>
<?php endif; ?>

<?php include("../includes/footer.php"); ?>