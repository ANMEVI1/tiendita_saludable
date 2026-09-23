<?php require __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="mb-8 flex justify-between items-end flex-wrap gap-4">
    <div>
        <h1 class="text-3xl font-heading font-bold text-white tracking-wide uppercase mb-2">Panel de Control</h1>
        <p class="text-gray-400 text-sm font-light">Resumen general y métricas en tiempo real de tu negocio.</p>
    </div>
</div>

<!-- Grid de Tarjetas de Estadísticas -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
    <!-- Card Productos -->
    <div class="bg-[#111] border border-[#222] rounded-xl p-6 relative overflow-hidden group hover:border-gold/40 transition-colors">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="ph-fill ph-package text-6xl text-gold"></i>
        </div>
        <div class="relative z-10">
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">Productos Activos</p>
            <h3 class="text-4xl font-bold text-white mb-2"><?= number_format($stats['productos']) ?></h3>
            <a href="/admin/productos" class="text-xs text-gold hover:text-white flex items-center gap-1 transition-colors font-semibold">
                Gestionar Catálogo <i class="ph ph-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card Categorías -->
    <div class="bg-[#111] border border-[#222] rounded-xl p-6 relative overflow-hidden group hover:border-gold/40 transition-colors">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="ph-fill ph-folders text-6xl text-gold"></i>
        </div>
        <div class="relative z-10">
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">Categorías</p>
            <h3 class="text-4xl font-bold text-white mb-2"><?= number_format($stats['categorias']) ?></h3>
            <a href="/admin/productos" class="text-xs text-gold hover:text-white flex items-center gap-1 transition-colors font-semibold">
                Ver Categorías <i class="ph ph-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card Usuarios -->
    <div class="bg-[#111] border border-[#222] rounded-xl p-6 relative overflow-hidden group hover:border-gold/40 transition-colors">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="ph-fill ph-users text-6xl text-gold"></i>
        </div>
        <div class="relative z-10">
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">Usuarios Registrados</p>
            <h3 class="text-4xl font-bold text-white mb-2"><?= number_format($stats['usuarios']) ?></h3>
            <a href="/admin/usuarios" class="text-xs text-gold hover:text-white flex items-center gap-1 transition-colors font-semibold">
                Ver Usuarios <i class="ph ph-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card Clientes -->
    <div class="bg-[#111] border border-[#222] rounded-xl p-6 relative overflow-hidden group hover:border-gold/40 transition-colors">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="ph-fill ph-user-address text-6xl text-gold"></i>
        </div>
        <div class="relative z-10">
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">Clientes</p>
            <h3 class="text-3xl font-bold text-white mb-2"><?= number_format($stats['clientes']) ?></h3>
            <a href="/admin/clientes" class="text-[10px] text-gold hover:text-white flex items-center gap-1 transition-colors font-semibold">
                Ver Clientes <i class="ph ph-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card Pedidos Totales -->
    <div class="bg-[#111] border border-[#222] rounded-xl p-5 relative overflow-hidden group hover:border-gold/40 transition-colors">
        <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="ph-fill ph-shopping-bag text-5xl text-gold"></i>
        </div>
        <div class="relative z-10">
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">Pedidos Totales</p>
            <h3 class="text-3xl font-bold text-white mb-2"><?= number_format($stats['pedidos']) ?></h3>
            <a href="/admin/pedidos" class="text-[10px] text-gold hover:text-white flex items-center gap-1 transition-colors font-semibold">
                Ver Historial <i class="ph ph-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card Pedidos Pendientes -->
    <div class="bg-gold/10 border border-gold/30 rounded-xl p-5 relative overflow-hidden group hover:bg-gold/20 transition-colors">
        <div class="absolute top-0 right-0 p-3 opacity-20 group-hover:opacity-30 transition-opacity">
            <i class="ph-fill ph-clock-countdown text-5xl text-gold"></i>
        </div>
        <div class="relative z-10">
            <p class="text-gold text-xs font-semibold uppercase tracking-widest mb-1">Pendientes/Horneando</p>
            <h3 class="text-3xl font-bold text-white mb-2"><?= number_format($stats['pedidos_pendientes']) ?></h3>
            <a href="/admin/pedidos" class="text-[10px] text-gold hover:text-white flex items-center gap-1 transition-colors font-semibold">
                Atender Ahora <i class="ph ph-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Tablas de Actividad Reciente -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Últimos Productos -->
    <div class="bg-[#111] border border-[#222] rounded-xl p-6 shadow-xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-base font-heading font-bold text-white uppercase tracking-wider flex items-center gap-2">
                <i class="ph ph-package text-gold text-xl"></i> Últimos Productos Agregados
            </h3>
            <a href="/admin/productos" class="text-xs text-gold hover:text-white transition-colors">Ver todos</a>
        </div>
        <div class="space-y-4">
            <?php if (empty($ultimosProductos)): ?>
                <p class="text-gray-500 text-xs py-4 text-center">No hay productos recientes.</p>
            <?php else: ?>
                <?php foreach ($ultimosProductos as $up): ?>
                    <div class="flex items-center justify-between p-3 bg-[#151515] border border-[#222] rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-[#0a0a0a] border border-[#333] overflow-hidden flex items-center justify-center">
                                <?php if (!empty($up['imagen_url'])): ?>
                                    <img src="<?= htmlspecialchars(strpos($up['imagen_url'], 'http') === 0 ? $up['imagen_url'] : '/' . ltrim($up['imagen_url'], '/')) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="ph ph-package text-gray-500"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-white"><?= htmlspecialchars($up['nombre']) ?></p>
                                <p class="text-[10px] text-gray-500"><?= htmlspecialchars($up['categoria_nombre']) ?></p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-gold">
                            <?= $up['precio_regular'] ? 'S/ ' . number_format($up['precio_regular'], 2) : 'Variantes' ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Últimos Pedidos -->
    <div class="bg-[#111] border border-[#222] rounded-xl p-6 shadow-xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-base font-heading font-bold text-white uppercase tracking-wider flex items-center gap-2">
                <i class="ph ph-shopping-bag text-gold text-xl"></i> Pedidos Recientes
            </h3>
            <a href="/admin/pedidos" class="text-xs text-gold hover:text-white transition-colors">Gestor Completo</a>
        </div>
        <div class="space-y-4">
            <?php if (empty($ultimosPedidos)): ?>
                <p class="text-gray-500 text-xs py-4 text-center">No hay pedidos recientes.</p>
            <?php else: ?>
                <?php foreach ($ultimosPedidos as $uped): ?>
                    <div class="flex items-center justify-between p-3 bg-[#151515] border border-[#222] rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#0a0a0a] border border-[#333] flex items-center justify-center text-gold text-xs font-bold">
                                <?= strtoupper(substr($uped['cliente_nombres'], 0, 1)) ?>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-white">#<?= str_pad($uped['id'], 6, '0', STR_PAD_LEFT) ?> - <?= htmlspecialchars($uped['cliente_nombres']) ?></p>
                                <?php 
                                    $colorText = 'text-gray-500';
                                    if(in_array($uped['estado_pedido'], ['PENDIENTE', 'HORNEANDO'])) $colorText = 'text-orange-400 animate-pulse';
                                    if($uped['estado_pedido'] == 'ENTREGADO') $colorText = 'text-green-400';
                                ?>
                                <p class="text-[10px] <?= $colorText ?> font-bold tracking-wider uppercase"><?= $uped['estado_pedido'] ?></p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-gold">
                            S/ <?= number_format($uped['total'], 2) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
