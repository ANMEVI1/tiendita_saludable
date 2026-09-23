-- Base de datos: Tiendita Saludable (Versión 2 - Profesional)
-- Estructura optimizada y compatible con cPanel / phpMyAdmin / Hosting

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `ts_detalle_pedidos`;
DROP TABLE IF EXISTS `ts_pedidos`;
DROP TABLE IF EXISTS `ts_metodos_pago`;
DROP TABLE IF EXISTS `ts_producto_caracteristicas`;
DROP TABLE IF EXISTS `ts_producto_variantes`;
DROP TABLE IF EXISTS `ts_productos`;
DROP TABLE IF EXISTS `ts_categorias`;
DROP TABLE IF EXISTS `ts_clientes`;
DROP TABLE IF EXISTS `ts_zonas_ubicacion`;
DROP TABLE IF EXISTS `ts_usuarios`;
DROP TABLE IF EXISTS `ts_rol_permisos`;
DROP TABLE IF EXISTS `ts_permisos`;
DROP TABLE IF EXISTS `ts_roles`;
DROP TABLE IF EXISTS `ts_configuracion_web`;

SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------
-- 1. Tablas de Accesos y Roles (RBAC)
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `ts_roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(50) NOT NULL,
  `descripcion` VARCHAR(255) NULL,
  `activo` TINYINT(1) DEFAULT 1,
  `creado_en` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `modificado_en` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado_en` DATETIME NULL
);

CREATE TABLE IF NOT EXISTS `ts_permisos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(50) NOT NULL UNIQUE,
  `descripcion` VARCHAR(255) NULL,
  `activo` TINYINT(1) DEFAULT 1,
  `creado_en` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `modificado_en` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado_en` DATETIME NULL
);

CREATE TABLE IF NOT EXISTS `ts_rol_permisos` (
  `rol_id` INT NOT NULL,
  `permiso_id` INT NOT NULL,
  PRIMARY KEY (`rol_id`, `permiso_id`),
  FOREIGN KEY (`rol_id`) REFERENCES `ts_roles`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permiso_id`) REFERENCES `ts_permisos`(`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `ts_usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `firebase_uid` VARCHAR(128) NULL UNIQUE COMMENT 'UID proporcionado por Firebase Auth',
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NULL COMMENT 'Null si se usa login social exclusivamente',
  `foto_url` VARCHAR(500) NULL,
  `metodo_auth` ENUM('email', 'google', 'facebook') DEFAULT 'email',
  `rol_id` INT NOT NULL,
  `activo` TINYINT(1) DEFAULT 1,
  `creado_en` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `modificado_en` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado_en` DATETIME NULL,
  FOREIGN KEY (`rol_id`) REFERENCES `ts_roles`(`id`)
);

-- -----------------------------------------------------
-- 2. Tablas de Perfil y Ubicación
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `ts_zonas_ubicacion` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL COMMENT 'Ej: AA.HH. Andrés Araujo Morán',
  `costo_envio` DECIMAL(10,2) DEFAULT 0.00,
  `activo` TINYINT(1) DEFAULT 1,
  `creado_en` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `modificado_en` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado_en` DATETIME NULL
);

CREATE TABLE IF NOT EXISTS `ts_clientes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NOT NULL UNIQUE,
  `zona_ubicacion_id` INT NULL,
  `nombres` VARCHAR(100) NOT NULL,
  `apellidos` VARCHAR(100) NOT NULL,
  `tipo_documento` VARCHAR(20) NULL COMMENT 'DNI, CE, Pasaporte',
  `nro_documento` VARCHAR(20) NULL UNIQUE,
  `telefono_whatsapp` VARCHAR(20) NULL,
  `direccion` VARCHAR(255) NULL COMMENT 'Dirección principal de entrega',
  `activo` TINYINT(1) DEFAULT 1,
  `creado_en` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `modificado_en` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado_en` DATETIME NULL,
  FOREIGN KEY (`usuario_id`) REFERENCES `ts_usuarios`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`zona_ubicacion_id`) REFERENCES `ts_zonas_ubicacion`(`id`)
);

-- -----------------------------------------------------
-- 3. Tablas de Catálogo
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `ts_categorias` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` VARCHAR(255) NULL,
  `orden` INT DEFAULT 0,
  `plantilla_html` VARCHAR(50) DEFAULT 'ESTANDAR' COMMENT 'ESTANDAR, LISTA_VARIANTES, GRANOLAS, MULTI_LISTA',
  `activo` TINYINT(1) DEFAULT 1,
  `creado_en` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `modificado_en` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado_en` DATETIME NULL
);

CREATE TABLE IF NOT EXISTS `ts_productos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `categoria_id` INT NOT NULL,
  `nombre` VARCHAR(150) NOT NULL,
  `descripcion` TEXT NULL,
  `ingredientes` TEXT NULL,
  `imagen_url` VARCHAR(500) NULL,
  `icono` VARCHAR(50) NULL COMMENT 'Clase del icono Phosphor Ej: ph-fill ph-drop',
  `precio_regular` DECIMAL(10,2) NULL,
  `precio_preventa` DECIMAL(10,2) NULL,
  `es_preventa` TINYINT(1) DEFAULT 0,
  `stock_actual` INT DEFAULT 0,
  `orden` INT DEFAULT 0,
  `activo` TINYINT(1) DEFAULT 1,
  `creado_en` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `modificado_en` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado_en` DATETIME NULL,
  FOREIGN KEY (`categoria_id`) REFERENCES `ts_categorias`(`id`)
);

CREATE TABLE IF NOT EXISTS `ts_producto_variantes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `producto_id` INT NOT NULL,
  `nombre` VARCHAR(100) NOT NULL COMMENT 'Ej: Paquete x8 und o Formato 100g',
  `subtitulo` VARCHAR(100) NULL COMMENT 'Ej: Locheritas',
  `precio` DECIMAL(10,2) NOT NULL,
  `precio_secundario` DECIMAL(10,2) NULL COMMENT 'Ej: Precio con Stevia',
  `texto_secundario` VARCHAR(50) NULL COMMENT 'Ej: stevia',
  `orden` INT DEFAULT 0,
  FOREIGN KEY (`producto_id`) REFERENCES `ts_productos`(`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `ts_producto_caracteristicas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `producto_id` INT NOT NULL,
  `grupo` VARCHAR(50) NULL COMMENT 'Ej: Variedades, Galletas Yogurt',
  `valor` VARCHAR(255) NOT NULL,
  `orden` INT DEFAULT 0,
  FOREIGN KEY (`producto_id`) REFERENCES `ts_productos`(`id`) ON DELETE CASCADE
);

-- -----------------------------------------------------
-- 4. Tablas de Ventas y Pedidos Programados
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `ts_metodos_pago` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` VARCHAR(255) NULL,
  `instrucciones` TEXT NULL COMMENT 'Ej: Número de Yape o cuenta bancaria',
  `imagen_url` VARCHAR(500) NULL,
  `activo` TINYINT(1) DEFAULT 1,
  `creado_en` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `modificado_en` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado_en` DATETIME NULL
);

CREATE TABLE IF NOT EXISTS `ts_pedidos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `cliente_id` INT NOT NULL,
  `metodo_pago_id` INT NOT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  `estado_pedido` ENUM('PENDIENTE', 'PAGADO', 'HORNEANDO', 'EN_CAMINO', 'ENTREGADO', 'CANCELADO') DEFAULT 'PENDIENTE',
  `tipo_pedido` ENUM('REGULAR', 'PREVENTA') DEFAULT 'REGULAR',
  `fecha_entrega_programada` DATETIME NULL COMMENT 'Fundamental para pedidos de preventa',
  `creado_en` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `modificado_en` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado_en` DATETIME NULL,
  FOREIGN KEY (`cliente_id`) REFERENCES `ts_clientes`(`id`),
  FOREIGN KEY (`metodo_pago_id`) REFERENCES `ts_metodos_pago`(`id`)
);

CREATE TABLE IF NOT EXISTS `ts_detalle_pedidos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `pedido_id` INT NOT NULL,
  `producto_id` INT NOT NULL,
  `cantidad` INT NOT NULL,
  `precio_unitario` DECIMAL(10,2) NOT NULL COMMENT 'Histórico del precio al momento de comprar',
  `subtotal` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`pedido_id`) REFERENCES `ts_pedidos`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`producto_id`) REFERENCES `ts_productos`(`id`)
);

-- -----------------------------------------------------
-- 5. Configuración de Web Dinámica
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `ts_configuracion_web` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `clave` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Ej: sobre_nosotros_mision',
  `valor` TEXT NOT NULL,
  `tipo_dato` ENUM('TEXTO', 'IMAGEN', 'NUMERO', 'HTML') DEFAULT 'TEXTO',
  `descripcion` VARCHAR(255) NULL,
  `activo` TINYINT(1) DEFAULT 1,
  `creado_en` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `modificado_en` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado_en` DATETIME NULL
);
