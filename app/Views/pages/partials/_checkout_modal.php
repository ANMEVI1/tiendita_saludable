<!-- Modal de Checkout Premium -->
<div x-data="checkoutModal()" 
     @open-checkout.window="initCheckout()" 
     x-show="isOpen" 
     style="display: none;" 
     class="fixed inset-0 z-[300] flex items-center justify-center p-4 bg-black/90 backdrop-blur-md"
     x-transition.opacity>

    <div @click.away="isOpen = false" class="bg-[#0a0a0a] border border-[#333] w-full max-w-3xl relative shadow-[0_0_50px_rgba(212,175,55,0.15)] rounded-2xl flex flex-col max-h-[90vh] overflow-hidden animate__animated animate__zoomIn animate__faster">
        
        <!-- Header -->
        <div class="p-6 border-b border-[#222] bg-[#111] flex justify-between items-center shrink-0">
            <h2 class="text-xl font-heading font-bold text-white tracking-widest uppercase flex items-center gap-2">
                <i class="ph-bold ph-lock-key text-gold"></i> Pago Seguro
            </h2>
            <button type="button" @click="isOpen = false" class="text-gray-500 hover:text-gold transition-colors p-2 bg-[#1a1a1a] rounded-lg border border-[#333]">
                <i class="ph-bold ph-x text-xl"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 md:p-8 bg-[#050505]">
            
            <div x-show="loadingMetodos" class="flex justify-center py-12">
                <i class="ph-bold ph-spinner-gap text-4xl text-gold animate-spin"></i>
            </div>

            <!-- Paso 1: Selección de Método -->
            <div x-show="!loadingMetodos && step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <h3 class="text-white font-bold text-lg mb-6 tracking-widest uppercase">1. Selecciona tu Método de Pago</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <template x-for="metodo in metodos" :key="metodo.id">
                        <div @click="seleccionarMetodo(metodo)" 
                             class="bg-[#111] border p-6 rounded-xl cursor-pointer hover:border-gold/50 hover:bg-[#151515] transition-all group flex flex-col items-center justify-center min-h-[120px]"
                             :class="selectedMetodo && selectedMetodo.id === metodo.id ? 'border-gold shadow-[0_0_15px_rgba(212,175,55,0.2)]' : 'border-[#222]'">
                            
                            <i class="ph-fill ph-wallet text-3xl mb-2 text-gray-400 group-hover:text-gold transition-colors" x-show="!metodo.imagen_url"></i>
                            <img :src="'/' + metodo.imagen_url" x-show="metodo.imagen_url" class="h-10 mb-3 object-contain opacity-70 group-hover:opacity-100 transition-opacity">
                            
                            <h4 class="text-white font-bold tracking-widest text-sm" x-text="metodo.nombre"></h4>
                        </div>
                    </template>
                </div>
                
                <div x-show="metodos.length === 0" class="text-center py-8 text-gray-500">
                    No hay métodos de pago disponibles en este momento.
                </div>
            </div>

            <!-- Paso 2: Escaneo y Confirmación -->
            <div x-show="!loadingMetodos && step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <button @click="step = 1" class="text-gold hover:text-white text-xs font-bold uppercase tracking-widest flex items-center gap-1 mb-6 transition-colors">
                    <i class="ph-bold ph-arrow-left text-base"></i> Volver a métodos
                </button>
                
                <div class="bg-[#111] border border-[#222] rounded-2xl p-6 md:p-8 flex flex-col items-center text-center">
                    <h3 class="text-white font-bold text-2xl mb-2 tracking-widest uppercase" x-text="selectedMetodo?.nombre"></h3>
                    <p class="text-gray-400 text-sm mb-8" x-text="selectedMetodo?.descripcion"></p>

                    <!-- Imagen QR muy grande (a petición del cliente) -->
                    <div x-show="selectedMetodo?.imagen_url" class="mb-8 w-full max-w-xs mx-auto">
                        <img :src="'/' + selectedMetodo?.imagen_url" class="w-full h-auto rounded-lg shadow-2xl">
                    </div>

                    <!-- Caja de Número de Cuenta / Teléfono -->
                    <div x-show="selectedMetodo?.instrucciones" class="w-full max-w-md mx-auto mb-8">
                        <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold block mb-2">Número / Instrucciones</label>
                        <div class="flex items-center bg-[#050505] border border-[#333] rounded-lg p-2">
                            <input type="text" readonly :value="selectedMetodo?.instrucciones" class="w-full bg-transparent text-white font-bold text-lg md:text-xl text-center outline-none selection:bg-gold selection:text-black">
                            <button @click="copyToClipboard(selectedMetodo?.instrucciones)" class="shrink-0 bg-[#222] text-gold hover:text-white hover:bg-gold px-4 py-3 rounded text-xs font-bold uppercase tracking-widest transition-all flex items-center gap-2">
                                <i :class="copied ? 'ph-bold ph-check text-green-400 text-lg' : 'ph-bold ph-copy text-lg'"></i>
                                <span x-text="copied ? 'Copiado' : 'Copiar'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Error Msg -->
                    <div x-show="errorMsg" class="bg-red-900/30 border border-red-500/50 text-red-400 p-3 rounded text-sm font-semibold text-center mb-6 w-full" x-text="errorMsg"></div>

                    <!-- Botón Final de Pagar -->
                    <button @click="procesarPago" :disabled="procesando" class="w-full max-w-md mx-auto bg-gold text-black px-8 py-4 rounded-xl font-bold uppercase tracking-widest hover:bg-white transition-all shadow-[0_0_20px_rgba(212,175,55,0.4)] disabled:opacity-50 flex items-center justify-center gap-2 text-lg">
                        <i x-show="procesando" class="ph-bold ph-spinner animate-spin text-2xl"></i>
                        <span x-text="procesando ? 'Procesando...' : 'Ya Pagué'"></span>
                    </button>
                    <p class="text-xs text-gray-500 mt-4 max-w-md mx-auto">Al confirmar, enviaremos un mensaje a WhatsApp automáticamente para que adjuntes tu comprobante de pago.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('checkoutModal', () => ({
        isOpen: false,
        step: 1,
        loadingMetodos: false,
        metodos: [],
        selectedMetodo: null,
        copied: false,
        procesando: false,
        errorMsg: '',

        initCheckout() {
            this.isOpen = true;
            this.step = 1;
            this.selectedMetodo = null;
            this.copied = false;
            this.errorMsg = '';
            
            // Cerrar el carrito si estaba abierto
            this.isCartOpen = false;
            
            this.loadMetodos();
        },

        async loadMetodos() {
            this.loadingMetodos = true;
            try {
                const res = await fetch('/api/checkout/metodos-pago');
                if (res.ok) {
                    this.metodos = await res.json();
                }
            } catch (err) {
                console.error("Error al cargar métodos:", err);
            } finally {
                this.loadingMetodos = false;
            }
        },

        seleccionarMetodo(metodo) {
            this.selectedMetodo = metodo;
            this.step = 2;
        },

        async copyToClipboard(text) {
            if (!text) return;
            try {
                await navigator.clipboard.writeText(text);
                this.copied = true;
                setTimeout(() => { this.copied = false; }, 2000);
            } catch (err) {
                console.error('Error al copiar', err);
            }
        },

        async procesarPago() {
            this.procesando = true;
            this.errorMsg = '';

            // Obtener el carrito del localStorage, ya que está manejado por otro componente
            const savedCart = localStorage.getItem('tiendita_cart');
            const cartItems = savedCart ? JSON.parse(savedCart) : [];

            if (cartItems.length === 0) {
                this.errorMsg = 'Tu carrito está vacío.';
                this.procesando = false;
                return;
            }

            try {
                // 1. Guardar en BD
                const res = await fetch('/api/checkout/procesar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        cart: cartItems,
                        metodo_pago_id: this.selectedMetodo.id
                    })
                });

                const result = await res.json();

                if (!res.ok || !result.success) {
                    this.errorMsg = result.error || 'Ocurrió un error al procesar tu pedido.';
                    this.procesando = false;
                    return;
                }

                // 2. Preparar el mensaje de WhatsApp
                const pedidoId = result.pedido_id;
                let text = `Hola *La Tiendita Saludable*, acabo de realizar el pedido *#${pedidoId}* pagando con *${this.selectedMetodo.nombre}*.\n\n`;
                text += `*Detalle:*\n`;
                
                let total = 0;
                cartItems.forEach(item => {
                    let v = item.variantName ? ` (${item.variantName})` : '';
                    let sub = parseFloat(item.price) * parseInt(item.quantity);
                    total += sub;
                    text += `- ${item.quantity}x ${item.name}${v} = S/ ${sub.toFixed(2)}\n`;
                });
                text += `\n*Total Pagado: S/ ${total.toFixed(2)}*\n\n`;
                text += `Adjunto mi imagen de pago a continuación:`;

                const phone = "<?= htmlspecialchars($configWeb['whatsapp_numero'] ?? '51984247684') ?>";
                const url = `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
                
                // 3. Limpiar Carrito y Cerrar Modales
                localStorage.removeItem('tiendita_cart');
                window.location.reload(); // Recargar para limpiar todo el UI y carrito a cero, 
                // Pero antes de recargar, abrimos WhatsApp
                window.open(url, '_blank');

            } catch (error) {
                this.errorMsg = 'Error de conexión. Por favor intente de nuevo.';
            } finally {
                this.procesando = false;
            }
        }
    }));
});
</script>
