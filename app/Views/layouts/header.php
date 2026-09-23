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
                    <a href="/login" class="page-transition hidden md:block text-xs uppercase tracking-widest border border-gold text-gold px-4 py-2 hover:bg-gold hover:text-black transition-colors">Entrar</a>
                    <a href="/login" class="page-transition md:hidden text-gray-400 hover:text-gold transition-colors"><i class="ph ph-user text-2xl"></i></a>
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
            <a href="/login" class="page-transition mt-8 text-sm uppercase tracking-widest border border-gold text-gold px-8 py-3 hover:bg-gold hover:text-black transition-colors">Iniciar Sesión</a>
        </nav>
    </div>

    <!-- Main Content Wrapper -->
    <main id="main-content" class="animate__animated animate__fadeIn">
