<!-- Modal Detalle de Producto -->
<div x-show="isModalOpen" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <!-- Backdrop -->
    <div x-show="isModalOpen" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-black bg-opacity-80 backdrop-blur-sm transition-opacity" 
         @click="isModalOpen = false"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            
            <div x-show="isModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative transform overflow-hidden rounded-xl bg-[#0a0a0a] border border-[#333] text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl flex flex-col md:flex-row">
                
                <button type="button" @click="isModalOpen = false" class="absolute top-4 right-4 z-10 text-gray-400 hover:text-gold transition-colors bg-black/50 rounded-full p-1">
                    <i class="ph ph-x text-2xl"></i>
                </button>

                <!-- Lado Imagen -->
                <div class="w-full md:w-2/5 h-64 md:h-auto bg-[#111] relative">
                    <template x-if="activeProduct?.imagen_url">
                        <img :src="'/' + activeProduct.imagen_url" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!activeProduct?.imagen_url">
                        <div class="w-full h-full flex flex-col items-center justify-center text-gold bg-[#050505]">
                            <i :class="activeProduct?.icono ? activeProduct.icono : 'ph ph-image'" class="text-6xl mb-4"></i>
                        </div>
                    </template>
                    <!-- Gradiente sutil -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent md:hidden"></div>
                </div>

                <!-- Lado Contenido -->
                <div class="w-full md:w-3/5 p-6 md:p-8 flex flex-col">
                    <div class="mb-6">
                        <p x-show="activeProduct?.es_preventa" class="inline-block px-2 py-1 bg-gold/10 text-gold border border-gold/20 text-[10px] uppercase tracking-widest rounded mb-3">Preventa Exclusiva</p>
                        <h3 class="text-2xl md:text-3xl font-heading font-bold text-white mb-2" id="modal-title" x-text="activeProduct?.nombre"></h3>
                        <p class="text-gray-400 text-sm font-light leading-relaxed" x-text="activeProduct?.descripcion"></p>
                    </div>

                    <!-- Selector de Variantes (Si existen) -->
                    <template x-if="activeProduct?.variantes?.length > 0">
                        <div class="mb-8">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">Selecciona una opción</label>
                            <div class="space-y-2">
                                <template x-for="variant in activeProduct.variantes" :key="variant.id">
                                    <label class="flex items-center justify-between p-3 border rounded cursor-pointer transition-all"
                                           :class="selectedVariantId == variant.id ? 'border-gold bg-gold/5' : 'border-[#333] hover:border-gold/50'">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" :value="variant.id" x-model="selectedVariantId" class="text-gold focus:ring-gold bg-[#111] border-[#333]">
                                            <span class="text-sm font-medium text-white" x-text="variant.nombre"></span>
                                        </div>
                                        <span class="text-gold font-bold" x-text="'S/ ' + parseFloat(variant.precio).toFixed(2)"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Precio Fijo (Si no hay variantes) -->
                    <template x-if="!activeProduct?.variantes?.length">
                        <div class="mb-8 border-t border-[#222] pt-6 mt-auto">
                            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Precio</p>
                            <span class="text-3xl font-bold text-gold" x-text="'S/ ' + parseFloat(activeProduct?.precio_regular || 0).toFixed(2)"></span>
                        </div>
                    </template>

                    <!-- Botón Agregar -->
                    <div class="mt-auto pt-6 border-t border-[#222]">
                        <button @click="addToCart()" 
                                class="w-full flex items-center justify-center gap-2 bg-gold text-black font-bold uppercase tracking-widest text-sm py-4 hover:bg-white hover:scale-[1.02] transition-all shadow-[0_0_15px_rgba(212,175,55,0.2)]">
                            <i class="ph-bold ph-shopping-cart-simple text-lg"></i> Agregar al Pedido
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
