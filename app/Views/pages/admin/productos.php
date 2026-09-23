<?php require __DIR__ . '/../../layouts/admin_header.php'; ?>

<div x-data="adminProductos()">
    <div class="mb-8 flex justify-between items-end flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-heading font-bold text-white tracking-wide uppercase mb-2">Catálogo</h1>
            <p class="text-gray-400 text-sm font-light">Gestiona tus productos, precios, imágenes y categorías.</p>
        </div>
        <!-- Botón para nuevo producto -->
        <button @click="openNewModal()" class="bg-gold text-black font-bold text-sm tracking-widest uppercase px-6 py-3 rounded hover:bg-white hover:scale-105 transition-all shadow-[0_0_15px_rgba(212,175,55,0.3)] flex items-center gap-2">
            <i class="ph-bold ph-plus"></i> Nuevo Producto
        </button>
    </div>

    <!-- Mensajes de Alerta -->
    <?php if (isset($_SESSION['success_msg'])): ?>
        <div class="mb-6 bg-green-900/40 border border-green-500/50 text-green-300 px-6 py-3 rounded-lg flex justify-between items-center text-sm">
            <span><i class="ph-bold ph-check-circle text-lg mr-2"></i><?= htmlspecialchars($_SESSION['success_msg']) ?></span>
            <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-white">&times;</button>
        </div>
        <?php unset($_SESSION['success_msg']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_msg'])): ?>
        <div class="mb-6 bg-red-900/40 border border-red-500/50 text-red-300 px-6 py-3 rounded-lg flex justify-between items-center text-sm">
            <span><i class="ph-bold ph-warning-circle text-lg mr-2"></i><?= htmlspecialchars($_SESSION['error_msg']) ?></span>
            <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-white">&times;</button>
        </div>
        <?php unset($_SESSION['error_msg']); ?>
    <?php endif; ?>

    <div class="space-y-12">
        <?php foreach ($categorias as $cat): ?>
            <div class="bg-[#111] border border-[#222] rounded-xl overflow-hidden shadow-xl">
                <!-- Header Categoría -->
                <div class="bg-[#1a1a1a] border-b border-[#333] px-6 py-4 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-heading font-bold text-gold uppercase tracking-wider"><?= htmlspecialchars($cat['nombre']) ?></h3>
                        <p class="text-xs text-gray-400 font-light mt-1"><?= htmlspecialchars($cat['descripcion'] ?? '') ?> • Plantilla: <span class="text-white"><?= htmlspecialchars($cat['plantilla_html']) ?></span></p>
                    </div>
                    <button @click='openEditCatModal(<?= htmlspecialchars(json_encode($cat, JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, "UTF-8") ?>)' class="text-gray-400 hover:text-white transition-colors" title="Editar Categoría">
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
                                                <?php if (!empty($prod['imagen_url'])): ?>
                                                    <div class="w-12 h-12 rounded-lg border border-[#333] overflow-hidden flex-shrink-0 bg-[#0a0a0a]">
                                                        <img src="<?= htmlspecialchars(strpos($prod['imagen_url'], 'http') === 0 ? $prod['imagen_url'] : '/' . ltrim($prod['imagen_url'], '/')) ?>" alt="Img" class="w-full h-full object-cover">
                                                    </div>
                                                <?php elseif (!empty($prod['icono'])): ?>
                                                    <div class="w-12 h-12 rounded-lg border border-[#333] bg-[#0a0a0a] flex items-center justify-center flex-shrink-0">
                                                        <i class="<?= htmlspecialchars($prod['icono']) ?> text-gold text-xl"></i>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="w-12 h-12 rounded-lg border border-[#333] bg-[#0a0a0a] flex items-center justify-center flex-shrink-0 text-gray-600">
                                                        <i class="ph ph-image text-xl"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <p class="text-sm font-semibold text-white"><?= htmlspecialchars($prod['nombre']) ?></p>
                                                    <?php if (!empty($prod['es_preventa'])): ?>
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
                                            <button @click='openEditModal(<?= htmlspecialchars(json_encode($prod, JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, "UTF-8") ?>)' class="text-gray-400 hover:text-gold transition-colors p-1" title="Editar Producto">
                                                <i class="ph ph-pencil-simple text-lg"></i>
                                            </button>
                                            <button @click='openDeleteModal(<?= htmlspecialchars(json_encode($prod, JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, "UTF-8") ?>)' class="text-gray-400 hover:text-red-500 transition-colors p-1 ml-2" title="Eliminar Producto">
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

    <!-- Modal Nuevo Producto -->
    <div x-show="modalNewOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;" x-transition.opacity>
        <div @click.away="modalNewOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] p-8 w-full max-w-lg relative shadow-2xl rounded-xl animate__animated animate__zoomIn animate__faster max-h-[90vh] overflow-y-auto">
            <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-gold/40 to-transparent"></div>
            
            <button type="button" @click="modalNewOpen = false" class="absolute top-4 right-4 text-[#555] hover:text-gold transition-colors focus:outline-none">
                <i class="ph ph-x text-xl"></i>
            </button>
            
            <div class="mb-6">
                <h2 class="text-[1.35rem] font-heading font-bold text-white tracking-widest uppercase mb-1">Nuevo Producto</h2>
                <p class="text-[#666] text-xs font-light">Agrega un nuevo producto al catálogo.</p>
            </div>
            
            <form action="/admin/productos/store" method="POST" enctype="multipart/form-data" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                
                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Nombre del Producto *</label>
                    <input type="text" name="nombre" maxlength="150" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" placeholder="Ej: Molde Artesanal Ajonjolí" required>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Categoría *</label>
                    <select name="categoria_id" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-lg" required>
                        <option value="">-- Seleccionar Categoría --</option>
                        <?php foreach ($categorias as $catOption): ?>
                            <option value="<?= $catOption['id'] ?>"><?= htmlspecialchars($catOption['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Descripción (Opcional)</label>
                    <textarea name="descripcion" rows="3" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-lg resize-none" placeholder="Ingredientes o descripción del producto..."></textarea>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Precio Base (S/)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500 text-sm">S/</span>
                        <input type="number" step="0.01" name="precio_regular" class="w-full bg-[#111] border border-[#222] py-3 pl-9 pr-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" placeholder="10.50">
                    </div>
                </div>

                <!-- Gestión de Imagen Profesional -->
                <div class="space-y-3 p-4 bg-[#111] border border-[#222] rounded-lg">
                    <label class="text-[10px] text-gold uppercase tracking-widest font-semibold block">Imagen del Producto</label>
                    
                    <div>
                        <span class="text-xs text-gray-400 block mb-1">Opción A: Subir imagen desde el computador (JPG, PNG, WEBP max 5MB)</span>
                        <input type="file" name="imagen_archivo" accept="image/*" class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gold/20 file:text-gold hover:file:bg-gold/30 cursor-pointer">
                    </div>

                    <div class="text-center text-xs text-gray-600 font-bold uppercase">— O —</div>

                    <div>
                        <span class="text-xs text-gray-400 block mb-1">Opción B: Pegar enlace/URL de imagen externa</span>
                        <input type="url" name="imagen_url_input" class="w-full bg-[#0a0a0a] border border-[#222] p-2.5 text-xs text-white focus:border-gold outline-none transition-colors rounded-md" placeholder="https://ejemplo.com/imagen.jpg">
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="es_preventa_new" name="es_preventa" value="1" class="w-4 h-4 rounded border-[#333] bg-[#111] text-gold focus:ring-gold accent-gold">
                    <label for="es_preventa_new" class="text-xs text-gray-300 font-medium cursor-pointer">Marcar como Producto de Preventa</label>
                </div>

                <button type="submit" class="w-full bg-gold text-black py-3 text-xs font-bold uppercase tracking-widest hover:bg-white transition-colors rounded-lg shadow-lg">
                    Guardar Producto
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Editar Producto -->
    <div x-show="modalEditOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;" x-transition.opacity>
        <div @click.away="modalEditOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] p-8 w-full max-w-lg relative shadow-2xl rounded-xl animate__animated animate__zoomIn animate__faster max-h-[90vh] overflow-y-auto">
            <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-gold/40 to-transparent"></div>
            
            <button type="button" @click="modalEditOpen = false" class="absolute top-4 right-4 text-[#555] hover:text-gold transition-colors focus:outline-none">
                <i class="ph ph-x text-xl"></i>
            </button>
            
            <div class="mb-6">
                <h2 class="text-[1.35rem] font-heading font-bold text-white tracking-widest uppercase mb-1">Editar Producto</h2>
                <p class="text-[#666] text-xs font-light">Actualiza la información, precio e imagen del producto.</p>
            </div>
            
            <form action="/admin/productos/update" method="POST" enctype="multipart/form-data" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="id" x-model="form.id">
                
                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Nombre del Producto *</label>
                    <input type="text" name="nombre" x-model="form.nombre" maxlength="150" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" required>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Categoría *</label>
                    <select name="categoria_id" x-model="form.categoria_id" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-lg" required>
                        <?php foreach ($categorias as $catOption): ?>
                            <option value="<?= $catOption['id'] ?>"><?= htmlspecialchars($catOption['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Descripción (Opcional)</label>
                    <textarea name="descripcion" x-model="form.descripcion" rows="3" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-lg resize-none"></textarea>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Precio Base (S/)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500 text-sm">S/</span>
                        <input type="number" step="0.01" name="precio_regular" x-model="form.precio_regular" class="w-full bg-[#111] border border-[#222] py-3 pl-9 pr-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg">
                    </div>
                </div>

                <!-- Gestión de Imagen Profesional -->
                <div class="space-y-3 p-4 bg-[#111] border border-[#222] rounded-lg">
                    <label class="text-[10px] text-gold uppercase tracking-widest font-semibold block">Imagen del Producto</label>
                    
                    <template x-if="form.imagen_url">
                        <div class="flex items-center gap-3 bg-[#0a0a0a] p-2 rounded border border-[#222] mb-2">
                            <img :src="form.imagen_url.startsWith('http') ? form.imagen_url : '/' + form.imagen_url.replace(/^\/+/, '')" class="w-12 h-12 object-cover rounded border border-[#333]">
                            <div class="overflow-hidden">
                                <span class="text-xs text-gray-300 block truncate" x-text="form.imagen_url"></span>
                                <span class="text-[10px] text-gray-500">Imagen actual</span>
                            </div>
                        </div>
                    </template>

                    <div>
                        <span class="text-xs text-gray-400 block mb-1">Opción A: Subir nueva imagen desde el equipo (JPG, PNG, WEBP)</span>
                        <input type="file" name="imagen_archivo" accept="image/*" class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gold/20 file:text-gold hover:file:bg-gold/30 cursor-pointer">
                    </div>

                    <div class="text-center text-xs text-gray-600 font-bold uppercase">— O —</div>

                    <div>
                        <span class="text-xs text-gray-400 block mb-1">Opción B: Actualizar URL externa de la imagen</span>
                        <input type="url" name="imagen_url_input" x-model="form.imagen_url" class="w-full bg-[#0a0a0a] border border-[#222] p-2.5 text-xs text-white focus:border-gold outline-none transition-colors rounded-md" placeholder="https://ejemplo.com/imagen.jpg">
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="es_preventa_edit" name="es_preventa" value="1" x-model="form.es_preventa" class="w-4 h-4 rounded border-[#333] bg-[#111] text-gold focus:ring-gold accent-gold">
                    <label for="es_preventa_edit" class="text-xs text-gray-300 font-medium cursor-pointer">Marcar como Producto de Preventa</label>
                </div>

                <button type="submit" class="w-full bg-gold text-black py-3 text-xs font-bold uppercase tracking-widest hover:bg-white transition-colors rounded-lg shadow-lg">
                    Guardar Cambios
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Eliminar Producto -->
    <div x-show="modalDeleteOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;" x-transition.opacity>
        <div @click.away="modalDeleteOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] p-8 w-full max-w-md relative shadow-2xl rounded-xl text-center">
            <div class="w-16 h-16 bg-red-900/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-500/30">
                <i class="ph ph-trash text-3xl"></i>
            </div>
            
            <h3 class="text-lg font-heading font-bold text-white uppercase tracking-wider mb-2">Confirmar Eliminación</h3>
            <p class="text-gray-400 text-xs mb-6">¿Estás seguro de que deseas eliminar el producto <strong class="text-white" x-text="formDelete.nombre"></strong>?</p>
            
            <form action="/admin/productos/delete" method="POST" class="flex gap-3">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="id" x-model="formDelete.id">
                
                <button type="button" @click="modalDeleteOpen = false" class="w-1/2 bg-[#222] text-gray-300 py-2.5 text-xs font-bold uppercase tracking-widest hover:bg-[#333] transition-colors rounded-lg">
                    Cancelar
                </button>
                <button type="submit" class="w-1/2 bg-red-600 text-white py-2.5 text-xs font-bold uppercase tracking-widest hover:bg-red-700 transition-colors rounded-lg shadow-lg">
                    Eliminar
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Editar Categoría -->
    <div x-show="modalEditCatOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;" x-transition.opacity>
        <div @click.away="modalEditCatOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] p-8 w-full max-w-lg relative shadow-2xl rounded-xl">
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
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Nombre de la Categoría *</label>
                    <input type="text" name="nombre" x-model="formCat.nombre" maxlength="100" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" required>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Descripción (Opcional)</label>
                    <textarea name="descripcion" x-model="formCat.descripcion" rows="3" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-lg resize-none"></textarea>
                </div>

                <button type="submit" class="w-full bg-gold text-black py-3 text-xs font-bold uppercase tracking-widest hover:bg-white transition-colors rounded-lg shadow-lg">
                    Guardar Cambios
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function adminProductos() {
        return {
            modalNewOpen: false,
            modalEditOpen: false,
            modalDeleteOpen: false,
            modalEditCatOpen: false,
            form: {
                id: '',
                categoria_id: '',
                nombre: '',
                descripcion: '',
                precio_regular: '',
                imagen_url: '',
                es_preventa: false
            },
            formDelete: {
                id: '',
                nombre: ''
            },
            formCat: {
                id: '',
                nombre: '',
                descripcion: ''
            },
            openNewModal() {
                this.modalNewOpen = true;
            },
            openEditModal(producto) {
                this.form.id = producto.id;
                this.form.categoria_id = producto.categoria_id;
                this.form.nombre = producto.nombre || '';
                this.form.descripcion = (producto.descripcion === null || producto.descripcion === 'null') ? '' : producto.descripcion;
                this.form.precio_regular = (producto.precio_regular === null || producto.precio_regular === 'null') ? '' : producto.precio_regular;
                this.form.imagen_url = producto.imagen_url || '';
                this.form.es_preventa = Boolean(Number(producto.es_preventa));
                this.modalEditOpen = true;
            },
            openDeleteModal(producto) {
                this.formDelete.id = producto.id;
                this.formDelete.nombre = producto.nombre;
                this.modalDeleteOpen = true;
            },
            openEditCatModal(categoria) {
                this.formCat.id = categoria.id;
                this.formCat.nombre = categoria.nombre;
                this.formCat.descripcion = (categoria.descripcion === null || categoria.descripcion === 'null') ? '' : categoria.descripcion;
                this.modalEditCatOpen = true;
            }
        }
    }
</script>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
