<?php if (empty($catalogo)): ?>
    <p class="text-center text-gray-500 py-12">No hay categorías disponibles por el momento.</p>
<?php else: ?>
    <?php foreach($catalogo as $catIndex => $categoria): ?>
        <div class="mb-16 md:mb-24">
            <h3 class="text-2xl md:text-3xl font-heading font-bold text-white mb-8 md:mb-10 category-title animate-on-scroll" data-animation="animate__fadeInLeft">
                <?= htmlspecialchars($categoria['nombre']) ?>
            </h3>
            
            <?php 
                // Determine grid layout class based on plantilla
                $gridClass = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8';
                if ($categoria['plantilla_html'] === 'LISTA_VARIANTES') {
                    $gridClass = 'grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8';
                }
            ?>
            <div class="<?= $gridClass ?>">
                <?php foreach($categoria['productos'] as $index => $p): ?>
                    <?php 
                        $plantilla = $categoria['plantilla_html'];
                        $partialPath = __DIR__ . '/cards/_card_estandar.php'; // default
                        
                        if ($plantilla === 'LISTA_VARIANTES') {
                            $partialPath = __DIR__ . '/cards/_card_variantes.php';
                        } elseif ($plantilla === 'GRANOLAS') {
                            $partialPath = __DIR__ . '/cards/_card_granolas.php';
                        } elseif ($plantilla === 'COMPLEMENTARIOS') {
                            $partialPath = __DIR__ . '/cards/_card_complementarios.php';
                        }
                        
                        if (file_exists($partialPath)) {
                            include $partialPath;
                        } else {
                            include __DIR__ . '/cards/_card_estandar.php';
                        }
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
