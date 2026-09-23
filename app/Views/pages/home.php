<?php include __DIR__ . '/../layouts/header.php'; ?>

        <!-- Hero -->
        <section id="inicio" class="relative h-screen flex items-center justify-center">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <img src="assets/img/imagen-home/img_home.jpg" alt="Premium Bakery" class="w-full h-full object-cover opacity-40 animate__animated animate__fadeIn animate__slow">
                <div class="absolute inset-0 hero-gradient"></div>
            </div>

            <div class="relative z-10 text-center px-4 max-w-4xl mx-auto mt-16 animate-on-scroll" data-animation="animate__fadeInUp">
                <h2 class="text-gold tracking-[0.3em] text-xs md:text-sm lg:text-base font-semibold mb-4 md:mb-6 uppercase"><?= htmlspecialchars($configWeb['hero_subtitulo'] ?? 'Tumbes, Perú') ?></h2>
                <h1 class="text-4xl sm:text-5xl md:text-7xl font-heading font-bold text-white mb-6 md:mb-8 leading-tight"><?= htmlspecialchars($configWeb['hero_titulo'] ?? 'Come Sano Vive Mejor!') ?></h1>
                <p class="text-gray-300 text-base md:text-xl font-light tracking-wide mb-8 md:mb-10 max-w-2xl mx-auto"><?= htmlspecialchars($configWeb['hero_descripcion'] ?? 'Seleccionamos y llevamos a tu mesa los mejores productos naturales.') ?></p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 animate-on-scroll animate__delay-1s" data-animation="animate__fadeInUp">
                    <a href="#productos" class="inline-block bg-gold text-black font-semibold text-xs md:text-sm uppercase tracking-widest px-8 py-4 hover:bg-white transition-colors">Catálogo Completo</a>
                    <a href="/preventa" class="page-transition inline-block border border-gold text-gold font-semibold text-xs md:text-sm uppercase tracking-widest px-8 py-4 hover:bg-gold hover:text-black transition-colors">Ver Preventa Exclusiva</a>
                </div>
            </div>
        </section>

        <!-- Productos Catálogo Completo -->
        <section id="productos" class="py-16 md:py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 md:mb-20 border-b border-[#333] pb-8 md:pb-10 animate-on-scroll" data-animation="animate__fadeInDown">
                    <h2 class="text-3xl md:text-5xl font-heading font-bold text-white mb-4"><?= htmlspecialchars($configWeb['catalogo_titulo'] ?? 'La Colección') ?></h2>
                    <p class="text-gray-400 tracking-wide font-light max-w-2xl mx-auto text-sm md:text-base"><?= htmlspecialchars($configWeb['catalogo_descripcion'] ?? 'Nuestra línea completa de productos artesanales.') ?></p>
                </div>

                <?php include __DIR__ . '/partials/_catalogo.php'; ?>
            </div>
        </section>

        <!-- SECCIÓN: SOBRE NOSOTROS -->
        <section id="nosotros" class="py-16 md:py-24 bg-[#080808] border-t border-b border-[#222]">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                
                <h2 class="text-3xl md:text-5xl font-heading font-bold text-white mb-8 md:mb-12 animate-on-scroll" data-animation="animate__fadeInDown">Nuestra Esencia</h2>
                
                <!-- Controles de Pestañas (Se llenan dinámicamente vía JS) -->
                <div class="flex flex-wrap justify-center gap-2 md:gap-4 mb-10 md:mb-12" id="about-tabs-container">
                    <!-- Botones dinámicos -->
                </div>

                <!-- Contenedor del Texto Dinámico -->
                <div class="glass-effect rounded-2xl p-8 md:p-12 mx-auto max-w-4xl min-h-[250px] flex items-center justify-center relative overflow-hidden animate-on-scroll" data-animation="animate__zoomIn">
                    <i class="ph-fill ph-quotes text-gold/10 text-6xl md:text-8xl absolute top-6 right-8"></i>
                    <p id="dynamic-text" class="text-lg md:text-2xl font-light leading-relaxed text-gray-200 relative z-10 animate__animated animate__fadeInUp italic"></p>
                </div>
            </div>
        </section>
    </main>

    <!-- Parciales de E-commerce (Alpine) - FUERA del <main> para evitar stacking context -->
    <?php include __DIR__ . '/partials/_alpine_store.php'; ?>
    <?php include __DIR__ . '/partials/_carrito_slide.php'; ?>
    <?php include __DIR__ . '/partials/_modal_producto.php'; ?>
    <?php include __DIR__ . '/partials/_checkout_modal.php'; ?>


<?php include __DIR__ . '/../layouts/footer.php'; ?>
