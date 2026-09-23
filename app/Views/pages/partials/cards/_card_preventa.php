<?php
/**
 * Tarjeta de Producto - Preventa
 * @var array $p Datos del producto
 * @var int $index Índice para la animación (opcional, default 0)
 * @var string $whatsapp_numero Número de WhatsApp para reservas
 */
$index = $index ?? 0;
$animClass = ($index % 2 == 0) ? 'animate__fadeInLeft' : 'animate__fadeInRight';
$whatsapp = $whatsapp_numero ?? '51900000000';
$mensaje = "Hola, deseo reservar el " . htmlspecialchars($p['nombre']) . " en preventa.";
?>
<div class="preventa-card group flex flex-col md:flex-row animate-on-scroll" data-animation="<?= $animClass ?>">
    <div class="stock-indicator">Preventa Exclusiva</div>
    <div class="w-full md:w-2/5 h-80 md:h-auto relative overflow-hidden bg-[#111]">
        <img src="<?= $p['imagen_url'] ? htmlspecialchars('/' . ltrim($p['imagen_url'], '/')) : 'https://placehold.co/600x400/111111/333333?text=Preventa' ?>" 
             alt="<?= htmlspecialchars($p['nombre']) ?>" 
             class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-transform duration-700">
    </div>
    <div class="w-full md:w-3/5 p-8 flex flex-col justify-center">
        <h2 class="text-3xl font-heading font-bold text-white mb-2"><?= htmlspecialchars($p['nombre']) ?></h2>
        <p class="text-gray-400 text-sm font-light mb-6"><?= htmlspecialchars($p['descripcion']) ?></p>
        
        <div class="flex items-end gap-4 mb-8">
            <div>
                <span class="block text-xs uppercase tracking-widest text-gray-500 mb-1">Precio Preventa</span>
                <span class="text-3xl font-bold text-gold">S/ <?= number_format($p['precio_regular'] ?? 0, 2) ?></span>
            </div>
            <!-- If we had a regular price vs preventa price, we could show it here. 
                 Assuming $p['precio_regular'] is the preventa price for now. -->
        </div>
        
        <a href="https://wa.me/<?= htmlspecialchars($whatsapp) ?>?text=<?= urlencode($mensaje) ?>" target="_blank" class="block text-center border border-gold text-gold font-bold uppercase tracking-widest py-4 hover:bg-gold hover:text-black transition-colors">
            <i class="ph-fill ph-whatsapp-logo mr-2"></i> Reservar Ahora
        </a>
    </div>
</div>
