<?php
/**
 * Tarjeta de Producto - Lista Variantes
 * @var array $p Datos del producto
 * @var int $index Índice para la animación (opcional, default 0)
 */
$index = $index ?? 0;
?>
<div @click="openProductModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)" 
     class="premium-card group cursor-pointer overflow-hidden flex flex-col animate-on-scroll" 
     data-animation="animate__fadeInUp" 
     style="animation-delay: <?= ($index % 3) * 0.15 ?>s;">
    <div class="h-40 md:h-48 bg-[#111] relative overflow-hidden">
        <img src="<?= $p['imagen_url'] ? htmlspecialchars('/' . ltrim($p['imagen_url'], '/')) : '' ?>" alt="<?= htmlspecialchars($p['nombre']) ?>" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
    </div>
    <div class="p-6 md:p-8 flex-grow flex flex-col">
        <h4 class="text-xl md:text-2xl font-heading font-bold text-white mb-2"><?= htmlspecialchars($p['nombre']) ?></h4>
        <p class="text-gray-400 text-sm font-light mb-6"><?= htmlspecialchars($p['descripcion']) ?></p>
        
        <?php if (!empty($p['variantes'])): ?>
            <ul class="text-sm text-gray-300 space-y-2 mb-6">
                <?php foreach($p['variantes'] as $var): ?>
                    <li class="flex justify-between border-b border-[#333] pb-1">
                        <span><?= htmlspecialchars($var['nombre']) ?>:</span> 
                        <span class="text-gold">S/ <?= number_format($var['precio'], 2) ?> <?= !empty($var['texto_secundario']) ? htmlspecialchars($var['texto_secundario']) : '' ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if (!empty($p['caracteristicas'])): ?>
            <?php foreach($p['caracteristicas'] as $grupo => $valores): ?>
                <p class="text-xs text-gray-500 mb-2 uppercase tracking-widest"><?= htmlspecialchars($grupo) ?></p>
                <p class="text-sm text-gray-300 leading-relaxed mb-6"><?= htmlspecialchars(implode(', ', $valores)) ?></p>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($p['precio_regular'])): ?>
            <div class="mt-auto flex justify-between items-center pt-4 border-t border-[#333]">
                <span class="text-gray-400 text-xs uppercase tracking-widest">Precio Pack</span>
                <span class="text-gold font-semibold text-2xl">S/ <?= number_format($p['precio_regular'], 2) ?></span>
            </div>
        <?php endif; ?>
    </div>
</div>
