<?php
/**
 * Tarjeta de Producto - Complementarios
 * @var array $p Datos del producto
 * @var int $index Índice para la animación (opcional, default 0)
 */
$index = $index ?? 0;
?>
<?php if (!empty($p['icono'])): ?>
    <div @click="openProductModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)" 
         class="premium-card group cursor-pointer flex flex-col justify-center text-center p-8 animate-on-scroll" 
         data-animation="animate__fadeInUp" 
         style="animation-delay: <?= ($index % 4) * 0.1 ?>s;">
        <i class="<?= htmlspecialchars($p['icono']) ?> text-gold text-4xl mb-4"></i>
        <h4 class="text-xl font-heading font-bold text-white mb-2"><?= htmlspecialchars($p['nombre']) ?></h4>
        <p class="text-gray-400 text-sm mb-4"><?= htmlspecialchars($p['descripcion'] ?? '') ?></p>
        <span class="text-gold font-bold text-2xl">S/ <?= number_format($p['precio_regular'] ?? 0, 2) ?> <span class="text-sm font-normal text-gray-500">und</span></span>
    </div>
<?php else: ?>
    <div @click="openProductModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)" 
         class="premium-card group cursor-pointer animate-on-scroll" 
         data-animation="animate__fadeInUp" 
         style="animation-delay: <?= ($index % 4) * 0.1 ?>s;">
        <div class="h-48 md:h-56 overflow-hidden bg-[#111]">
            <img src="<?= $p['imagen_url'] ? htmlspecialchars('/' . ltrim($p['imagen_url'], '/')) : '' ?>" alt="<?= htmlspecialchars($p['nombre']) ?>" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-700">
        </div>
        <div class="p-6 text-center border-t border-[#333]">
            <h4 class="text-lg font-heading font-bold text-white"><?= htmlspecialchars($p['nombre']) ?></h4>
        </div>
    </div>
<?php endif; ?>
