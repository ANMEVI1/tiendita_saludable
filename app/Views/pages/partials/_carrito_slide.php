<!-- Slide-over Carrito -->
<div x-show="isCartOpen" style="display: none;" class="fixed inset-0 z-[200]" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    
    <!-- Backdrop oscuro -->
    <div x-show="isCartOpen" 
         x-transition:enter="ease-in-out duration-500" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in-out duration-500" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-black bg-opacity-75 backdrop-blur-sm transition-opacity" 
         @click="isCartOpen = false"></div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                
                <!-- Panel Lateral -->
                <div x-show="isCartOpen" 
                     x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" 
                     x-transition:enter-start="translate-x-full" 
                     x-transition:enter-end="translate-x-0" 
                     x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" 
                     x-transition:leave-start="translate-x-0" 
                     x-transition:leave-end="translate-x-full" 
                     class="pointer-events-auto w-screen max-w-md">
                    
                    <div class="flex h-full flex-col bg-[#0a0a0a] border-l border-[#333] shadow-2xl">
                        
                        <!-- Header -->
                        <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                            <div class="flex items-start justify-between">
                                <h2 class="text-xl font-heading font-bold text-white uppercase tracking-widest" id="slide-over-title">Tu Pedido</h2>
                                <div class="ml-3 flex h-7 items-center">
                                    <button type="button" @click="isCartOpen = false" class="relative -m-2 p-2 text-gray-400 hover:text-gold transition-colors">
                                        <span class="absolute -inset-0.5"></span>
                                        <span class="sr-only">Cerrar panel</span>
                                        <i class="ph ph-x text-2xl"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-8">
                                <div class="flow-root">
                                    
                                    <template x-if="cart.length === 0">
                                        <div class="text-center py-10">
                                            <i class="ph-thin ph-shopping-cart text-6xl text-gray-600 mb-4"></i>
                                            <p class="text-gray-400 font-light">Tu carrito está vacío.</p>
                                        </div>
                                    </template>

                                    <ul role="list" class="-my-6 divide-y divide-[#333]">
                                        <template x-for="item in cart" :key="item.cartItemId">
                                            <li class="flex py-6">
                                                <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-md border border-[#333]">
                                                    <template x-if="item.image">
                                                        <img :src="'/' + item.image" class="h-full w-full object-cover object-center">
                                                    </template>
                                                    <template x-if="!item.image">
                                                        <div class="h-full w-full bg-[#111] flex items-center justify-center">
                                                            <i class="ph ph-image text-gray-500 text-xl"></i>
                                                        </div>
                                                    </template>
                                                </div>

                                                <div class="ml-4 flex flex-1 flex-col">
                                                    <div>
                                                        <div class="flex justify-between text-sm font-semibold text-white">
                                                            <h3 x-text="item.name" class="font-heading tracking-wide"></h3>
                                                            <p class="ml-4 text-gold" x-text="'S/ ' + (item.price * item.quantity).toFixed(2)"></p>
                                                        </div>
                                                        <p class="mt-1 text-xs text-gray-400 uppercase tracking-widest" x-text="item.variantName"></p>
                                                    </div>
                                                    <div class="flex flex-1 items-end justify-between text-sm">
                                                        
                                                        <!-- Controles de Cantidad -->
                                                        <div class="flex items-center border border-[#333] rounded">
                                                            <button @click="updateQuantity(item.cartItemId, -1)" class="px-3 py-1 text-gray-400 hover:text-gold hover:bg-[#111] transition-colors">-</button>
                                                            <span class="px-3 text-white text-xs" x-text="item.quantity"></span>
                                                            <button @click="updateQuantity(item.cartItemId, 1)" class="px-3 py-1 text-gray-400 hover:text-gold hover:bg-[#111] transition-colors">+</button>
                                                        </div>

                                                        <div class="flex">
                                                            <button @click="removeFromCart(item.cartItemId)" type="button" class="font-medium text-red-500 hover:text-red-400 text-xs tracking-widest uppercase">Remover</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Footer / Checkout -->
                        <div class="border-t border-[#333] px-4 py-6 sm:px-6 bg-[#050505]">
                            <div class="flex justify-between text-base font-bold text-white mb-4">
                                <p class="font-heading uppercase tracking-widest text-lg">Subtotal</p>
                                <p class="text-gold text-2xl" x-text="'S/ ' + cartTotalCost"></p>
                            </div>
                            <p class="mt-0.5 text-xs text-gray-400 font-light mb-6">El envío se calculará por WhatsApp. Gratis en Andrés Araujo Morán.</p>
                            <div class="mt-6 flex flex-col gap-3">
                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <button @click="$dispatch('open-checkout')" :disabled="cart.length === 0" 
                                            class="flex items-center justify-center w-full rounded-sm border border-transparent bg-gold px-6 py-4 text-sm font-bold uppercase tracking-widest text-black shadow-sm hover:bg-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="ph-bold ph-credit-card mr-2 text-xl"></i> Pagar Ahora
                                    </button>
                                <?php else: ?>
                                    <a href="/login" 
                                       class="flex items-center justify-center w-full rounded-sm border border-transparent bg-[#222] text-white px-6 py-4 text-sm font-bold uppercase tracking-widest shadow-sm hover:bg-gold hover:text-black transition-colors text-center">
                                        <i class="ph-bold ph-sign-in mr-2 text-xl"></i> Iniciar Sesión para Comprar
                                    </a>
                                <?php endif; ?>
                                
                                <button type="button" @click="isCartOpen = false" class="flex items-center justify-center w-full rounded-sm border border-gold text-gold bg-transparent px-6 py-4 text-sm font-bold uppercase tracking-widest hover:bg-gold hover:text-black transition-colors mt-2">
                                    Seguir Comprando
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
