<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | La Tiendita Saludable</title>
    <!-- Tailwind CSS CDN -->
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
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Estilos Originales -->
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <div id="page-wrapper" class="auth-container animate__animated animate__fadeIn">
        
        <!-- Botón Volver -->
        <a href="/" class="page-transition absolute top-8 left-4 md:left-8 text-gray-400 hover:text-[#d4af37] transition-colors flex items-center gap-2 text-sm uppercase tracking-widest z-50">
            <i class="ph ph-arrow-left text-xl"></i> <span class="hidden sm:inline">Volver al Catálogo</span>
        </a>

        <!-- Contenedor Principal -->
        <div class="auth-box animate__animated animate__fadeInUp animate__delay-1s mx-4 mt-16 md:mt-0">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-heading font-bold text-white tracking-widest uppercase mb-2">BIENVENIDO</h1>
                <p class="text-gray-400 text-sm font-light">Inicia sesión en tu cuenta premium</p>
            </div>

            <?php if (isset($_SESSION['error_auth'])): ?>
                <div class="bg-red-900/50 border border-red-500/50 text-red-200 px-4 py-3 rounded mb-6 text-sm text-center">
                    <?= htmlspecialchars($_SESSION['error_auth']) ?>
                    <?php unset($_SESSION['error_auth']); ?>
                </div>
            <?php endif; ?>

            <form action="/login" method="POST" id="login-form">
                <!-- Token CSRF -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <div class="floating-input mb-8">
                    <input type="email" id="email" name="email" placeholder=" " maxlength="150" required autocomplete="email">
                    <label for="email">Correo Electrónico</label>
                </div>

                <div class="floating-input mb-4">
                    <input type="password" id="password" name="password" placeholder=" " maxlength="100" required>
                    <label for="password">Contraseña</label>
                    <i class="ph ph-eye password-toggle" onclick="togglePassword('password', this)" title="Mostrar contraseña"></i>
                </div>

                <!-- Opciones (Recuérdame / Olvidaste contraseña) -->
                <div class="flex justify-between items-center mb-6">
                    <label class="flex items-center gap-2 text-xs text-gray-400 cursor-pointer hover:text-white transition-colors">
                        <input type="checkbox" name="remember" class="w-3 h-3 border border-gray-600 bg-transparent rounded-sm text-[#d4af37] focus:ring-0 focus:outline-none cursor-pointer">
                        Recuérdame
                    </label>
                    <a href="#" class="text-xs text-gray-400 hover:text-[#d4af37] transition-colors">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="auth-btn mb-6 uppercase tracking-widest text-sm font-bold">Iniciar Sesión</button>

                <div class="auth-divider">o</div>
                
                <button type="button" class="auth-btn-google mb-6">
                    <i class="ph-bold ph-google-logo text-lg mr-2"></i> CONTINÚA CON GOOGLE
                </button>

                <p class="text-center text-sm text-gray-400 mt-2">
                    ¿No tienes una cuenta? <a href="/registro" class="page-transition text-[#d4af37] hover:text-white transition-colors ml-1 font-semibold uppercase tracking-wider text-xs">Crear Cuenta</a>
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
                input.type = 'password';
                iconElement.classList.replace('ph-eye-slash', 'ph-eye');
                iconElement.setAttribute('title', 'Mostrar contraseña');
            }
        }

        // Transición fluida de página
        document.querySelectorAll('.page-transition').forEach(link => {
            link.addEventListener('click', function(e) {
                // Si es un botón de submit o tiene target _blank, no hacer preventDefault
                if (this.getAttribute('target') === '_blank') return;
                
                e.preventDefault();
                const href = this.getAttribute('href');
                const wrapper = document.getElementById('page-wrapper');
                
                if(wrapper) {
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
