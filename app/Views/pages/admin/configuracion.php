<?php require __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="mb-8">
    <h1 class="text-3xl font-heading font-bold text-white tracking-wide uppercase mb-2">Configuración Web</h1>
    <p class="text-gray-400 text-sm font-light">Edita los textos dinámicos de tu página principal. Mantén la longitud adecuada para no romper el diseño.</p>
</div>

<form action="/admin/configuracion/update" method="POST" class="bg-[#111] border border-[#222] rounded-xl p-6 md:p-8 shadow-2xl">
    <!-- CSRF Token -->
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

    <div class="space-y-10">
        
        <!-- Bloque 1: Hero Section -->
        <div>
            <h3 class="text-gold font-heading text-xl uppercase tracking-widest mb-6 pb-2 border-b border-[#333]">1. Cabecera (Hero Section)</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Título Principal (Hero)</label>
                    <input type="text" name="hero_titulo" maxlength="100" required 
                           value="<?= htmlspecialchars($configWeb['hero_titulo'] ?? '') ?>"
                           class="w-full bg-[#0a0a0a] border border-[#333] text-white px-4 py-3 rounded focus:outline-none focus:border-gold transition-colors text-sm">
                    <p class="text-[10px] text-gray-500 mt-1">Ej: Come Sano Vive Mejor!</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Subtítulo (Ubicación)</label>
                    <input type="text" name="hero_subtitulo" maxlength="50" required 
                           value="<?= htmlspecialchars($configWeb['hero_subtitulo'] ?? '') ?>"
                           class="w-full bg-[#0a0a0a] border border-[#333] text-white px-4 py-3 rounded focus:outline-none focus:border-gold transition-colors text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Descripción Corta</label>
                    <textarea name="hero_descripcion" maxlength="255" rows="2" required 
                              class="w-full bg-[#0a0a0a] border border-[#333] text-white px-4 py-3 rounded focus:outline-none focus:border-gold transition-colors text-sm resize-none"><?= htmlspecialchars($configWeb['hero_descripcion'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Bloque 2: Catálogo -->
        <div>
            <h3 class="text-gold font-heading text-xl uppercase tracking-widest mb-6 pb-2 border-b border-[#333]">2. Textos del Catálogo</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Título del Catálogo</label>
                    <input type="text" name="catalogo_titulo" maxlength="50" required 
                           value="<?= htmlspecialchars($configWeb['catalogo_titulo'] ?? '') ?>"
                           class="w-full bg-[#0a0a0a] border border-[#333] text-white px-4 py-3 rounded focus:outline-none focus:border-gold transition-colors text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Descripción del Catálogo</label>
                    <input type="text" name="catalogo_descripcion" maxlength="150" required 
                           value="<?= htmlspecialchars($configWeb['catalogo_descripcion'] ?? '') ?>"
                           class="w-full bg-[#0a0a0a] border border-[#333] text-white px-4 py-3 rounded focus:outline-none focus:border-gold transition-colors text-sm">
                </div>
            </div>
        </div>

        <!-- Bloque 3: Nuestra Esencia -->
        <div>
            <h3 class="text-gold font-heading text-xl uppercase tracking-widest mb-6 pb-2 border-b border-[#333]">3. Nuestra Esencia (Pestañas)</h3>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Misión</label>
                    <textarea name="nosotros_mision" rows="4" required 
                              class="w-full bg-[#0a0a0a] border border-[#333] text-white px-4 py-3 rounded focus:outline-none focus:border-gold transition-colors text-sm resize-none"><?= htmlspecialchars($configWeb['nosotros_mision'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Visión</label>
                    <textarea name="nosotros_vision" rows="4" required 
                              class="w-full bg-[#0a0a0a] border border-[#333] text-white px-4 py-3 rounded focus:outline-none focus:border-gold transition-colors text-sm resize-none"><?= htmlspecialchars($configWeb['nosotros_vision'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Historia</label>
                    <textarea name="nosotros_historia" rows="4" required 
                              class="w-full bg-[#0a0a0a] border border-[#333] text-white px-4 py-3 rounded focus:outline-none focus:border-gold transition-colors text-sm resize-none"><?= htmlspecialchars($configWeb['nosotros_historia'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Bloque 4: Footer y Contacto -->
        <div>
            <h3 class="text-gold font-heading text-xl uppercase tracking-widest mb-6 pb-2 border-b border-[#333]">4. Contacto y Footer</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">WhatsApp de Pedidos</label>
                    <input type="text" name="whatsapp_numero" maxlength="20" required 
                           value="<?= htmlspecialchars($configWeb['whatsapp_numero'] ?? '') ?>"
                           class="w-full bg-[#0a0a0a] border border-[#333] text-white px-4 py-3 rounded focus:outline-none focus:border-gold transition-colors text-sm"
                           placeholder="Ej: 51984247684">
                    <p class="text-[10px] text-gray-500 mt-1">Sin espacios ni símbolos. Sólo números y código de país al inicio.</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Texto Corto Footer</label>
                    <textarea name="footer_descripcion" maxlength="200" rows="2" required 
                              class="w-full bg-[#0a0a0a] border border-[#333] text-white px-4 py-3 rounded focus:outline-none focus:border-gold transition-colors text-sm resize-none"><?= htmlspecialchars($configWeb['footer_descripcion'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

    </div>

    <!-- Actions -->
    <div class="mt-10 pt-6 border-t border-[#333] flex justify-end">
        <button type="submit" class="bg-gold text-black font-bold text-sm tracking-widest uppercase px-8 py-3 rounded hover:bg-white hover:scale-105 transition-all shadow-[0_0_15px_rgba(212,175,55,0.3)]">
            Guardar Cambios
        </button>
    </div>
</form>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
