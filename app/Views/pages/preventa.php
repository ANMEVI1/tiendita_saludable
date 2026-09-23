<?php $extraCss = 'assets/css/preventa.css'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<main>
        <!-- Hero Preventa -->
        <section class="relative py-20 lg:py-32 flex items-center justify-center border-b border-[#222]">
            <div class="absolute inset-0 hero-gradient-preventa z-0 animate__animated animate__fadeIn animate__slow"></div>
            
            <div class="relative z-10 text-center px-4 max-w-4xl mx-auto animate-on-scroll" data-animation="animate__fadeInUp">
                <div class="inline-flex items-center gap-2 border border-gold/50 bg-gold/10 px-4 py-2 mb-8">
                    <i class="ph-fill ph-clock text-gold"></i>
                    <span class="text-gold text-xs font-bold tracking-[0.2em] uppercase">Oferta por Tiempo Limitado</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-heading font-black text-white mb-6 leading-tight glow-text">Reserva la Exclusividad</h1>
                <p class="text-gray-300 text-lg md:text-xl font-light tracking-wide mb-12 max-w-2xl mx-auto">Asegura tu pedido antes de que se agote el stock. Producción limitada artesanalmente en Tumbes.</p>
                
                <!-- Countdown -->
                <div class="flex justify-center gap-4 md:gap-8 mb-12 animate-on-scroll animate__delay-1s" data-animation="animate__fadeInUp">
                    <div class="countdown-box px-6 py-4 flex flex-col items-center min-w-[100px]">
                        <span class="text-4xl font-heading font-bold text-gold">05</span>
                        <span class="text-xs uppercase tracking-widest text-gray-400 mt-2">Días</span>
                    </div>
                    <div class="text-4xl font-heading font-bold text-gold/50 self-center">:</div>
                    <div class="countdown-box px-6 py-4 flex flex-col items-center min-w-[100px]">
                        <span class="text-4xl font-heading font-bold text-gold">14</span>
                        <span class="text-xs uppercase tracking-widest text-gray-400 mt-2">Horas</span>
                    </div>
                    <div class="text-4xl font-heading font-bold text-gold/50 self-center hidden md:block">:</div>
                    <div class="countdown-box px-6 py-4 flex flex-col items-center min-w-[100px] hidden md:flex">
                        <span class="text-4xl font-heading font-bold text-gold">32</span>
                        <span class="text-xs uppercase tracking-widest text-gray-400 mt-2">Min</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Productos en Preventa -->
        <section class="py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <?php if (empty($preventas)): ?>
                        <p class="text-gray-400 col-span-full text-center py-8">No hay productos en preventa actualmente.</p>
                    <?php else: ?>
                        <?php foreach($preventas as $index => $p): ?>
                            <?php 
                                $whatsapp_numero = $configWeb['whatsapp_numero'] ?? '51900000000';
                                include __DIR__ . '/partials/cards/_card_preventa.php'; 
                            ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
