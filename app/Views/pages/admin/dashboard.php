<?php require __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="mb-8 flex justify-between items-end flex-wrap gap-4">
    <div>
        <h1 class="text-3xl font-heading font-bold text-white tracking-wide uppercase mb-2">Panel de Control</h1>
        <p class="text-gray-400 text-sm font-light">Resumen general y métricas en tiempo real de tu negocio.</p>
    </div>
</div>

<!-- Grid de Tarjetas de Estadísticas -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
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
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">Clientes Registrados</p>
            <h3 class="text-4xl font-bold text-white mb-2"><?= number_format($stats['clientes']) ?></h3>
            <a href="/admin/clientes" class="text-xs text-gold hover:text-white flex items-center gap-1 transition-colors font-semibold">
                Ver Clientes <i class="ph ph-arrow-right"></i>
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

    <!-- Últimos Usuarios -->
    <div class="bg-[#111] border border-[#222] rounded-xl p-6 shadow-xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-base font-heading font-bold text-white uppercase tracking-wider flex items-center gap-2">
                <i class="ph ph-users text-gold text-xl"></i> Cuentas Recientes
            </h3>
            <a href="/admin/usuarios" class="text-xs text-gold hover:text-white transition-colors">Ver todos</a>
        </div>
        <div class="space-y-4">
            <?php if (empty($ultimosUsuarios)): ?>
                <p class="text-gray-500 text-xs py-4 text-center">No hay usuarios recientes.</p>
            <?php else: ?>
                <?php foreach ($ultimosUsuarios as $uu): ?>
                    <div class="flex items-center justify-between p-3 bg-[#151515] border border-[#222] rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gold/10 text-gold border border-gold/30 flex items-center justify-center font-bold text-xs">
                                <?= strtoupper(substr($uu['email'], 0, 1)) ?>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-white"><?= htmlspecialchars($uu['email']) ?></p>
                                <p class="text-[10px] text-gray-500">Registrado en el sistema</p>
                            </div>
                        </div>
                        <span class="text-[10px] uppercase tracking-widest px-2 py-0.5 rounded bg-gray-800 text-gray-300">
                            <?= htmlspecialchars($uu['rol_nombre']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
