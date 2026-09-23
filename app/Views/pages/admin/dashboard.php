<?php require __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-3xl font-heading font-bold text-white tracking-wide uppercase mb-2">Panel de Control</h1>
        <p class="text-gray-400 text-sm font-light">Resumen general de tu negocio.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <!-- Card Productos -->
    <div class="bg-[#111] border border-[#222] rounded-xl p-6 relative overflow-hidden group hover:border-gold/30 transition-colors">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="ph-fill ph-package text-6xl text-gold"></i>
        </div>
        <div class="relative z-10">
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">Total Productos</p>
            <h3 class="text-4xl font-bold text-white mb-2"><?= number_format($stats['productos']) ?></h3>
            <a href="/admin/productos" class="text-xs text-gold hover:text-white flex items-center gap-1 transition-colors">
                Administrar <i class="ph ph-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card Categorías -->
    <div class="bg-[#111] border border-[#222] rounded-xl p-6 relative overflow-hidden group hover:border-gold/30 transition-colors">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="ph-fill ph-folders text-6xl text-gold"></i>
        </div>
        <div class="relative z-10">
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">Categorías</p>
            <h3 class="text-4xl font-bold text-white mb-2"><?= number_format($stats['categorias']) ?></h3>
            <a href="/admin/productos" class="text-xs text-gold hover:text-white flex items-center gap-1 transition-colors">
                Ver Catálogo <i class="ph ph-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card Pedidos -->
    <div class="bg-[#111] border border-[#222] rounded-xl p-6 relative overflow-hidden group hover:border-gold/30 transition-colors opacity-50">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="ph-fill ph-shopping-cart text-6xl text-gold"></i>
        </div>
        <div class="relative z-10">
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">Pedidos Online</p>
            <h3 class="text-4xl font-bold text-white mb-2">Próximamente</h3>
            <span class="text-xs text-gray-500">Módulo en desarrollo</span>
        </div>
    </div>
</div>

<div class="bg-[#0a0a0a] border border-[#222] rounded-xl p-8 text-center max-w-2xl mx-auto mt-10">
    <div class="w-16 h-16 rounded-full bg-gold/10 border border-gold/30 flex items-center justify-center mx-auto mb-4 shadow-[0_0_15px_rgba(212,175,55,0.1)]">
        <i class="ph-fill ph-star text-gold text-2xl"></i>
    </div>
    <h2 class="text-xl font-heading font-bold text-white mb-2">Bienvenido, Admin</h2>
    <p class="text-sm text-gray-400 font-light leading-relaxed">
        Desde este panel puedes controlar milimétricamente el contenido de tu web. Navega usando el menú lateral para ajustar tu catálogo de productos o para editar los textos de presentación (Misión, Visión, Textos del Hero). Todo cambio se reflejará al instante en tu web pública, manteniendo el diseño de alto impacto.
    </p>
</div>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
