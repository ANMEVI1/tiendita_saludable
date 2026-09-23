    <!-- Footer -->
    <footer id="contacto" class="pt-16 md:pt-20 pb-8 md:pb-10 animate-on-scroll" data-animation="animate__fadeIn">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 md:gap-12 mb-12 md:mb-16">
                <div class="lg:col-span-2">
                    <a href="/" class="flex items-center gap-3 md:gap-4 group mb-6 inline-flex">
                        <div class="relative w-10 h-10 md:w-12 md:h-12 overflow-hidden rounded-sm border-2 border-transparent group-hover:border-gold/50 transition-all duration-300 shadow-[0_0_15px_rgba(212,175,55,0.1)]">
                            <img src="/assets/img/logo/logo_relialista_negro.jpg" alt="Logo La Tiendita Saludable" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="flex flex-col justify-center">
                            <span class="text-xl md:text-2xl font-heading font-bold text-white tracking-[0.15em] uppercase leading-none">La Tiendita</span>
                            <span class="text-gold text-[10px] md:text-xs tracking-[0.4em] uppercase font-semibold leading-none mt-1.5 md:mt-1 ml-0.5">Saludable</span>
                        </div>
                    </a>
                    <p class="text-gray-400 font-light max-w-sm mb-6 text-sm md:text-base"><?= htmlspecialchars($configWeb['footer_descripcion'] ?? '') ?></p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full border border-[#333] flex items-center justify-center text-gray-400 hover:border-gold hover:text-gold transition-colors"><i class="ph ph-instagram-logo"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full border border-[#333] flex items-center justify-center text-gray-400 hover:border-gold hover:text-gold transition-colors"><i class="ph ph-facebook-logo"></i></a>
                    </div>
                </div>
                <div>
                    <h3 class="text-white font-heading tracking-widest uppercase mb-6">Contacto</h3>
                    <ul class="space-y-4 text-gray-400 font-light text-sm">
                        <li class="flex items-start"><i class="ph ph-map-pin text-gold mr-3 mt-1 text-lg"></i> Tumbes, Perú</li>
                        <li class="flex items-start"><i class="ph ph-phone text-gold mr-3 mt-1 text-lg"></i> +<?= htmlspecialchars($configWeb['whatsapp_numero'] ?? '51 984 247 684') ?></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-heading tracking-widest uppercase mb-6">Privacidad</h3>
                    <ul class="space-y-4 text-gray-400 font-light text-sm">
                        <li><a href="#" class="hover:text-gold transition-colors">Términos y Condiciones</a></li>
                        <li><a href="#" class="hover:text-gold transition-colors">Política de Privacidad</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-[#333] pt-8 text-center">
                <p class="text-gray-600 text-xs tracking-widest uppercase">&copy; 2026 La Tiendita Saludable. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Premium Widget -->
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
        <!-- Chat Box -->
        <div id="wa-chat-box" class="bg-[#0a0a0a] border border-[#333] w-72 mb-4 rounded-sm shadow-2xl opacity-0 pointer-events-none transform translate-y-4 transition-all duration-300 origin-bottom-right flex flex-col overflow-hidden">
            <!-- Header -->
            <div class="bg-[#111] p-4 border-b border-[#333] flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gold/10 flex items-center justify-center border border-gold/30">
                        <i class="ph-fill ph-whatsapp-logo text-gold text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-heading text-xs uppercase tracking-widest font-semibold">La Tiendita</h4>
                        <p class="text-[9px] text-[#25D366] uppercase tracking-widest mt-1">En línea</p>
                    </div>
                </div>
                <button id="close-wa-box" class="text-gray-500 hover:text-gold transition-colors"><i class="ph ph-x text-lg"></i></button>
            </div>
            
            <!-- Body -->
            <div class="p-5 flex flex-col gap-3 bg-[#050505]">
                <p class="text-xs text-gray-400 mb-2 font-light leading-relaxed">Selecciona una opción para iniciar tu consulta vía WhatsApp:</p>
                <?php $waNumber = htmlspecialchars($configWeb['whatsapp_numero'] ?? '51984247684'); ?>
                
                <a href="https://wa.me/<?= $waNumber ?>?text=Hola%20La%20Tiendita%20Saludable,%20quisiera%20realizar%20un%20pedido." target="_blank" class="group flex items-center justify-between p-3 bg-[#111] hover:bg-gold border border-[#333] hover:border-gold transition-all duration-300">
                    <span class="text-[10px] text-gray-300 group-hover:text-black uppercase tracking-widest font-semibold">Realizar Pedido</span>
                    <i class="ph ph-caret-right text-gold group-hover:text-black"></i>
                </a>
                
                <a href="https://wa.me/<?= $waNumber ?>?text=Hola,%20quisiera%20informaci%C3%B3n%20sobre%20ventas%20al%20por%20mayor%20para%20negocios." target="_blank" class="group flex items-center justify-between p-3 bg-[#111] hover:bg-gold border border-[#333] hover:border-gold transition-all duration-300">
                    <span class="text-[10px] text-gray-300 group-hover:text-black uppercase tracking-widest font-semibold">Ventas al por mayor</span>
                    <i class="ph ph-caret-right text-gold group-hover:text-black"></i>
                </a>
                
                <a href="https://wa.me/<?= $waNumber ?>?text=Hola,%20tengo%20una%20consulta%20sobre%20sus%20productos." target="_blank" class="group flex items-center justify-between p-3 bg-[#111] hover:bg-gold border border-[#333] hover:border-gold transition-all duration-300">
                    <span class="text-[10px] text-gray-300 group-hover:text-black uppercase tracking-widest font-semibold">Asesoría / Consultas</span>
                    <i class="ph ph-caret-right text-gold group-hover:text-black"></i>
                </a>
            </div>
        </div>

        <!-- Floating Button -->
        <button id="wa-toggle-btn" class="w-14 h-14 bg-[#111] border border-gold rounded-full flex items-center justify-center text-gold text-3xl shadow-[0_0_15px_rgba(212,175,55,0.2)] hover:bg-gold hover:text-black hover:scale-110 transition-all duration-300 relative group">
            <i class="ph-fill ph-whatsapp-logo"></i>
            <span class="absolute top-0 right-0 flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-gold opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-gold"></span>
            </span>
        </button>
    </div>

    <!-- Script de Animaciones y Chat -->
    <script src="/assets/js/animations.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Lógica de Tabs Dinámica (Sobre Nosotros)
            const tabsContainer = document.getElementById('about-tabs-container');
            const dynamicText = document.getElementById('dynamic-text');

            if (tabsContainer && dynamicText) {
                const contentData = {
                    mision: <?= json_encode($configWeb['nosotros_mision'] ?? '', JSON_UNESCAPED_UNICODE) ?>,
                    vision: <?= json_encode($configWeb['nosotros_vision'] ?? '', JSON_UNESCAPED_UNICODE) ?>,
                    historia: <?= json_encode($configWeb['nosotros_historia'] ?? '', JSON_UNESCAPED_UNICODE) ?>,
                };

                // Generar pestañas dinámicamente a partir del objeto
                tabsContainer.innerHTML = '';
                let isFirst = true;
                for (const key in contentData) {
                    const btn = document.createElement('button');
                    btn.className = `about-tab text-gray-400 hover:text-white font-heading tracking-widest uppercase text-xs sm:text-sm md:text-base font-semibold py-2 px-4 transition-colors ${isFirst ? 'active border-b-2 border-gold text-white' : ''}`;
                    btn.setAttribute('data-target', key);
                    btn.textContent = 'Nuestra ' + key.charAt(0).toUpperCase() + key.slice(1);
                    tabsContainer.appendChild(btn);
                    isFirst = false;
                }

                const tabs = document.querySelectorAll('.about-tab');
                
                function updateText(targetKey) {
                    const text = contentData[targetKey];
                    const linkHTML = ` <a href="/sobre-nosotros" class="text-gold font-sans not-italic text-sm md:text-base ml-2 hover:underline page-transition">Conocer Más...</a>`;
                    
                    dynamicText.classList.remove('animate__fadeInUp', 'animate__fadeIn');
                    dynamicText.classList.add('animate__fadeOutDown', 'animate__fast');
                    
                    setTimeout(() => {
                        dynamicText.innerHTML = text + linkHTML;
                        dynamicText.classList.remove('animate__fadeOutDown');
                        dynamicText.classList.add('animate__fadeInUp');
                    }, 300);
                }

                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        tabs.forEach(t => {
                            t.classList.remove('active', 'border-b-2', 'border-gold', 'text-white');
                            t.classList.add('text-gray-400');
                        });
                        tab.classList.remove('text-gray-400');
                        tab.classList.add('active', 'border-b-2', 'border-gold', 'text-white');
                        updateText(tab.getAttribute('data-target'));
                    });
                });

                if (Object.keys(contentData).length > 0) {
                    updateText(Object.keys(contentData)[0]);
                }
            }

            // Manejo del menú móvil
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const closeMenuBtn = document.getElementById('close-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileLinks = document.querySelectorAll('.mobile-link');
            
            function toggleMenu() {
                if (mobileMenu.classList.contains('opacity-0')) {
                    mobileMenu.classList.remove('opacity-0', 'pointer-events-none', 'scale-105');
                    mobileMenu.classList.add('opacity-100', 'pointer-events-auto', 'scale-100');
                    document.body.style.overflow = 'hidden';
                } else {
                    mobileMenu.classList.add('opacity-0', 'pointer-events-none', 'scale-105');
                    mobileMenu.classList.remove('opacity-100', 'pointer-events-auto', 'scale-100');
                    document.body.style.overflow = '';
                }
            }

            if (mobileMenuBtn && closeMenuBtn) {
                mobileMenuBtn.addEventListener('click', toggleMenu);
                closeMenuBtn.addEventListener('click', toggleMenu);
                mobileLinks.forEach(link => {
                    link.addEventListener('click', toggleMenu);
                });
            }

            // Manejo del cajón de WhatsApp
            const waToggleBtn = document.getElementById('wa-toggle-btn');
            const closeWaBox = document.getElementById('close-wa-box');
            const waChatBox = document.getElementById('wa-chat-box');

            function toggleWaBox() {
                if (waChatBox.classList.contains('opacity-0')) {
                    waChatBox.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
                    waChatBox.classList.add('opacity-100', 'pointer-events-auto', 'translate-y-0');
                } else {
                    waChatBox.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
                    waChatBox.classList.remove('opacity-100', 'pointer-events-auto', 'translate-y-0');
                }
            }

            if (waToggleBtn && closeWaBox) {
                waToggleBtn.addEventListener('click', toggleWaBox);
                closeWaBox.addEventListener('click', toggleWaBox);
            }
            
            // Transiciones de página hermosas
            const pageTransitions = document.querySelectorAll('.page-transition');
            pageTransitions.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    const mainContent = document.getElementById('main-content');
                    
                    if(mainContent) {
                        mainContent.classList.remove('animate__fadeIn');
                        mainContent.classList.add('animate__fadeOut', 'animate__faster');
                    }
                    
                    setTimeout(() => {
                        window.location.href = href;
                    }, 300);
                });
            });
        });
    </script>
</body>
</html>
