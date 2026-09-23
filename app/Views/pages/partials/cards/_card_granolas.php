<?php
/**
 * Tarjeta de Producto - Granolas
 * @var array $p Datos del producto
 * @var int $index Índice para la animación (opcional, default 0)
 */
$index = $index ?? 0;
?>
<div @click="openProductModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)" 
     class="premium-card group cursor-pointer text-center animate-on-scroll flex flex-col justify-between" 
     data-animation="animate__fadeInUp" 
     style="animation-delay: <?= ($index % 4) * 0.1 ?>s;">
    <div class="p-6 md:p-8 flex-grow flex flex-col justify-center">
        <h4 class="text-xl font-heading font-bold text-white mb-1"><?= htmlspecialchars($p['nombre']) ?></h4>
        <p class="text-gold text-xs uppercase tracking-widest mb-6"><?= htmlspecialchars($p['descripcion'] ?? '') ?></p>
        <?php if (!empty($p['variantes'])): 
            $var = $p['variantes'][0]; // Granolas tienen 1 variante que guarda precios principal y secundario
        ?>
            <div class="text-3xl font-bold text-gold mb-2">
                S/ <?= number_format($var['precio'], 2) ?> 
                <?php if (!empty($var['precio_secundario'])): ?>
                    <span class="text-sm text-gray-500 font-normal block sm:inline">S/ <?= number_format($var['precio_secundario'], 2) ?> <?= htmlspecialchars($var['texto_secundario'] ?? '') ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
