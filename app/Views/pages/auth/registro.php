<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta | La Tiendita Saludable</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: '#d4af37',
                    },
                    fontFamily: {
                        heading: ['Playfair Display', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <div id="page-wrapper" class="auth-container animate__animated animate__fadeIn">
        
        <a href="/" class="page-transition absolute top-8 left-4 md:left-8 text-gray-400 hover:text-[#d4af37] transition-colors flex items-center gap-2 text-sm uppercase tracking-widest z-50">
            <i class="ph ph-arrow-left text-xl"></i> <span class="hidden sm:inline">Volver al Catálogo</span>
        </a>

        <div class="auth-box animate__animated animate__fadeInUp animate__delay-1s mx-4 mt-16 md:mt-0">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-heading font-bold text-white tracking-widest uppercase mb-2">Registro</h1>
                <p class="text-gray-400 text-sm font-light">Únete a la familia saludable</p>
            </div>

            <?php if (isset($_SESSION['error_auth'])): ?>
                <div class="bg-red-900/50 border border-red-500/50 text-red-200 px-4 py-3 rounded mb-6 text-sm text-center">
                    <?= htmlspecialchars($_SESSION['error_auth']) ?>
                    <?php unset($_SESSION['error_auth']); ?>
                </div>
            <?php endif; ?>

            <form action="/registro" method="POST" id="register-form">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                    <div class="floating-input">
                        <input type="text" id="nombres" name="nombres" placeholder=" " maxlength="100" required>
                        <label for="nombres">Nombre</label>
                    </div>
                    <div class="floating-input">
                        <input type="text" id="apellidos" name="apellidos" placeholder=" " maxlength="100" required>
                        <label for="apellidos">Apellido</label>
                    </div>
                </div>

                <div class="floating-input">
                    <input type="email" id="email" name="email" placeholder=" " maxlength="150" required>
                    <label for="email">Correo Electrónico</label>
                </div>

                <div class="floating-input">
                    <input type="tel" id="telefono" name="telefono" placeholder=" " pattern="[0-9]*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="15">
                    <label for="telefono">Número de Teléfono (WhatsApp)</label>
                </div>

                <div class="floating-input">
                    <input type="password" id="password" name="password" placeholder=" " minlength="6" maxlength="100" required>
                    <label for="password">Contraseña</label>
                    <i class="ph ph-eye password-toggle" onclick="togglePassword('password', this)" title="Mostrar contraseña"></i>
                </div>

                <div class="floating-input">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder=" " minlength="6" maxlength="100" required>
                    <label for="confirm_password">Confirmar Contraseña</label>
                    <i class="ph ph-eye password-toggle" onclick="togglePassword('confirm_password', this)" title="Mostrar contraseña"></i>
                </div>

                <button type="submit" class="auth-btn mb-6 mt-2 uppercase tracking-widest text-sm font-bold">Crear Cuenta</button>

                <p class="text-center text-sm text-gray-400">
                    ¿Ya tienes una cuenta? <a href="/login" class="page-transition text-[#d4af37] hover:text-white transition-colors ml-1 font-semibold uppercase tracking-wider text-xs">Iniciar Sesión</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                iconElement.classList.replace('ph-eye', 'ph-eye-slash');
                iconElement.setAttribute('title', 'Ocultar contraseña');
            } else {
                input.type = 'text';
                iconElement.classList.replace('ph-eye-slash', 'ph-eye');
                iconElement.setAttribute('title', 'Mostrar contraseña');
            }
        }

        document.querySelectorAll('.page-transition').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('target') === '_blank') return;
                e.preventDefault();
                const href = this.getAttribute('href');
                const wrapper = document.getElementById('page-wrapper');
                
                if (wrapper) {
                    wrapper.classList.remove('animate__fadeIn');
                    wrapper.classList.add('animate__fadeOut', 'animate__faster');
                    setTimeout(() => {
                        window.location.href = href;
                    }, 300);
                } else {
                    window.location.href = href;
                }
            });
        });
    </script>
</body>
</html>
