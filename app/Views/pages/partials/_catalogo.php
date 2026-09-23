<?php if (empty($catalogo)): ?>
    <p class="text-center text-gray-500 py-12">No hay categorías disponibles por el momento.</p>
<?php else: ?>
    <?php foreach($catalogo as $catIndex => $categoria): ?>
        <div class="mb-16 md:mb-24">
            <h3 class="text-2xl md:text-3xl font-heading font-bold text-white mb-8 md:mb-10 category-title animate-on-scroll" data-animation="animate__fadeInLeft">
                <?= htmlspecialchars($categoria['nombre']) ?>
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                
                <?php if ($categoria['plantilla_html'] === 'ESTANDAR'): ?>
                    <?php foreach($categoria['productos'] as $index => $p): ?>
                        <div @click="openProductModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)" class="premium-card group cursor-pointer animate-on-scroll" data-animation="animate__fadeInUp" style="animation-delay: <?= ($index % 4) * 0.1 ?>s;">
                            <div class="p-6 flex flex-col h-full">
                                <h4 class="text-xl font-heading font-bold text-white mb-2"><?= htmlspecialchars($p['nombre']) ?></h4>
                                <p class="text-gray-400 text-sm font-light mb-4 flex-grow"><?= htmlspecialchars($p['descripcion']) ?></p>
                                <div class="flex justify-between items-center mt-6 pt-4 border-t border-[#333]">
                                    <span class="text-gold font-semibold text-xl">S/ <?= number_format($p['precio_regular'] ?? 0, 2) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                
                <?php elseif ($categoria['plantilla_html'] === 'LISTA_VARIANTES'): ?>
                    <!-- Tostadas y Palitos -->
                    <!-- Necesitamos forzar el layout de 3 columnas para esto -->
                    </div><div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                    <?php foreach($categoria['productos'] as $index => $p): ?>
                        <div @click="openProductModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)" class="premium-card group cursor-pointer overflow-hidden flex flex-col animate-on-scroll" data-animation="animate__fadeInUp" style="animation-delay: <?= ($index % 3) * 0.15 ?>s;">
                            <div class="h-40 md:h-48 bg-[#111] relative overflow-hidden">
                                <img src="<?= $p['imagen_url'] ? htmlspecialchars('/' . ltrim($p['imagen_url'], '/')) : '' ?>" alt="<?= htmlspecialchars($p['nombre']) ?>" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                            </div>
                            <div class="p-6 md:p-8 flex-grow flex flex-col">
                                <h4 class="text-xl md:text-2xl font-heading font-bold text-white mb-2"><?= htmlspecialchars($p['nombre']) ?></h4>
                                <p class="text-gray-400 text-sm font-light mb-6"><?= htmlspecialchars($p['descripcion']) ?></p>
                                
                                <?php if (!empty($p['variantes'])): ?>
                                    <ul class="text-sm text-gray-300 space-y-2 mb-6">
                                        <?php foreach($p['variantes'] as $var): ?>
                                            <li class="flex justify-between border-b border-[#333] pb-1">
                                                <span><?= htmlspecialchars($var['nombre']) ?>:</span> 
                                                <span class="text-gold">S/ <?= number_format($var['precio'], 2) ?> <?= $var['texto_secundario'] ? htmlspecialchars($var['texto_secundario']) : '' ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>

                                <?php if (!empty($p['caracteristicas'])): ?>
                                    <?php foreach($p['caracteristicas'] as $grupo => $valores): ?>
                                        <p class="text-xs text-gray-500 mb-2 uppercase tracking-widest"><?= htmlspecialchars($grupo) ?></p>
                                        <p class="text-sm text-gray-300 leading-relaxed mb-6"><?= htmlspecialchars(implode(', ', $valores)) ?></p>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php if ($p['precio_regular']): ?>
                                    <div class="mt-auto flex justify-between items-center pt-4 border-t border-[#333]">
                                        <span class="text-gray-400 text-xs uppercase tracking-widest">Precio Pack</span>
                                        <span class="text-gold font-semibold text-2xl">S/ <?= number_format($p['precio_regular'], 2) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php elseif ($categoria['plantilla_html'] === 'GRANOLAS'): ?>
                    <?php foreach($categoria['productos'] as $index => $p): ?>
                        <div @click="openProductModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)" class="premium-card group cursor-pointer text-center animate-on-scroll flex flex-col justify-between" data-animation="animate__fadeInUp" style="animation-delay: <?= ($index % 4) * 0.1 ?>s;">
                            <div class="p-6 md:p-8 flex-grow flex flex-col justify-center">
                                <h4 class="text-xl font-heading font-bold text-white mb-1"><?= htmlspecialchars($p['nombre']) ?></h4>
                                <p class="text-gold text-xs uppercase tracking-widest mb-6"><?= htmlspecialchars($p['descripcion']) ?></p>
                                <?php if (!empty($p['variantes'])): 
                                    $var = $p['variantes'][0]; // Granolas tienen 1 variante que guarda precios principal y secundario
                                ?>
                                    <div class="text-3xl font-bold text-gold mb-2">
                                        S/ <?= number_format($var['precio'], 2) ?> 
                                        <?php if ($var['precio_secundario']): ?>
                                            <span class="text-sm text-gray-500 font-normal block sm:inline">S/ <?= number_format($var['precio_secundario'], 2) ?> <?= htmlspecialchars($var['texto_secundario']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php elseif ($categoria['plantilla_html'] === 'COMPLEMENTARIOS'): ?>
                    <?php foreach($categoria['productos'] as $index => $p): ?>
                        <?php if ($p['icono']): ?>
                            <div @click="openProductModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)" class="premium-card group cursor-pointer flex flex-col justify-center text-center p-8 animate-on-scroll" data-animation="animate__fadeInUp" style="animation-delay: <?= ($index % 4) * 0.1 ?>s;">
                                <i class="<?= htmlspecialchars($p['icono']) ?> text-gold text-4xl mb-4"></i>
                                <h4 class="text-xl font-heading font-bold text-white mb-2"><?= htmlspecialchars($p['nombre']) ?></h4>
                                <p class="text-gray-400 text-sm mb-4"><?= htmlspecialchars($p['descripcion']) ?></p>
                                <span class="text-gold font-bold text-2xl">S/ <?= number_format($p['precio_regular'] ?? 0, 2) ?> <span class="text-sm font-normal text-gray-500">und</span></span>
                            </div>
                        <?php else: ?>
                            <div @click="openProductModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)" class="premium-card group cursor-pointer animate-on-scroll" data-animation="animate__fadeInUp" style="animation-delay: <?= ($index % 4) * 0.1 ?>s;">
                                <div class="h-48 md:h-56 overflow-hidden">
                                    <img src="<?= $p['imagen_url'] ? htmlspecialchars('/' . ltrim($p['imagen_url'], '/')) : '' ?>" alt="<?= htmlspecialchars($p['nombre']) ?>" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-700">
                                </div>
                                <div class="p-6 text-center border-t border-[#333]">
                                    <h4 class="text-lg font-heading font-bold text-white"><?= htmlspecialchars($p['nombre']) ?></h4>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
