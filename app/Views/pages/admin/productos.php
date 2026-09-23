<?php require __DIR__ . '/../../layouts/admin_header.php'; ?>

<div x-data="adminProductos()">
    <div class="mb-8 flex justify-between items-end flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-heading font-bold text-white tracking-wide uppercase mb-2">Catálogo</h1>
            <p class="text-gray-400 text-sm font-light">Gestiona tus productos, precios y categorías.</p>
        </div>
        <!-- Botón para nuevo producto -->
        <button class="bg-gold text-black font-bold text-sm tracking-widest uppercase px-6 py-3 rounded hover:bg-white hover:scale-105 transition-all shadow-[0_0_15px_rgba(212,175,55,0.3)] flex items-center gap-2">
            <i class="ph-bold ph-plus"></i> Nuevo Producto
        </button>
    </div>

    <div class="space-y-12">
        <?php foreach ($categorias as $cat): ?>
            <div class="bg-[#111] border border-[#222] rounded-xl overflow-hidden shadow-xl">
                <!-- Header Categoría -->
                <div class="bg-[#1a1a1a] border-b border-[#333] px-6 py-4 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-heading font-bold text-gold uppercase tracking-wider"><?= htmlspecialchars($cat['nombre']) ?></h3>
                        <p class="text-xs text-gray-400 font-light mt-1"><?= htmlspecialchars($cat['descripcion'] ?? '') ?> • Plantilla: <span class="text-white"><?= htmlspecialchars($cat['plantilla_html']) ?></span></p>
                    </div>
                    <button @click="openEditCatModal({ id: <?= $cat['id'] ?>, nombre: `<?= addslashes(htmlspecialchars($cat['nombre'])) ?>`, descripcion: `<?= addslashes(htmlspecialchars($cat['descripcion'] ?? '')) ?>` })" class="text-gray-400 hover:text-white transition-colors" title="Editar Categoría">
                        <i class="ph ph-pencil-simple text-xl"></i>
                    </button>
                </div>
                
                <!-- Tabla de Productos -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#222]">
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">Producto</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">Precio Base</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">Variantes / Extras</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a] text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#222]">
                            <?php if (empty($cat['productos'])): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">No hay productos en esta categoría.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($cat['productos'] as $prod): ?>
                                    <tr class="hover:bg-[#151515] transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <?php if ($prod['imagen_url']): ?>
                                                    <div class="w-10 h-10 rounded border border-[#333] overflow-hidden flex-shrink-0">
                                                        <img src="<?= htmlspecialchars('/' . ltrim($prod['imagen_url'], '/')) ?>" alt="Img" class="w-full h-full object-cover">
                                                    </div>
                                                <?php elseif ($prod['icono']): ?>
                                                    <div class="w-10 h-10 rounded border border-[#333] bg-[#0a0a0a] flex items-center justify-center flex-shrink-0">
                                                        <i class="<?= htmlspecialchars($prod['icono']) ?> text-gold text-lg"></i>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="w-10 h-10 rounded border border-[#333] bg-[#0a0a0a] flex items-center justify-center flex-shrink-0">
                                                        <i class="ph ph-image text-gray-600"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <p class="text-sm font-semibold text-white"><?= htmlspecialchars($prod['nombre']) ?></p>
                                                    <?php if ($prod['es_preventa']): ?>
                                                        <span class="inline-block mt-1 px-2 py-0.5 bg-gold/10 text-gold border border-gold/20 text-[9px] uppercase tracking-widest rounded">Preventa</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-300">
                                                <?= $prod['precio_regular'] ? 'S/ ' . number_format($prod['precio_regular'], 2) : '<span class="text-gray-600 italic">Múltiple</span>' ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                <?php if (!empty($prod['variantes'])): ?>
                                                    <span class="px-2 py-1 bg-blue-900/30 text-blue-400 border border-blue-500/30 text-[10px] uppercase tracking-widest rounded" title="Tiene múltiples precios o tamaños">
                                                        <?= count($prod['variantes']) ?> Vars
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($prod['caracteristicas'])): ?>
                                                    <span class="px-2 py-1 bg-green-900/30 text-green-400 border border-green-500/30 text-[10px] uppercase tracking-widest rounded" title="Tiene listas de características">
                                                        <?= count($prod['caracteristicas']) ?> Caracts
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button @click="openEditModal({ id: <?= $prod['id'] ?>, categoria_id: <?= $prod['categoria_id'] ?>, nombre: `<?= addslashes(htmlspecialchars($prod['nombre'])) ?>`, descripcion: `<?= addslashes(htmlspecialchars($prod['descripcion'] ?? '')) ?>`, precio_regular: `<?= $prod['precio_regular'] ?>` })" class="text-gray-400 hover:text-gold transition-colors p-1" title="Editar Producto">
                                                <i class="ph ph-pencil-simple text-lg"></i>
                                            </button>
                                            <button class="text-gray-400 hover:text-red-500 transition-colors p-1 ml-2" title="Eliminar Producto">
                                                <i class="ph ph-trash text-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal Editar Producto -->
    <div x-show="modalEditOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;" x-transition.opacity>
        <div @click.away="modalEditOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] p-8 w-full max-w-lg relative shadow-2xl animate__animated animate__zoomIn animate__faster">
            <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-gold/40 to-transparent"></div>
            
            <button type="button" @click="modalEditOpen = false" class="absolute top-4 right-4 text-[#555] hover:text-gold transition-colors focus:outline-none">
                <i class="ph ph-x text-xl"></i>
            </button>
            
            <div class="mb-6">
                <h2 class="text-[1.35rem] font-heading font-bold text-white tracking-widest uppercase mb-1">Editar Producto</h2>
                <p class="text-[#666] text-xs font-light">Actualiza la información básica del producto.</p>
            </div>
            
            <form action="/admin/productos/update" method="POST" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="id" x-model="form.id">
                
                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Nombre del Producto</label>
                    <input type="text" name="nombre" x-model="form.nombre" maxlength="150" class="w-full bg-transparent border-b border-[#222] py-2 text-sm text-white focus:border-gold outline-none transition-colors" required>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Categoría</label>
                    <select name="categoria_id" x-model="form.categoria_id" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-sm" required>
                        <?php foreach ($categorias as $catOption): ?>
                            <option value="<?= $catOption['id'] ?>"><?= htmlspecialchars($catOption['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Descripción (Opcional)</label>
                    <textarea name="descripcion" x-model="form.descripcion" rows="3" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-sm resize-none"></textarea>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Precio Base (Opcional si tiene variantes)</label>
                    <div class="relative">
                        <span class="absolute left-0 top-2 text-gray-500 text-sm">S/</span>
                        <input type="number" step="0.01" name="precio_regular" x-model="form.precio_regular" class="w-full bg-transparent border-b border-[#222] py-2 pl-6 text-sm text-white focus:border-gold outline-none transition-colors">
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#D4AF37] text-black py-[0.85rem] mt-4 text-[0.8rem] font-semibold uppercase tracking-widest hover:bg-[#f1c40f] transition-colors focus:outline-none">
                    Guardar Cambios
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Editar Categoría -->
    <div x-show="modalEditCatOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;" x-transition.opacity>
        <div @click.away="modalEditCatOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] p-8 w-full max-w-lg relative shadow-2xl animate__animated animate__zoomIn animate__faster">
            <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-gold/40 to-transparent"></div>
            
            <button type="button" @click="modalEditCatOpen = false" class="absolute top-4 right-4 text-[#555] hover:text-gold transition-colors focus:outline-none">
                <i class="ph ph-x text-xl"></i>
            </button>
            
            <div class="mb-6">
                <h2 class="text-[1.35rem] font-heading font-bold text-white tracking-widest uppercase mb-1">Editar Categoría</h2>
                <p class="text-[#666] text-xs font-light">Actualiza el nombre y descripción de la categoría.</p>
            </div>
            
            <form action="/admin/categorias/update" method="POST" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="id" x-model="formCat.id">
                
                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Nombre de la Categoría</label>
                    <input type="text" name="nombre" x-model="formCat.nombre" maxlength="100" class="w-full bg-transparent border-b border-[#222] py-2 text-sm text-white focus:border-gold outline-none transition-colors" required>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Descripción (Opcional)</label>
                    <textarea name="descripcion" x-model="formCat.descripcion" rows="3" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-sm resize-none"></textarea>
                </div>

                <button type="submit" class="w-full bg-[#D4AF37] text-black py-[0.85rem] mt-4 text-[0.8rem] font-semibold uppercase tracking-widest hover:bg-[#f1c40f] transition-colors focus:outline-none">
                    Guardar Cambios
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function adminProductos() {
        return {
            modalEditOpen: false,
            modalEditCatOpen: false,
            form: {
                id: '',
                categoria_id: '',
                nombre: '',
                descripcion: '',
                precio_regular: ''
            },
            formCat: {
                id: '',
                nombre: '',
                descripcion: ''
            },
            openEditModal(producto) {
                this.form.id = producto.id;
                this.form.categoria_id = producto.categoria_id;
                this.form.nombre = producto.nombre;
                this.form.descripcion = producto.descripcion === 'null' ? '' : producto.descripcion;
                this.form.precio_regular = producto.precio_regular === 'null' ? '' : producto.precio_regular;
                this.modalEditOpen = true;
            },
            openEditCatModal(categoria) {
                this.formCat.id = categoria.id;
                this.formCat.nombre = categoria.nombre;
                this.formCat.descripcion = categoria.descripcion === 'null' ? '' : categoria.descripcion;
                this.modalEditCatOpen = true;
            }
        }
    }
</script>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
