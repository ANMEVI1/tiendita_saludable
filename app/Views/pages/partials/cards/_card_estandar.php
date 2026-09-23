<?php
/**
 * Tarjeta de Producto - Estándar
 * @var array $p Datos del producto
 * @var int $index Índice para la animación (opcional, default 0)
 */
$index = $index ?? 0;
?>
<div @click="openProductModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)" 
     class="premium-card group cursor-pointer animate-on-scroll" 
     data-animation="animate__fadeInUp" 
     style="animation-delay: <?= ($index % 4) * 0.1 ?>s;">
    <div class="p-6 flex flex-col h-full">
        <h4 class="text-xl font-heading font-bold text-white mb-2"><?= htmlspecialchars($p['nombre']) ?></h4>
        <p class="text-gray-400 text-sm font-light mb-4 flex-grow"><?= htmlspecialchars($p['descripcion']) ?></p>
        <div class="flex justify-between items-center mt-6 pt-4 border-t border-[#333]">
            <span class="text-gold font-semibold text-xl">S/ <?= number_format($p['precio_regular'] ?? 0, 2) ?></span>
        </div>
    </div>
</div>
