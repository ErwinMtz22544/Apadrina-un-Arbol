<?php
require_once __DIR__ . '/../includes/session.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Regístrate</title>
    <style>
        .login-container,
        .f-left {
            overflow: visible !important;
        }

        .input-wrapper {
            position: relative;
        }

        .pass-input {
            padding-right: 45px !important;
        }

        .pass-input::-ms-reveal,
        .pass-input::-ms-clear {
            display: none;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: auto !important;
            height: auto !important;
            margin: 0 !important;
            padding: 4px !important;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            color: #777 !important;
            font-size: 19px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            -webkit-user-select: none;
            touch-action: none;
            z-index: 5;
        }

        .toggle-password:hover {
            color: #4a6733 !important;
        }

        /* Ancla para la tarjeta flotante de requisitos */
        .pass-popover-anchor {
            position: relative;
            width: 100%;
        }

        .reg-req-popover {
            position: absolute;
            left: calc(100% + 16px);
            top: 50%;
            transform: translateY(-50%) translateX(-6px);
            width: 245px;
            box-sizing: border-box;
            background: #ffffff;
            border: 1px solid #dce6d8;
            border-radius: 14px;
            padding: 14px 16px;
            box-shadow: 0 12px 30px rgba(26, 60, 26, 0.16);
            font-family: 'Poppins', sans-serif;
            z-index: 30;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.18s ease, transform 0.18s ease, visibility 0.18s ease;
        }

        .reg-req-popover::before {
            content: "";
            position: absolute;
            left: -7px;
            top: 50%;
            width: 12px;
            height: 12px;
            background: #ffffff;
            border-left: 1px solid #dce6d8;
            border-bottom: 1px solid #dce6d8;
            transform: translateY(-50%) rotate(45deg);
        }

        .reg-req-popover.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(-50%) translateX(0);
        }

        .reg-req-title {
            font-size: 11px;
            font-weight: 700;
            color: #4a6733;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin: 0 0 9px;
        }

        .reg-req-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .reg-req-item {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 12px;
            color: #777;
            transition: color 0.2s;
        }

        .reg-req-item i {
            font-size: 14px;
            flex-shrink: 0;
        }

        .reg-req-item.ok {
            color: #3f6d23;
            font-weight: 500;
        }

        .reg-req-item.ok i {
            color: #5D8736;
        }

        /* Aviso discreto si sale del campo y aún faltan requisitos */
        .reg-missing-hint {
            display: none;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            font-size: 12px;
            font-weight: 500;
            color: #c25e00;
            font-family: 'Poppins', sans-serif;
            line-height: 1.35;
        }

        .reg-missing-hint.show {
            display: flex;
        }

        /* Aviso de coincidencia */
        .reg-match-msg {
            display: none;
            align-items: center;
            gap: 6px;
            width: 100%;
            font-size: 12.5px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            margin-top: -6px;
        }

        .reg-match-msg.show {
            display: flex;
        }

        .reg-match-msg.valid {
            color: #3f6d23;
        }

        .reg-match-msg.invalid {
            color: #d32f2f;
        }

        .btn-LR:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        /* En móvil/tablet se muestra compacto debajo del campo */
        @media (max-width: 960px) {
            .reg-req-popover {
                position: static;
                display: none;
                width: 100%;
                margin-top: 8px;
                transform: none;
                background: #f8faf7;
                box-shadow: none;
                opacity: 1;
                visibility: visible;
            }
            .reg-req-popover::before {
                display: none;
            }
            .reg-req-popover.visible {
                display: block;
                transform: none;
            }
            .reg-req-list {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 6px 10px;
            }
        }
    </style>
</head>
<body>
    <?php include("../includes/header.php"); ?>

    <nav class="breadcrumbs container">
        <a href="<?php echo $base_url; ?>/index.php">Inicio</a>
        <span class="separator">/</span>
        <span class="current">Registro</span>
    </nav>

    <section class="container">
        <div class="contact-container login-container">

            <form action="../includes/registro_usuario_be.php" method="POST" id="formularioRegistro" class="f-left" novalidate>
                <div class="f-left-title">
                    <h2>Regístrate</h2>
                    <hr>
                </div>

                <div class="input-wrapper">
                    <i class="fa-solid fa-circle-user f-icon"></i>
                    <input type="text" name="nombre_usuario" placeholder="Nombre" class="f-inputs" novalidate>
                </div>

                <div class="input-wrapper">
                    <i class="fas fa-envelope f-icon"></i>
                    <input type="email" name="email" id="email" placeholder="Correo electrónico" class="f-inputs" novalidate>
                    <div class="campo-correo">
                        <small id="mensaje-correo" class="mensaje-correo"></small>
                    </div>
                </div>

                <div class="pass-popover-anchor">
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock f-icon"></i>
                        <input type="password" name="user-password" id="regPassword" placeholder="Contraseña" class="f-inputs pass-input" novalidate>
                        <button type="button" class="toggle-password" data-target="regPassword" title="Mantener presionado para ver" aria-label="Mantener presionado para ver contraseña">
                            <i class="ri-eye-line"></i>
                        </button>
                    </div>

                    <div class="reg-req-popover" id="regReqPopover" role="status" aria-live="polite">
                        <p class="reg-req-title">Requisitos de seguridad</p>
                        <ul class="reg-req-list">
                            <li class="reg-req-item" id="reg-req-len"><i class="ri-checkbox-blank-circle-line"></i> Mín. 8 caracteres</li>
                            <li class="reg-req-item" id="reg-req-upper"><i class="ri-checkbox-blank-circle-line"></i> Una mayúscula</li>
                            <li class="reg-req-item" id="reg-req-lower"><i class="ri-checkbox-blank-circle-line"></i> Una minúscula</li>
                            <li class="reg-req-item" id="reg-req-num"><i class="ri-checkbox-blank-circle-line"></i> Un número</li>
                            <li class="reg-req-item" id="reg-req-spec"><i class="ri-checkbox-blank-circle-line"></i> Carácter especial</li>
                        </ul>
                    </div>

                    <div id="regMissingHint" class="reg-missing-hint"></div>
                </div>

                <div class="input-wrapper">
                    <i class="fas fa-check-circle f-icon"></i>
                    <input type="password" name="reg_confirm" id="regConfirm" placeholder="Confirmar contraseña" class="f-inputs pass-input" novalidate>
                    <button type="button" class="toggle-password" data-target="regConfirm" title="Mantener presionado para ver" aria-label="Mantener presionado para ver confirmación de contraseña">
                        <i class="ri-eye-line"></i>
                    </button>
                </div>
                <div id="regMatchMsg" class="reg-match-msg"></div>

                <button type="submit" id="btnRegistrar" class="btn-LR" disabled>Registrarme</button>

                <p class="register-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </form>

            <div class="f-right">
                <img src="../assets/img/login-registro/registro.jpg" alt="Registro">
            </div>

        </div>
    </section>

    <script>
        (() => {
            // 1. Mostrar contraseñas solo al mantener presionado (sin quitar foco al campo)
            document.querySelectorAll('.toggle-password').forEach((btn) => {
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

                btn.addEventListener('mousedown', (e) => e.preventDefault());
                btn.addEventListener('pointerdown', mostrar);
                btn.addEventListener('pointerup', ocultar);
                btn.addEventListener('pointerleave', ocultar);
                btn.addEventListener('pointercancel', ocultar);
                btn.addEventListener('blur', ocultar);
                btn.addEventListener('contextmenu', (e) => e.preventDefault());
            });

            // 2. Validación en tiempo real + popover flotante
            const passInput = document.getElementById('regPassword');
            const confirmInput = document.getElementById('regConfirm');
            const popover = document.getElementById('regReqPopover');
            const missingHint = document.getElementById('regMissingHint');
            const matchMsg = document.getElementById('regMatchMsg');
            const btnRegistrar = document.getElementById('btnRegistrar');

            let passFocused = false;

            const reglas = [
                { el: document.getElementById('reg-req-len'),   label: '8+ caracteres', test: (v) => v.length >= 8 },
                { el: document.getElementById('reg-req-upper'), label: 'mayúscula',     test: (v) => /[A-Z]/.test(v) },
                { el: document.getElementById('reg-req-lower'), label: 'minúscula',     test: (v) => /[a-z]/.test(v) },
                { el: document.getElementById('reg-req-num'),   label: 'número',        test: (v) => /[0-9]/.test(v) },
                { el: document.getElementById('reg-req-spec'),  label: 'símbolo',       test: (v) => /[^A-Za-z0-9\s]/.test(v) }
            ];

            function validarRegistroPass() {
                const pass = passInput.value;
                const conf = confirmInput.value;
                const faltantes = [];

                reglas.forEach((regla) => {
                    const ok = regla.test(pass);
                    const icono = regla.el.querySelector('i');
                    regla.el.classList.toggle('ok', ok);
                    icono.className = ok ? 'ri-checkbox-circle-fill' : 'ri-checkbox-blank-circle-line';
                    if (!ok) faltantes.push(regla.label);
                });

                const cumpleTodo = faltantes.length === 0;

                // Mostrar popover solo mientras el usuario escribe en Contraseña
                popover.classList.toggle('visible', passFocused);

                // Si salió del campo y le faltó algo, avisar en una sola línea discreta
                if (!passFocused && pass.length > 0 && !cumpleTodo) {
                    missingHint.className = 'reg-missing-hint show';
                    missingHint.innerHTML = '<i class="ri-error-warning-line"></i> Falta: ' + faltantes.join(', ');
                } else {
                    missingHint.className = 'reg-missing-hint';
                    missingHint.innerHTML = '';
                }

                // Coincidencia de contraseñas
                let coinciden = false;
                if (conf.length === 0) {
                    matchMsg.className = 'reg-match-msg';
                    matchMsg.innerHTML = '';
                } else if (pass === conf) {
                    coinciden = true;
                    matchMsg.className = 'reg-match-msg show valid';
                    matchMsg.innerHTML = '<i class="ri-checkbox-circle-fill"></i> Las contraseñas coinciden';
                } else {
                    matchMsg.className = 'reg-match-msg show invalid';
                    matchMsg.innerHTML = '<i class="ri-close-circle-fill"></i> Las contraseñas no coinciden';
                }

                btnRegistrar.disabled = !(cumpleTodo && coinciden);
            }

            passInput.addEventListener('focus', () => {
                passFocused = true;
                validarRegistroPass();
            });
            passInput.addEventListener('blur', () => {
                passFocused = false;
                validarRegistroPass();
            });
            passInput.addEventListener('input', validarRegistroPass);
            confirmInput.addEventListener('input', validarRegistroPass);
        })();
    </script>
    <script src="../assets/js/validar-correo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/registro.js"></script>
    <?php include("../includes/footer.php"); ?>
</body>
</html>