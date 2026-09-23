<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - La Tiendita Saludable</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        gold: '#D4AF37',
                        dark: '#0a0a0a',
                        card: '#111111',
                    },
                    fontFamily: {
                        heading: ['Playfair Display', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js para interactividad sin recargas -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap');
        body { background-color: #050505; color: #fff; }
        .glass-header {
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .admin-sidebar {
            background-color: #0a0a0a;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
        /* Custom scrollbar para panel admin */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #050505; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #D4AF37; }
    </style>
</head>
<body class="flex h-screen overflow-hidden font-sans antialiased selection:bg-gold selection:text-black">
    
    <!-- Alpine Root para controlar Sidebar Mobile -->
    <div x-data="{ sidebarOpen: false, modalPasswordOpen: false }" class="flex h-full w-full">
        
        <!-- Backdrop Mobile -->
        <div x-show="sidebarOpen" x-transition.opacity 
             class="fixed inset-0 z-20 bg-black/80 lg:hidden" 
             @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-30 w-64 admin-sidebar transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-auto flex flex-col">
            
            <div class="flex items-center justify-center h-20 border-b border-[#222]">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-8 h-8 rounded border border-gold/30 overflow-hidden shadow-[0_0_10px_rgba(212,175,55,0.1)]">
                        <img src="/assets/img/logo/logo_relialista_negro.jpg" alt="Logo" class="w-full h-full object-cover">
                    </div>
                    <span class="text-white font-heading font-bold tracking-wider uppercase text-sm">Tiendita <span class="text-gold">Admin</span></span>
                </a>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="/admin" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $_SERVER['REQUEST_URI'] == '/admin' ? 'bg-gold text-black font-semibold shadow-[0_0_15px_rgba(212,175,55,0.2)]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white transition-colors' ?>">
                    <i class="ph ph-squares-four text-lg"></i>
                    <span class="text-sm tracking-wide">Dashboard</span>
                </a>
                
                <a href="/admin/productos" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= strpos($_SERVER['REQUEST_URI'], '/admin/productos') !== false ? 'bg-gold text-black font-semibold shadow-[0_0_15px_rgba(212,175,55,0.2)]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white transition-colors' ?>">
                    <i class="ph ph-package text-lg"></i>
                    <span class="text-sm tracking-wide">Catálogo y Productos</span>
                </a>

                <a href="/admin/usuarios" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= strpos($_SERVER['REQUEST_URI'], '/admin/usuarios') !== false ? 'bg-gold text-black font-semibold shadow-[0_0_15px_rgba(212,175,55,0.2)]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white transition-colors' ?>">
                    <i class="ph ph-users text-lg"></i>
                    <span class="text-sm tracking-wide">Usuarios y Accesos</span>
                </a>

                <a href="/admin/clientes" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= strpos($_SERVER['REQUEST_URI'], '/admin/clientes') !== false ? 'bg-gold text-black font-semibold shadow-[0_0_15px_rgba(212,175,55,0.2)]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white transition-colors' ?>">
                    <i class="ph ph-user-address text-lg"></i>
                    <span class="text-sm tracking-wide">Clientes y Perfiles</span>
                </a>

                <a href="/admin/pedidos" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= strpos($_SERVER['REQUEST_URI'], '/admin/pedidos') !== false ? 'bg-gold text-black font-semibold shadow-[0_0_15px_rgba(212,175,55,0.2)]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white transition-colors' ?>">
                    <i class="ph ph-shopping-bag text-lg"></i>
                    <span class="text-sm tracking-wide">Pedidos y Ventas</span>
                </a>

                <a href="/admin/metodos-pago" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= strpos($_SERVER['REQUEST_URI'], '/admin/metodos-pago') !== false ? 'bg-gold text-black font-semibold shadow-[0_0_15px_rgba(212,175,55,0.2)]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white transition-colors' ?>">
                    <i class="ph ph-wallet text-lg"></i>
                    <span class="text-sm tracking-wide">Métodos de Pago</span>
                </a>

                <a href="/admin/configuracion" class="flex items-center gap-3 px-4 py-3 rounded-lg <?= strpos($_SERVER['REQUEST_URI'], '/admin/configuracion') !== false ? 'bg-gold text-black font-semibold shadow-[0_0_15px_rgba(212,175,55,0.2)]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white transition-colors' ?>">
                    <i class="ph ph-gear text-lg"></i>
                    <span class="text-sm tracking-wide">Configuración Web</span>
                </a>
            </nav>

            <div class="p-4 border-t border-[#222]">
                <div class="flex items-center gap-3 px-4 py-3 bg-[#111] rounded-lg cursor-pointer hover:bg-[#1a1a1a] transition-colors" @click="modalPasswordOpen = true" title="Cambiar Contraseña">
                    <div class="w-8 h-8 rounded-full bg-[#222] flex items-center justify-center">
                        <i class="ph ph-user text-gold"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-white truncate"><?= htmlspecialchars($_SESSION['email'] ?? 'Admin') ?></p>
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest">Administrador</p>
                    </div>
                </div>
                <a href="/logout" class="mt-4 flex items-center justify-center gap-2 w-full py-2 text-xs text-red-400 hover:text-red-300 hover:bg-red-400/10 rounded-lg transition-colors">
                    <i class="ph ph-sign-out"></i> Cerrar Sesión
                </a>
            </div>
        </aside>

        <!-- Modal Cambiar Contraseña -->
        <div x-show="modalPasswordOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;">
            <div @click.away="modalPasswordOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] p-8 w-full max-w-[400px] relative shadow-2xl">
                
                <!-- Borde superior sutil -->
                <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-gold/40 to-transparent"></div>
                
                <button type="button" @click="modalPasswordOpen = false" class="absolute top-4 right-4 text-[#555] hover:text-gold transition-colors focus:outline-none">
                    <i class="ph ph-x text-xl"></i>
                </button>

                <div class="text-center mb-8">
                    <h2 class="text-[1.35rem] font-heading font-bold text-white tracking-widest uppercase mb-1">Seguridad</h2>
                    <p class="text-[#666] text-xs font-light">Actualiza tu contraseña de administrador</p>
                </div>

                <form action="/admin/cambiar-password" method="POST" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" maxlength="100" class="w-full bg-transparent border-b border-[#222] py-2 text-[0.85rem] text-white focus:border-gold outline-none transition-colors placeholder-[#555]" placeholder="Contraseña Actual" required>
                        <i class="ph ph-eye absolute right-0 top-2.5 text-[#555] cursor-pointer hover:text-gold transition-colors" onclick="toggleModalPassword('current_password', this)"></i>
                    </div>

                    <div class="relative">
                        <input type="password" id="new_password" name="new_password" maxlength="100" class="w-full bg-transparent border-b border-[#222] py-2 text-[0.85rem] text-white focus:border-gold outline-none transition-colors placeholder-[#555]" placeholder="Nueva Contraseña" required>
                        <i class="ph ph-eye absolute right-0 top-2.5 text-[#555] cursor-pointer hover:text-gold transition-colors" onclick="toggleModalPassword('new_password', this)"></i>
                    </div>

                    <div class="relative">
                        <input type="password" id="confirm_password" name="confirm_password" maxlength="100" class="w-full bg-transparent border-b border-[#222] py-2 text-[0.85rem] text-white focus:border-gold outline-none transition-colors placeholder-[#555]" placeholder="Confirmar Contraseña" required>
                        <i class="ph ph-eye absolute right-0 top-2.5 text-[#555] cursor-pointer hover:text-gold transition-colors" onclick="toggleModalPassword('confirm_password', this)"></i>
                    </div>

                    <button type="submit" class="w-full bg-[#D4AF37] text-black py-[0.85rem] mt-2 text-[0.8rem] font-semibold uppercase tracking-widest hover:bg-[#f1c40f] transition-colors focus:outline-none">
                        Actualizar Contraseña
                    </button>
                </form>
            </div>
        </div>

        <script>
            function toggleModalPassword(inputId, iconElement) {
                const input = document.getElementById(inputId);
                if (input.type === 'password') {
                    input.type = 'text';
                    iconElement.classList.replace('ph-eye', 'ph-eye-slash');
                } else {
                    input.type = 'password';
                    iconElement.classList.replace('ph-eye-slash', 'ph-eye');
                }
            }
        </script>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar Mobile & Common -->
            <header class="glass-header h-20 flex items-center justify-between px-6 z-10 lg:justify-end">
                <button @click="sidebarOpen = true" class="lg:hidden text-white hover:text-gold transition-colors">
                    <i class="ph ph-list text-2xl"></i>
                </button>
                <div class="flex items-center gap-4">
                    <a href="/" target="_blank" class="text-xs text-gray-400 hover:text-white flex items-center gap-2 transition-colors">
                        <i class="ph ph-arrow-square-out"></i> Ver Tienda
                    </a>
                </div>
            </header>

            <!-- Main Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#050505] p-6 lg:p-10 relative">
                
                <?php if (isset($_SESSION['success_msg'])): ?>
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                         class="mb-6 bg-green-900/40 border border-green-500/50 text-green-300 px-4 py-3 rounded flex justify-between items-center shadow-lg">
                        <div class="flex items-center gap-2">
                            <i class="ph-fill ph-check-circle"></i>
                            <span class="text-sm"><?= htmlspecialchars($_SESSION['success_msg']) ?></span>
                        </div>
                        <button @click="show = false" class="text-green-500 hover:text-white"><i class="ph ph-x"></i></button>
                    </div>
                    <?php unset($_SESSION['success_msg']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_msg'])): ?>
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                         class="mb-6 bg-red-900/40 border border-red-500/50 text-red-300 px-4 py-3 rounded flex justify-between items-center shadow-lg">
                        <div class="flex items-center gap-2">
                            <i class="ph-fill ph-warning-circle"></i>
                            <span class="text-sm"><?= htmlspecialchars($_SESSION['error_msg']) ?></span>
                        </div>
                        <button @click="show = false" class="text-red-500 hover:text-white"><i class="ph ph-x"></i></button>
                    </div>
                    <?php unset($_SESSION['error_msg']); ?>
                <?php endif; ?>
