-- USE `db_tiendita_saludable`;

-- 1. Insertar Roles
INSERT INTO `ts_roles` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', 'Control total del sistema'),
(2, 'Vendedor', 'Gestión de productos y pedidos'),
(3, 'Cliente', 'Usuario comprador');

-- 2. Insertar Usuario Administrador (admin@tiendita.com / empe123)
INSERT INTO `ts_usuarios` (`id`, `email`, `password_hash`, `rol_id`) VALUES
(1, 'admin@tiendita.com', '$2y$10$8HHYgTc/sMF/TErmgwe/sep/9qCriYoC367Y5Hkad8GVW3bMaGoaK', 1);

-- 3. Crear el Perfil Cliente del Administrador
INSERT INTO `ts_clientes` (`usuario_id`, `nombres`, `apellidos`, `telefono_whatsapp`) VALUES
(1, 'Admin', 'Tiendita', '51984247684');

-- 4. Insertar Categorías (Con su orden y plantilla para diseño exacto)
INSERT INTO `ts_categorias` (`id`, `nombre`, `descripcion`, `orden`, `plantilla_html`) VALUES
(1, 'Panadería Integral', 'Panes de molde artesanales', 1, 'ESTANDAR'),
(2, 'Tostadas y Palitos', 'Snacks salados y galletas', 2, 'LISTA_VARIANTES'),
(3, 'Granolas Premium', 'Granolas artesanales', 3, 'GRANOLAS'),
(4, 'Productos Complementarios', 'Miel, algarrobina y más', 4, 'COMPLEMENTARIOS');

-- 5. Insertar Productos

-- Panadería Integral (Categoría 1)
INSERT INTO `ts_productos` (`id`, `categoria_id`, `nombre`, `descripcion`, `imagen_url`, `precio_regular`, `orden`) VALUES
(1, 1, 'Molde Ajonjolí', '27 Cortes. Semillas de girasol y linaza.', NULL, 10.60, 1),
(2, 1, 'Molde Chía', '27 Cortes. Alto en fibra y omega 3.', NULL, 11.00, 2),
(3, 1, 'Molde Avena', '27 Cortes. Textura suave y nutritiva.', NULL, 10.60, 3),
(4, 1, 'Redondo Fibra / Higo', '12 Unidades. Suaves y naturales.', NULL, 9.00, 4);

-- En el Redondo Fibra / Higo el precio es "S/ 9.00 - S/ 9.60", así que usamos variante para el segundo. 
-- O lo dejamos como precio_regular = 9.00 y agregamos variante si queremos (pero en el HTML está harcodeado S/9.00 - S/9.60).
-- Lo dejaremos así y en HTML mostraremos precio regular + texto si existe.

-- Tostadas y Palitos (Categoría 2)
INSERT INTO `ts_productos` (`id`, `categoria_id`, `nombre`, `descripcion`, `imagen_url`, `precio_regular`, `orden`) VALUES
(5, 2, 'Tostadas Multigranos', 'Crujientes, horneadas lentamente. Disponibles en Ajonjolí, Chía y Avena.', 'assets/info-a-recaudar-para-el-negocio/algunos-productos.png', NULL, 1),
(6, 2, 'Palitos Artesanales', 'Masa semi-hojaldre. Pack por 6 unidades. Caja x50 paquetes.', 'https://images.unsplash.com/photo-1600989218206-382a5dc27806?q=80&w=2070&auto=format&fit=crop', 15.60, 2),
(7, 2, 'Galletas & Rosquitas', 'Endulzadas con panela. Pack por 6 unidades. Presentación premium.', 'assets/info-a-recaudar-para-el-negocio/galletas-integrales.png', 19.80, 3);

-- Variantes para Tostadas
INSERT INTO `ts_producto_variantes` (`producto_id`, `nombre`, `precio`, `orden`) VALUES
(5, 'Paquete x8 und', 4.40, 1),
(5, 'Paquete x16 und', 8.00, 2),
(5, 'Paquete x22 und', 10.00, 3);
-- Para el x27 que tiene rango 11.60 - 12.00, lo pondremos como 11.60 y secundario 12.00 o en texto.
INSERT INTO `ts_producto_variantes` (`producto_id`, `nombre`, `precio`, `texto_secundario`, `orden`) VALUES
(5, 'Paquete x27 und', 11.60, '- S/ 12.00', 4);

-- Características para Palitos
INSERT INTO `ts_producto_caracteristicas` (`producto_id`, `grupo`, `valor`, `orden`) VALUES
(6, 'Variedades:', 'Queso, Orégano, Ajo, Finas Hierbas, Aceituna, Ajonjolí, Canelados, Dulces.', 1);

-- Características para Galletas
INSERT INTO `ts_producto_caracteristicas` (`producto_id`, `grupo`, `valor`, `orden`) VALUES
(7, 'Galletas Yogurt:', 'Coco, Ajonjolí, Kiwicha, Algarrobina.', 1),
(7, 'Rosquitas:', 'Anís, Coco, Ajonjolí, Saladas, Algarrobina.', 2);


-- Granolas Premium (Categoría 3)
INSERT INTO `ts_productos` (`id`, `categoria_id`, `nombre`, `orden`) VALUES
(8, 3, 'Granolas Premium', 1);

-- Variantes para Granola (El HTML las lista como 4 cajas, las haremos como variantes de este producto o como productos individuales)
-- Dado el diseño, las Granolas son 4 tarjetas separadas. Sería mejor 4 productos. Actualizamos:
DELETE FROM `ts_productos` WHERE `id` = 8;
INSERT INTO `ts_productos` (`id`, `categoria_id`, `nombre`, `descripcion`, `orden`) VALUES
(8, 3, 'Formato 100g', 'Locheritas', 1),
(9, 3, 'Formato 350g', 'Cierre Hermético', 2),
(10, 3, 'Formato 500g', 'Cierre Hermético', 3),
(11, 3, 'Formato 1Kg', 'Tamaño Familiar', 4);

INSERT INTO `ts_producto_variantes` (`producto_id`, `nombre`, `precio`, `precio_secundario`, `texto_secundario`, `orden`) VALUES
(8, 'Regular', 3.60, 3.90, 'stevia', 1),
(9, 'Regular', 11.00, 11.40, 'stevia', 1),
(10, 'Regular', 15.80, 16.40, 'stevia', 1),
(11, 'Regular', 29.80, 31.80, 'stevia', 1);

-- Productos Complementarios (Categoría 4)
INSERT INTO `ts_productos` (`id`, `categoria_id`, `nombre`, `descripcion`, `imagen_url`, `icono`, `precio_regular`, `orden`) VALUES
(12, 4, 'Algarrobina Natural', NULL, 'assets/info-a-recaudar-para-el-negocio/product-algarrobina-natural.jpeg', NULL, NULL, 1),
(13, 4, 'Miel de Abeja Pura', NULL, 'assets/info-a-recaudar-para-el-negocio/product-miel-de-abeja.jpeg', NULL, NULL, 2),
(14, 4, 'Yogures Artesanales', NULL, 'assets/info-a-recaudar-para-el-negocio/yogures-naturales.jpeg', NULL, NULL, 3),
(15, 4, 'Zumo de Manzana', 'Sin azúcares añadidos. 296ml.', NULL, 'ph-fill ph-drop', 4.90, 4);


-- 6. Configuración Web Básica
INSERT INTO `ts_configuracion_web` (`clave`, `valor`, `tipo_dato`, `descripcion`) VALUES
('hero_titulo', 'Come Sano Vive Mejor!', 'TEXTO', 'Título principal del Home'),
('hero_subtitulo', 'Tumbes, Perú', 'TEXTO', 'Subtítulo pequeño encima del Hero'),
('hero_descripcion', 'Nació de la necesidad de cuidar a los nuestros, hoy seleccionamos y llevamos a tu mesa en Tumbes los mejores productos naturales, para nutrir tu vida y proteger tu salud.', 'TEXTO', 'Párrafo de descripción del Home'),
('nosotros_mision', '"Nuestra misión es cuidar de tu bienestar y el de tu familia ofreciendo productos de consumo 100% naturales y saludables. Nos dedicamos a brindarte una atención humana y personalizada, llevando lo mejor de la naturaleza directamente a la puerta de tu casa de forma gratuita en el AA.HH. Andrés Araujo Morán (Tumbes). A través de nuestro cuidadoso modelo de pedidos programados y preventa exclusiva, nos aseguramos de que cada producto que recibas llegue con la mejor calidad y frescura a tu mesa."', 'TEXTO', 'Texto de Misión'),
('nosotros_vision', '"Nuestra visión es consolidar a Tiendita Saludable como un espacio físico de referencia en bienestar y sostenibilidad. Proyectamos expandir nuestra oferta integrando alimentos artesanales de la más alta calidad, junto con una línea integral de productos ecológicos para el cuidado personal y del hogar. Nos impulsa el compromiso de crear un ambiente acogedor y profundamente conectado con la naturaleza, mientras generamos un impacto social positivo al fomentar la empleabilidad y el desarrollo de nuestra comunidad local."', 'TEXTO', 'Texto de Visión'),
('nosotros_historia', '"Tiendita Saludable nació de una profunda necesidad personal: superar las adversidades económicas y, sobre todo, encontrar productos que realmente protegieran mi salud y la de mis hijos. Al buscar estas alternativas, me enfrenté a un mercado que carecía de opciones verdaderamente sanas. En ese camino, descubrí que muchas otras personas compartían mi misma lucha y frustración. Así, lo que comenzó como una búsqueda para el bienestar de mi familia, se transformó en un espacio dedicado a brindar a nuestra comunidad las opciones que tanto necesitábamos para mantener una alimentación sana y consciente."', 'TEXTO', 'Texto de Historia'),
('footer_descripcion', 'Comprometidos con la excelencia en cada horneado, utilizando solo ingredientes integrales de la más alta pureza en Tumbes.', 'TEXTO', 'Texto pequeño en el pie de página'),
('whatsapp_numero', '51984247684', 'TEXTO', 'Número principal de WhatsApp'),
('catalogo_titulo', 'La Colección', 'TEXTO', 'Título de la sección de productos'),
('catalogo_descripcion', 'Nuestra línea completa de productos artesanales. Elaborados con ingredientes 100% integrales, sin azúcar refinada y llenos de sabor natural.', 'TEXTO', 'Subtítulo del catálogo'),
('limite_productos_categoria_inicio', '8', 'NUMERO', 'Número máximo de productos visibles por categoría en la página principal'),
('limite_productos_preventa_inicio', '4', 'NUMERO', 'Número máximo de productos visibles en la sección de preventa');
