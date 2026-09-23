<?php require __DIR__ . '/../../layouts/admin_header.php'; ?>

<div x-data="adminPedidos()">
    <div class="mb-8 flex justify-between items-end flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-heading font-bold text-white tracking-wide uppercase mb-2">Pedidos y Ventas</h1>
            <p class="text-gray-400 text-sm font-light">Centro de control para gestionar el estado de los pedidos de la tienda.</p>
        </div>
    </div>

    <!-- Tabla de Pedidos -->
    <div class="bg-[#111] border border-[#222] rounded-xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#222]">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">Nro Pedido / Fecha</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">Cliente</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">Total / Método</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">Estado</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a] text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#222]">
                    <?php if (empty($pedidos)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 text-sm">No hay pedidos registrados en el sistema.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr class="hover:bg-[#151515] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-white uppercase tracking-widest">#<?= str_pad($pedido['id'], 6, '0', STR_PAD_LEFT) ?></span>
                                        <span class="text-xs text-gray-500 font-medium"><?= date('d M, Y - h:i A', strtotime($pedido['creado_en'])) ?></span>
                                        <?php if($pedido['tipo_pedido'] == 'PREVENTA'): ?>
                                            <span class="mt-1 inline-block text-[10px] bg-purple-900/30 text-purple-400 border border-purple-500/30 px-2 rounded font-bold tracking-widest uppercase self-start">PREVENTA</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#1a1a1a] border border-[#333] flex items-center justify-center text-gold font-bold text-xs">
                                            <?= strtoupper(substr($pedido['cliente_nombres'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-200"><?= htmlspecialchars($pedido['cliente_nombres'] . ' ' . $pedido['cliente_apellidos']) ?></p>
                                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $pedido['cliente_whatsapp']) ?>" target="_blank" class="text-[10px] text-green-400 font-light hover:underline flex items-center gap-1">
                                                <i class="ph ph-whatsapp-logo"></i> <?= htmlspecialchars($pedido['cliente_whatsapp']) ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gold">S/ <?= number_format($pedido['total'], 2) ?></p>
                                    <p class="text-[10px] text-gray-400 tracking-wider uppercase"><?= htmlspecialchars($pedido['metodo_pago_nombre']) ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <?php 
                                        $bgClass = 'bg-gray-800 text-gray-300 border-gray-700';
                                        $dotClass = 'bg-gray-400';
                                        
                                        switch($pedido['estado_pedido']) {
                                            case 'PENDIENTE': $bgClass = 'bg-orange-900/30 text-orange-400 border-orange-500/30'; $dotClass = 'bg-orange-400 animate-pulse'; break;
                                            case 'PAGADO': $bgClass = 'bg-blue-900/30 text-blue-400 border-blue-500/30'; $dotClass = 'bg-blue-400'; break;
                                            case 'HORNEANDO': $bgClass = 'bg-yellow-900/30 text-yellow-400 border-yellow-500/30'; $dotClass = 'bg-yellow-400 animate-pulse'; break;
                                            case 'EN_CAMINO': $bgClass = 'bg-cyan-900/30 text-cyan-400 border-cyan-500/30'; $dotClass = 'bg-cyan-400 animate-pulse'; break;
                                            case 'ENTREGADO': $bgClass = 'bg-green-900/30 text-green-400 border-green-500/30'; $dotClass = 'bg-green-400'; break;
                                            case 'CANCELADO': $bgClass = 'bg-red-900/30 text-red-400 border-red-500/30'; $dotClass = 'bg-red-400'; break;
                                        }
                                    ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 <?= $bgClass ?> border text-[10px] font-semibold uppercase tracking-wider rounded-full whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full <?= $dotClass ?>"></span> <?= $pedido['estado_pedido'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button @click='openStatusModal(<?= $pedido['id'] ?>, "<?= $pedido['estado_pedido'] ?>")' class="bg-[#1a1a1a] border border-[#333] text-gray-300 hover:text-gold hover:border-gold transition-colors px-3 py-1.5 rounded text-xs font-semibold tracking-wider flex items-center gap-1">
                                            <i class="ph ph-arrows-clockwise"></i> Estado
                                        </button>
                                        <button @click='verDetalle(<?= $pedido['id'] ?>)' class="bg-gold text-black hover:bg-white transition-colors px-3 py-1.5 rounded text-xs font-bold tracking-wider flex items-center gap-1">
                                            <i class="ph ph-eye"></i> Ver
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detalle del Pedido -->
    <div x-show="modalDetalleOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;" x-transition.opacity>
        <div @click.away="modalDetalleOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] w-full max-w-2xl relative shadow-2xl rounded-xl max-h-[90vh] flex flex-col">
            <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-gold/40 to-transparent"></div>
            
            <!-- Header Modal -->
            <div class="p-6 border-b border-[#222] flex justify-between items-center bg-[#111] rounded-t-xl shrink-0">
                <div>
                    <h2 class="text-xl font-heading font-bold text-white tracking-widest uppercase mb-1">
                        Pedido <span class="text-gold">#<span x-text="String(pedidoSeleccionadoId).padStart(6, '0')"></span></span>
                    </h2>
                    <p class="text-[#666] text-xs font-light">Detalle de productos y cantidades.</p>
                </div>
                <button type="button" @click="modalDetalleOpen = false" class="text-[#555] hover:text-gold transition-colors focus:outline-none bg-[#1a1a1a] p-2 rounded-lg">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
            
            <!-- Body Modal (Scrollable) -->
            <div class="p-6 overflow-y-auto">
                <div x-show="loadingDetalle" class="flex justify-center py-10">
                    <i class="ph ph-spinner-gap text-3xl text-gold animate-spin"></i>
                </div>
                
                <div x-show="!loadingDetalle">
                    <table class="w-full text-left border-collapse mb-4">
                        <thead>
                            <tr class="border-b border-[#222]">
                                <th class="py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-widest">Producto</th>
                                <th class="py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-widest text-center">Cant.</th>
                                <th class="py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-widest text-right">P. Unitario</th>
                                <th class="py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-widest text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#222]">
                            <template x-for="item in detalleActual" :key="item.id">
                                <tr>
                                    <td class="py-3 text-sm text-gray-300 font-medium" x-text="item.producto_nombre"></td>
                                    <td class="py-3 text-sm text-gray-400 text-center" x-text="item.cantidad"></td>
                                    <td class="py-3 text-sm text-gray-400 text-right">S/ <span x-text="Number(item.precio_unitario).toFixed(2)"></span></td>
                                    <td class="py-3 text-sm text-gold font-bold text-right">S/ <span x-text="Number(item.subtotal).toFixed(2)"></span></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="p-6 border-t border-[#222] bg-[#111] rounded-b-xl shrink-0 flex justify-end">
                <button type="button" @click="modalDetalleOpen = false" class="bg-[#222] text-white px-6 py-2.5 rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-[#333] transition-colors">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Actualizar Estado -->
    <div x-show="modalStatusOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;" x-transition.opacity>
        <div @click.away="modalStatusOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] p-8 w-full max-w-sm relative shadow-2xl rounded-xl">
            <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-gold/40 to-transparent"></div>
            
            <button type="button" @click="modalStatusOpen = false" class="absolute top-4 right-4 text-[#555] hover:text-gold transition-colors focus:outline-none">
                <i class="ph ph-x text-xl"></i>
            </button>
            
            <div class="mb-6">
                <h2 class="text-[1.35rem] font-heading font-bold text-white tracking-widest uppercase mb-1">Actualizar Estado</h2>
                <p class="text-[#666] text-xs font-light">Modifica la fase en la que se encuentra el pedido.</p>
            </div>
            
            <form action="/admin/pedidos/update-status" method="POST" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="id" x-model="pedidoSeleccionadoId">
                
                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Fase del Pedido</label>
                    <select name="estado" x-model="estadoSeleccionado" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg font-semibold tracking-wider">
                        <option value="PENDIENTE" class="text-orange-400">PENDIENTE (Aún no paga)</option>
                        <option value="PAGADO" class="text-blue-400">PAGADO (Confirmado)</option>
                        <option value="HORNEANDO" class="text-yellow-400">HORNEANDO (En preparación)</option>
                        <option value="EN_CAMINO" class="text-cyan-400">EN CAMINO (Repartidor)</option>
                        <option value="ENTREGADO" class="text-green-400">ENTREGADO (Finalizado)</option>
                        <option value="CANCELADO" class="text-red-400">CANCELADO (Anulado)</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-gold text-black py-3 text-xs font-bold uppercase tracking-widest hover:bg-white transition-colors rounded-lg shadow-lg mt-4">
                    Guardar Estado
                </button>
            </form>
        </div>
    </div>

</div>

<script>
    function adminPedidos() {
        return {
            modalDetalleOpen: false,
            modalStatusOpen: false,
            pedidoSeleccionadoId: '',
            estadoSeleccionado: '',
            detalleActual: [],
            loadingDetalle: false,
            
            openStatusModal(id, estadoActual) {
                this.pedidoSeleccionadoId = id;
                this.estadoSeleccionado = estadoActual;
                this.modalStatusOpen = true;
            },
            
            verDetalle(id) {
                this.pedidoSeleccionadoId = id;
                this.modalDetalleOpen = true;
                this.loadingDetalle = true;
                this.detalleActual = [];
                
                fetch(`/admin/pedidos/detalle?id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        this.detalleActual = data;
                        this.loadingDetalle = false;
                    })
                    .catch(error => {
                        console.error('Error cargando detalle:', error);
                        this.loadingDetalle = false;
                    });
            }
        }
    }
</script>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
