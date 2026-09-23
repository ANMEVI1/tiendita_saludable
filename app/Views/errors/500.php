<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error del Sistema | La Tiendita Saludable</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: '#d4af37',
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-[#050505] text-white antialiased min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-[#111] border border-[#333] rounded-lg shadow-2xl p-8 md:p-12 text-center animate__animated animate__fadeIn">
        
        <div class="inline-flex items-center justify-center w-24 h-24 bg-red-500/10 rounded-full mb-8">
            <i class="ph ph-warning-circle text-6xl text-red-500 animate-pulse"></i>
        </div>

        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4 uppercase tracking-widest">
            Algo salió mal
        </h1>
        
        <p class="text-gray-400 text-lg mb-8 font-light">
            Nuestro sistema se encuentra en mantenimiento o ha ocurrido un error de conexión con la base de datos.
        </p>

        <?php if (\App\Config\App::isLocal()): ?>
            <div class="bg-[#0a0a0a] border border-red-500/30 p-4 rounded text-left mb-8 overflow-x-auto">
                <h3 class="text-red-400 font-bold mb-2 text-sm uppercase">Detalles del Error (Solo visible en Development):</h3>
                <code class="text-gray-300 text-sm font-mono whitespace-pre-wrap">
                    <?= htmlspecialchars($errorMessage ?? 'Error desconocido.') ?>
                </code>
            </div>
        <?php endif; ?>

        <a href="/" class="inline-block bg-gold hover:bg-white text-black font-bold uppercase tracking-widest px-8 py-4 rounded-sm transition-colors duration-300">
            Reintentar Conexión
        </a>
    </div>
</body>
</html>
