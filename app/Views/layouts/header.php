<!DOCTYPE html>
<html lang="es" style="scroll-behavior: smooth;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'La Tiendita Saludable | Premium' ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: '#D4AF37',
                    },
                    fontFamily: {
                        heading: ['Playfair Display', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Iconos Phosphor -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Estilos Personalizados -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <?php if (isset($extraCss)): ?>
        <link rel="stylesheet" href="<?= $extraCss ?>">
    <?php endif; ?>

    <style>
        .category-title {
            position: relative;
            display: inline-block;
        }
        .category-title::after {
            content: '';
            position: absolute;
            width: 50%;
            height: 2px;
            bottom: -8px;
            left: 0;
            background-color: var(--color-gold);
        }
    </style>
</head>
<body class="bg-[#050505] text-gray-200 font-sans antialiased overflow-x-hidden selection:bg-gold selection:text-black" x-data="tienditaStore()">

    <!-- Header -->
    <header class="border-b border-[#333] sticky top-0 z-50 bg-[#0a0a0a]/90 backdrop-blur-md animate__animated animate__fadeInDown">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20 md:h-24">
                <a href="/" class="flex items-center gap-3 md:gap-4 group">
                    <div class="relative w-12 h-12 md:w-16 md:h-16 overflow-hidden rounded-sm border-2 border-transparent group-hover:border-gold/50 transition-all duration-300 shadow-[0_0_15px_rgba(212,175,55,0.1)]">
                        <img src="/assets/img/logo/logo_relialista_negro.jpg" alt="Logo La Tiendita Saludable" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="text-xl md:text-3xl font-heading font-bold text-white tracking-[0.15em] uppercase leading-none">La Tiendita</span>
                        <span class="text-gold text-[10px] md:text-sm tracking-[0.4em] uppercase font-semibold leading-none mt-1.5 md:mt-1 ml-0.5">Saludable</span>
                    </div>
                </a>
                
                <!-- Desktop Nav -->
                <nav class="hidden md:flex space-x-10 items-center">
                    <a href="/#inicio" class="text-sm font-medium tracking-widest uppercase text-white hover:text-gold transition-colors">Inicio</a>
                    <a href="/#productos" class="text-sm font-medium tracking-widest uppercase text-gray-400 hover:text-gold transition-colors">Colección</a>
                    <a href="/sobre-nosotros" class="text-sm font-medium tracking-widest uppercase text-gray-400 hover:text-gold transition-colors">Sobre Nosotros</a>
                    <a href="/preventa" class="page-transition text-sm font-bold tracking-widest uppercase text-gold hover:text-white transition-colors flex items-center border border-gold/30 px-3 py-1 bg-gold/10 rounded-sm">
                        <span class="w-2 h-2 rounded-full bg-gold animate-pulse mr-2"></span> Preventa
                    </a>
                </nav>

                <div class="flex items-center space-x-5 md:space-x-6">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php if(isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
                            <a href="/admin" class="hidden md:flex items-center gap-2 text-xs font-bold uppercase tracking-widest border border-gold bg-gold/10 text-gold px-4 py-2 hover:bg-gold hover:text-black transition-colors rounded-sm shadow-[0_0_10px_rgba(212,175,55,0.2)]">
                                <i class="ph-bold ph-shield-star"></i> Panel Admin
                            </a>
                            <a href="/admin" class="md:hidden text-gold hover:text-white transition-colors relative"><i class="ph-bold ph-shield-star text-2xl"></i></a>
                        <?php else: ?>
                            <button @click="$dispatch('open-profile')" class="hidden md:flex items-center gap-2 text-xs font-bold uppercase tracking-widest border border-gold bg-gold/10 text-gold px-4 py-2 hover:bg-gold hover:text-black transition-colors rounded-sm shadow-[0_0_10px_rgba(212,175,55,0.2)]">
                                <i class="ph-bold ph-user"></i> Mi Perfil
                            </button>
                            <button @click="$dispatch('open-profile')" class="md:hidden text-gold hover:text-white transition-colors relative"><i class="ph-bold ph-user text-2xl"></i></button>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="/login" class="page-transition hidden md:block text-xs uppercase tracking-widest border border-gold text-gold px-4 py-2 hover:bg-gold hover:text-black transition-colors">Entrar</a>
                        <a href="/login" class="page-transition md:hidden text-gray-400 hover:text-gold transition-colors"><i class="ph ph-user text-2xl"></i></a>
                    <?php endif; ?>

                    <button @click="isCartOpen = true" class="text-gray-400 hover:text-gold transition-colors relative">
                        <i class="ph ph-shopping-bag text-2xl"></i>
                        <span x-show="cartTotalItems > 0" x-text="cartTotalItems" class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-[0_0_5px_rgba(220,38,38,0.5)]" style="display: none;"></span>
                    </button>
                    <!-- Hamburger Menu Button -->
                    <button id="mobile-menu-btn" class="md:hidden text-gray-400 hover:text-gold transition-colors"><i class="ph ph-list text-3xl"></i></button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" class="fixed inset-0 bg-[#050505]/98 backdrop-blur-xl z-[100] flex flex-col justify-center items-center opacity-0 pointer-events-none transition-all duration-500 scale-105">
        <button id="close-menu-btn" class="absolute top-6 right-6 text-gray-400 text-4xl hover:text-gold transition-colors"><i class="ph ph-x"></i></button>
        <nav class="flex flex-col space-y-8 items-center text-center">
            <a href="/#inicio" class="mobile-link text-2xl font-heading tracking-widest uppercase text-white hover:text-gold transition-colors">Inicio</a>
            <a href="/#productos" class="mobile-link text-2xl font-heading tracking-widest uppercase text-gray-400 hover:text-gold transition-colors">Colección</a>
            <a href="/sobre-nosotros" class="mobile-link text-2xl font-heading tracking-widest uppercase text-gray-400 hover:text-gold transition-colors">Nuestra Esencia</a>
            <a href="/preventa" class="page-transition text-2xl font-heading font-bold tracking-widest uppercase text-gold hover:text-white transition-colors">Preventa</a>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if(isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
                    <a href="/admin" class="mt-8 text-sm font-bold uppercase tracking-widest border border-gold bg-gold/10 text-gold px-8 py-3 hover:bg-gold hover:text-black transition-colors rounded-sm shadow-[0_0_15px_rgba(212,175,55,0.3)]">Administrar</a>
                <?php else: ?>
                    <button @click="$dispatch('open-profile'); document.getElementById('close-menu-btn').click()" class="mt-8 text-sm font-bold uppercase tracking-widest border border-gold bg-gold/10 text-gold px-8 py-3 hover:bg-gold hover:text-black transition-colors rounded-sm shadow-[0_0_15px_rgba(212,175,55,0.3)]">Mi Perfil</button>
                <?php endif; ?>
            <?php else: ?>
                <a href="/login" class="page-transition mt-8 text-sm uppercase tracking-widest border border-gold text-gold px-8 py-3 hover:bg-gold hover:text-black transition-colors">Iniciar Sesión</a>
            <?php endif; ?>
        </nav>
    </div>

    </div>

    <!-- Modal Central de Perfil (Sólo Clientes) -->
    <?php if(isset($_SESSION['user_id']) && (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1)): ?>
    <div x-data="clientProfileModal()" 
         @open-profile.window="initProfile()" 
         x-show="isOpen" 
         class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm" 
         style="display: none;" 
         x-transition.opacity>
        
        <div @click.away="isOpen = false" class="bg-[#0a0a0a] border border-[#222] w-full max-w-4xl relative shadow-[0_0_50px_rgba(212,175,55,0.15)] rounded-2xl flex flex-col max-h-[90vh] overflow-hidden animate__animated animate__zoomIn animate__faster">
            <!-- Header Modal -->
            <div class="p-6 border-b border-[#222] bg-[#111] flex justify-between items-center shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gold/10 border border-gold/30 flex items-center justify-center text-gold text-xl font-bold">
                        <span x-text="perfil.nombres ? perfil.nombres.charAt(0).toUpperCase() : 'U'"></span>
                    </div>
                    <div>
                        <h2 class="text-xl font-heading font-bold text-white tracking-widest uppercase" x-text="perfil.nombres ? (perfil.nombres + ' ' + perfil.apellidos) : 'Mi Perfil'"></h2>
                    </div>
                </div>
                <button type="button" @click="isOpen = false" class="text-gray-500 hover:text-gold transition-colors p-2 bg-[#1a1a1a] rounded-lg border border-[#333]">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>
            </div>

            <!-- Tabs Nav -->
            <div class="flex border-b border-[#222] bg-[#0c0c0c] shrink-0">
                <button @click="tab = 'pedidos'" :class="tab === 'pedidos' ? 'text-gold border-b-2 border-gold bg-[#111]' : 'text-gray-400 hover:text-white'" class="flex-1 py-4 text-xs font-bold uppercase tracking-widest transition-colors flex items-center justify-center gap-2">
                    <i class="ph-bold ph-shopping-bag text-lg"></i> Mis Pedidos
                </button>
                <button @click="tab = 'perfil'" :class="tab === 'perfil' ? 'text-gold border-b-2 border-gold bg-[#111]' : 'text-gray-400 hover:text-white'" class="flex-1 py-4 text-xs font-bold uppercase tracking-widest transition-colors flex items-center justify-center gap-2">
                    <i class="ph-bold ph-user-gear text-lg"></i> Mis Datos
                </button>
                <button @click="tab = 'reservas'" :class="tab === 'reservas' ? 'text-gold border-b-2 border-gold bg-[#111]' : 'text-gray-400 hover:text-white'" class="flex-1 py-4 text-xs font-bold uppercase tracking-widest transition-colors flex items-center justify-center gap-2">
                    <i class="ph-bold ph-calendar-check text-lg"></i> Reservas
                </button>
            </div>

            <!-- Contenido Scrollable -->
            <div class="flex-1 overflow-y-auto p-6 bg-[#0a0a0a] relative">
                
                <!-- Tab: Mis Pedidos -->
                <div x-show="tab === 'pedidos'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200 absolute inset-0"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-4"
                     class="w-full">
                    <div x-show="loading" class="flex justify-center py-12">
                        <i class="ph-bold ph-spinner-gap text-4xl text-gold animate-spin"></i>
                    </div>
                    
                    <div x-show="!loading && pedidos.length === 0" class="text-center py-12">
                        <div class="w-20 h-20 bg-[#111] rounded-full flex items-center justify-center mx-auto mb-4 border border-[#222]">
                            <i class="ph ph-shopping-bag text-4xl text-gray-500"></i>
                        </div>
                        <h3 class="text-white font-bold text-lg mb-2">Aún no tienes pedidos</h3>
                        <p class="text-gray-400 text-sm mb-6">Explora nuestra colección y realiza tu primera compra.</p>
                        <button @click="isOpen = false; window.location.href='/#productos'" class="bg-gold text-black px-6 py-2.5 rounded text-xs font-bold uppercase tracking-widest hover:bg-white transition-colors">
                            Ver Productos
                        </button>
                    </div>

                    <div x-show="!loading && pedidos.length > 0" class="space-y-4">
                        <template x-for="pedido in pedidos" :key="pedido.id">
                            <div class="bg-[#111] border border-[#222] rounded-xl p-5 hover:border-gold/30 transition-colors">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <p class="text-white font-bold text-lg">Pedido #<span x-text="String(pedido.id).padStart(6, '0')"></span></p>
                                        <p class="text-gray-500 text-xs mt-1" x-text="new Date(pedido.creado_en).toLocaleString()"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-gold font-bold text-lg">S/ <span x-text="Number(pedido.total).toFixed(2)"></span></p>
                                        <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1" x-text="pedido.metodo_pago"></p>
                                    </div>
                                </div>
                                
                                <!-- Tracking Visual -->
                                <div class="relative pt-6 pb-2">
                                    <!-- Linea Base -->
                                    <div class="absolute top-8 left-4 right-4 h-1 bg-[#222] rounded-full z-0"></div>
                                    
                                    <!-- Progreso (calculado en Alpine) -->
                                    <div class="absolute top-8 left-4 h-1 bg-gold rounded-full z-0 transition-all duration-1000" 
                                         :style="`width: ${pedido.estado_pedido === 'PENDIENTE' ? '0%' : (pedido.estado_pedido === 'EN_CAMINO' ? '50%' : (pedido.estado_pedido === 'ENTREGADO' ? '100%' : '0%'))}`"></div>
                                    
                                    <div class="relative z-10 flex justify-between">
                                        <!-- Step 1: Pendiente/Pagado -->
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"
                                                 :class="['PENDIENTE', 'PAGADO', 'EN_CAMINO', 'ENTREGADO'].includes(pedido.estado_pedido) ? 'bg-gold text-black shadow-[0_0_10px_rgba(212,175,55,0.5)]' : 'bg-[#222] text-gray-500'">
                                                <i class="ph-fill ph-check"></i>
                                            </div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider"
                                                  :class="['PENDIENTE', 'PAGADO', 'EN_CAMINO', 'ENTREGADO'].includes(pedido.estado_pedido) ? 'text-gold' : 'text-gray-500'">Procesando</span>
                                        </div>
                                        
                                        <!-- Step 2: En Camino -->
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"
                                                 :class="['EN_CAMINO', 'ENTREGADO'].includes(pedido.estado_pedido) ? 'bg-gold text-black shadow-[0_0_10px_rgba(212,175,55,0.5)]' : 'bg-[#222] text-gray-500'">
                                                <i class="ph-fill ph-truck" x-show="['EN_CAMINO', 'ENTREGADO'].includes(pedido.estado_pedido)"></i>
                                                <span x-show="!['EN_CAMINO', 'ENTREGADO'].includes(pedido.estado_pedido)">2</span>
                                            </div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider"
                                                  :class="['EN_CAMINO', 'ENTREGADO'].includes(pedido.estado_pedido) ? 'text-gold' : 'text-gray-500'">En Camino</span>
                                        </div>
                                        
                                        <!-- Step 3: Entregado -->
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"
                                                 :class="pedido.estado_pedido === 'ENTREGADO' ? 'bg-gold text-black shadow-[0_0_10px_rgba(212,175,55,0.5)]' : 'bg-[#222] text-gray-500'">
                                                <i class="ph-fill ph-house" x-show="pedido.estado_pedido === 'ENTREGADO'"></i>
                                                <span x-show="pedido.estado_pedido !== 'ENTREGADO'">3</span>
                                            </div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider"
                                                  :class="pedido.estado_pedido === 'ENTREGADO' ? 'text-gold' : 'text-gray-500'">Entregado</span>
                                        </div>
                                    </div>
                                    
                                    <div x-show="pedido.estado_pedido === 'CANCELADO'" class="mt-4 p-3 bg-red-900/20 border border-red-500/30 rounded text-center">
                                        <p class="text-red-400 text-xs font-bold uppercase tracking-widest"><i class="ph-bold ph-warning"></i> Pedido Cancelado</p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Tab: Mi Perfil -->
                <div x-show="tab === 'perfil'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200 absolute inset-0 z-0"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-4"
                     class="w-full" style="display: none;">
                    <form @submit.prevent="saveProfile" class="space-y-5 max-w-2xl mx-auto py-2 relative z-10">
                        <div x-show="msgExito" class="bg-green-900/30 border border-green-500/50 text-green-400 p-3 rounded text-sm font-semibold text-center mb-4" x-text="msgExito"></div>
                        <div x-show="msgError" class="bg-red-900/30 border border-red-500/50 text-red-400 p-3 rounded text-sm font-semibold text-center mb-4" x-text="msgError"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Nombres</label>
                                <input type="text" x-model="perfil.nombres" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Apellidos</label>
                                <input type="text" x-model="perfil.apellidos" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Correo Electrónico (No modificable)</label>
                                <input type="email" x-model="perfil.email" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-500 outline-none rounded-lg cursor-not-allowed" disabled>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Teléfono / WhatsApp</label>
                                <input type="text" x-model="perfil.telefono" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-[#222]">
                            <h4 class="text-sm text-gold font-bold uppercase tracking-widest mb-4">Seguridad (Cambio de Contraseña)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div class="space-y-1">
                                    <label class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Contraseña Actual</label>
                                    <input type="password" x-model="perfil.password_actual" placeholder="Si vas a cambiarla" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Nueva Contraseña</label>
                                    <input type="password" x-model="perfil.password_nueva" placeholder="Mínimo 6 caracteres" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Repetir Nueva</label>
                                    <input type="password" x-model="perfil.password_confirmacion" placeholder="Confirma la contraseña" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg">
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-500 mt-2">* Deja estos campos en blanco si no deseas cambiar tu contraseña actual.</p>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" :disabled="saving" class="bg-gold text-black px-8 py-3 rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-white transition-colors shadow-[0_0_15px_rgba(212,175,55,0.3)] disabled:opacity-50 flex items-center gap-2">
                                <i x-show="saving" class="ph-bold ph-spinner animate-spin text-lg"></i>
                                <span x-text="saving ? 'Guardando...' : 'Guardar Cambios'"></span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tab: Reservas -->
                <div x-show="tab === 'reservas'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200 absolute inset-0"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-4"
                     class="w-full flex flex-col items-center justify-center py-16 text-center" style="display: none;">
                    <div class="relative w-24 h-24 mb-6">
                        <div class="absolute inset-0 bg-gold/20 rounded-full animate-ping"></div>
                        <div class="relative w-full h-full bg-[#111] rounded-full border border-gold/50 flex items-center justify-center">
                            <i class="ph-fill ph-calendar-star text-4xl text-gold"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-heading font-bold text-white mb-2 uppercase tracking-widest">En Desarrollo</h3>
                    <p class="text-gray-400 max-w-sm mx-auto text-sm">Nuestro sistema de reservas online exclusivas está siendo preparado. ¡Pronto podrás asegurar tus postres favoritos anticipadamente!</p>
                </div>

            </div>

            <!-- Footer Modal -->
            <div class="p-4 border-t border-[#222] bg-[#0c0c0c] shrink-0 flex justify-between items-center">
                <form action="/logout" method="POST" class="m-0">
                    <button type="submit" class="text-red-500 hover:text-red-400 text-xs font-bold uppercase tracking-widest flex items-center gap-2 transition-colors px-4 py-2 hover:bg-red-900/20 rounded">
                        <i class="ph-bold ph-sign-out text-lg"></i> Cerrar Sesión
                    </button>
                </form>
                <div class="text-[10px] text-gray-600 uppercase tracking-widest font-semibold flex items-center gap-2">
                    <i class="ph-fill ph-shield-check text-gold text-sm"></i> Área Segura
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('clientProfileModal', () => ({
                isOpen: false,
                tab: 'pedidos',
                loading: true,
                saving: false,
                msgExito: '',
                msgError: '',
                pedidos: [],
                perfil: {
                    nombres: '',
                    apellidos: '',
                    email: '',
                    telefono: '',
                    password_actual: '',
                    password_nueva: '',
                    password_confirmacion: ''
                },

                initProfile() {
                    this.isOpen = true;
                    this.tab = 'pedidos';
                    this.msgExito = '';
                    this.msgError = '';
                    this.fetchData();
                },

                async fetchData() {
                    this.loading = true;
                    try {
                        const [resPerfil, resPedidos] = await Promise.all([
                            fetch('/api/cliente/perfil'),
                            fetch('/api/cliente/pedidos')
                        ]);
                        
                        if (resPerfil.ok) {
                            const dataPerfil = await resPerfil.json();
                            this.perfil.nombres = dataPerfil.nombres || '';
                            this.perfil.apellidos = dataPerfil.apellidos || '';
                            this.perfil.email = dataPerfil.email || '';
                            this.perfil.telefono = dataPerfil.telefono || '';
                            this.perfil.password_actual = '';
                            this.perfil.password_nueva = '';
                            this.perfil.password_confirmacion = '';
                        }
                        
                        if (resPedidos.ok) {
                            this.pedidos = await resPedidos.json();
                        }
                    } catch (error) {
                        console.error("Error loading profile:", error);
                    } finally {
                        this.loading = false;
                    }
                },

                async saveProfile() {
                    this.saving = true;
                    this.msgExito = '';
                    this.msgError = '';
                    
                    // Basic validation for password
                    if (this.perfil.password_nueva || this.perfil.password_actual || this.perfil.password_confirmacion) {
                        if (!this.perfil.password_actual) {
                            this.msgError = 'Debes ingresar tu contraseña actual para cambiarla.';
                            this.saving = false;
                            return;
                        }
                        if (this.perfil.password_nueva !== this.perfil.password_confirmacion) {
                            this.msgError = 'Las contraseñas nuevas no coinciden.';
                            this.saving = false;
                            return;
                        }
                        if (this.perfil.password_nueva.length < 6) {
                            this.msgError = 'La nueva contraseña debe tener al menos 6 caracteres.';
                            this.saving = false;
                            return;
                        }
                    }

                    try {
                        const res = await fetch('/api/cliente/update', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(this.perfil)
                        });
                        
                        const result = await res.json();
                        if (res.ok && result.success) {
                            this.msgExito = result.message || 'Perfil actualizado con éxito.';
                            this.perfil.password_actual = '';
                            this.perfil.password_nueva = '';
                            this.perfil.password_confirmacion = '';
                        } else {
                            this.msgError = result.error || 'Ocurrió un error al guardar.';
                        }
                    } catch (error) {
                        this.msgError = 'Error de conexión. Intente nuevamente.';
                    } finally {
                        this.saving = false;
                        setTimeout(() => { this.msgExito = ''; this.msgError = ''; }, 4000);
                    }
                }
            }));
        });
    </script>
    <?php endif; ?>

    <!-- Main Content Wrapper -->
    <main id="main-content" class="animate__animated animate__fadeIn">
