-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-09-2026 a las 13:40:59
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rannkor`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('activo','pendiente','finalizado','cancelado') NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`id_carrito`, `fecha_creacion`, `estado`, `id_usuario`) VALUES
(4, '2025-12-07 21:27:31', 'finalizado', 4),
(5, '2025-12-07 21:51:38', 'finalizado', 4),
(6, '2025-12-09 09:39:53', 'finalizado', 4),
(10, '2026-09-13 15:06:49', 'finalizado', 4),
(11, '2026-09-15 11:08:00', 'activo', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_detalle`
--

CREATE TABLE `carrito_detalle` (
  `id_carritodetalle` int(11) NOT NULL,
  `id_carrito` int(11) NOT NULL,
  `id_productostalla` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion`
--

CREATE TABLE `direccion` (
  `id_direccion` int(11) NOT NULL,
  `calle` varchar(50) NOT NULL,
  `numero` int(11) NOT NULL,
  `bloque` varchar(11) DEFAULT NULL,
  `planta` varchar(11) DEFAULT NULL,
  `puerta` varchar(10) DEFAULT NULL,
  `ciudad` varchar(50) NOT NULL,
  `codigo_postal` int(11) NOT NULL,
  `provincia` varchar(50) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direccion`
--

INSERT INTO `direccion` (`id_direccion`, `calle`, `numero`, `bloque`, `planta`, `puerta`, `ciudad`, `codigo_postal`, `provincia`, `id_usuario`) VALUES
(7, 'Goya', 20, '0', '1', 'B', 'Fuente Palmera', 14700, 'Córdoba', 4),
(11, 'Las Flores', 2, '', '2', 'A', 'Fuente Palmera', 14500, 'Córdoba', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_direccion` int(11) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `fecha_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado_pedido` enum('pagado') NOT NULL,
  `tipo_envio` enum('estandar','urgente') NOT NULL,
  `gastos_envio` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `codigo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_usuario`, `id_direccion`, `metodo_pago`, `fecha_pedido`, `estado_pedido`, `tipo_envio`, `gastos_envio`, `total`, `codigo`) VALUES
(4, 4, 7, 'visa', '2025-12-07 21:29:05', 'pagado', 'urgente', 10.00, 110.00, 'PED-6935F1A1EDF93'),
(8, 4, 11, 'visa', '2026-09-13 14:46:54', 'pagado', 'estandar', 5.00, 55.00, 'PED-6AA6B75E055BB'),
(9, 4, 11, 'paypal', '2026-09-15 11:04:12', 'pagado', 'urgente', 10.00, 60.00, 'PED-6AA9262C184DA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_detalle`
--

CREATE TABLE `pedidos_detalle` (
  `id_pedidodetalle` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `talla` int(10) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos_detalle`
--

INSERT INTO `pedidos_detalle` (`id_pedidodetalle`, `id_pedido`, `id_producto`, `talla`, `cantidad`, `precio_unitario`) VALUES
(6, 4, 2, 36, 1, 50.00),
(7, 4, 16, 44, 1, 50.00),
(13, 8, 1, 37, 1, 50.00),
(14, 9, 4, 39, 1, 50.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria` enum('mujer','hombre') NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen_url` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `descripcion`, `categoria`, `precio`, `imagen_url`) VALUES
(1, 'Lux', 'Optima tibi exopto', 'mujer', 50.00, '1mm.webp'),
(2, 'Lux', 'Optima tibi exopto', 'mujer', 50.00, '2mm.webp'),
(3, 'Lux', 'Optima tibi exopto', 'mujer', 50.00, '3mm.webp'),
(4, 'Lux', 'Optima tibi exopto', 'mujer', 50.00, '4mm.webp'),
(5, 'Lux', 'Optima tibi exopto', 'mujer', 50.00, '5mm.webp'),
(6, 'Lux', 'Optima tibi exopto', 'mujer', 50.00, '6mm.webp'),
(7, 'Lux', 'Optima tibi exopto', 'mujer', 50.00, '7mm.webp'),
(8, 'Lux', 'Optima tibi exopto', 'mujer', 50.00, '8mm.webp'),
(9, 'Lux', 'Optima tibi exopto', 'mujer', 50.00, '9mm.webp'),
(10, 'Lux', 'Optima tibi exopto', 'mujer', 50.00, '10mm.webp'),
(11, 'Lux', 'Optima tibi exopto', 'mujer', 50.00, '11mm.webp'),
(12, 'Lux', 'Optima tibi exopto', 'mujer', 50.00, '12mm.webp'),
(13, 'Lux', 'Optima tibi exopto', 'hombre', 50.00, '1hh.webp'),
(14, 'Lux', 'Optima tibi exopto', 'hombre', 50.00, '2hh.webp'),
(15, 'Lux', 'Optima tibi exopto', 'hombre', 50.00, '3hh.webp'),
(16, 'Lux', 'Optima tibi exopto', 'hombre', 50.00, '4hh.webp'),
(17, 'Lux', 'Optima tibi exopto', 'hombre', 50.00, '5hh.webp'),
(18, 'Lux', 'Optima tibi exopto', 'hombre', 50.00, '6hh.webp'),
(19, 'Lux', 'Optima tibi exopto', 'hombre', 50.00, '7hh.webp'),
(20, 'Lux', 'Optima tibi exopto', 'hombre', 50.00, '8hh.webp'),
(21, 'Lux', 'Optima tibi exopto', 'hombre', 50.00, '9hh.webp'),
(22, 'Lux', 'Optima tibi exopto', 'hombre', 50.00, '10hh.webp'),
(23, 'Lux', 'Optima tibi exopto', 'hombre', 50.00, '11hh.webp'),
(24, 'Lux', 'Optima tibi exopto', 'hombre', 50.00, '12hh.webp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_talla`
--

CREATE TABLE `productos_talla` (
  `id_productostalla` int(11) NOT NULL,
  `id_talla` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `stock` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_talla`
--

INSERT INTO `productos_talla` (`id_productostalla`, `id_talla`, `id_producto`, `stock`) VALUES
(1, 110, 1, 10),
(2, 111, 1, 9),
(3, 112, 1, 5),
(4, 113, 1, 7),
(5, 110, 2, 10),
(6, 111, 2, 10),
(7, 112, 2, 7),
(8, 114, 2, 5),
(9, 110, 3, 0),
(10, 111, 3, 10),
(11, 112, 3, 7),
(12, 113, 3, 5),
(13, 111, 4, 10),
(14, 112, 4, 9),
(15, 113, 4, 4),
(16, 114, 4, 3),
(17, 111, 5, 10),
(18, 112, 5, 7),
(19, 113, 5, 8),
(20, 110, 6, 6),
(21, 111, 6, 8),
(22, 112, 6, 10),
(23, 114, 6, 5),
(24, 111, 7, 5),
(25, 112, 7, 6),
(26, 113, 7, 3),
(27, 112, 8, 3),
(28, 113, 8, 3),
(29, 110, 9, 10),
(30, 113, 9, 5),
(31, 114, 9, 10),
(32, 112, 10, 8),
(33, 113, 10, 8),
(34, 110, 11, 5),
(35, 111, 11, 5),
(36, 113, 11, 5),
(37, 114, 11, 9),
(38, 110, 12, 10),
(39, 112, 12, 10),
(40, 113, 12, 10),
(41, 115, 13, 8),
(42, 116, 13, 8),
(43, 117, 13, 5),
(44, 116, 14, 2),
(45, 117, 14, 10),
(46, 118, 14, 7),
(47, 116, 15, 10),
(48, 117, 15, 10),
(49, 118, 15, 10),
(50, 116, 16, 10),
(51, 117, 16, 10),
(52, 118, 16, 10),
(53, 116, 17, 10),
(54, 117, 17, 10),
(55, 118, 17, 10),
(56, 116, 18, 10),
(57, 117, 18, 10),
(58, 118, 18, 10),
(59, 117, 19, 5),
(60, 118, 19, 5),
(61, 117, 20, 8),
(62, 118, 20, 8),
(63, 116, 21, 10),
(64, 118, 21, 5),
(65, 117, 22, 10),
(66, 118, 22, 6),
(67, 118, 23, 6),
(68, 116, 24, 5),
(69, 117, 24, 5),
(70, 114, 1, 0),
(71, 113, 2, 0),
(72, 114, 3, 0),
(73, 110, 4, 0),
(74, 110, 5, 0),
(75, 114, 5, 0),
(76, 113, 6, 0),
(77, 110, 7, 0),
(78, 114, 7, 0),
(79, 110, 8, 0),
(80, 111, 8, 0),
(81, 114, 8, 0),
(82, 111, 9, 0),
(83, 112, 9, 0),
(84, 110, 10, 0),
(85, 111, 10, 0),
(86, 114, 10, 0),
(87, 112, 11, 0),
(88, 111, 12, 0),
(89, 114, 12, 0),
(90, 118, 13, 0),
(91, 115, 14, 0),
(92, 115, 15, 0),
(93, 115, 16, 0),
(94, 115, 17, 0),
(95, 115, 18, 0),
(96, 115, 19, 0),
(97, 116, 19, 0),
(98, 115, 20, 0),
(99, 116, 20, 0),
(100, 115, 21, 0),
(101, 117, 21, 0),
(102, 115, 22, 0),
(103, 116, 22, 0),
(104, 115, 23, 0),
(105, 116, 23, 0),
(106, 117, 23, 7),
(107, 115, 24, 4),
(108, 118, 24, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` enum('admin','usuario') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
(1, 'admin'),
(2, 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas`
--

CREATE TABLE `tallas` (
  `id_talla` int(11) NOT NULL,
  `talla` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tallas`
--

INSERT INTO `tallas` (`id_talla`, `talla`) VALUES
(110, 36),
(111, 37),
(112, 38),
(113, 39),
(114, 40),
(115, 41),
(116, 42),
(117, 43),
(118, 44);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `mail` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellidos`, `telefono`, `mail`, `password`, `fecha_registro`, `id_rol`) VALUES
(4, 'Estibaliz', 'Flores', '600600600', 'cliente@gmail.com', '$2y$10$ilj0hQazHftKcBrOrHJEmeLR2l07kBacjh9u6lI.4RJzJoN.Z1jXC\r\n', '2025-11-17 10:37:17', 2),
(9, 'Natalia', 'PGDev', NULL, 'admin@gmail.com', '$2y$10$r5LMc1v/mGNbYknumupjd.uIYLhvHZw4NQ/EQ0UAtSQ92H.KXQiQ2', '2025-12-09 16:26:52', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `carrito_detalle`
--
ALTER TABLE `carrito_detalle`
  ADD PRIMARY KEY (`id_carritodetalle`),
  ADD KEY `id_carrito` (`id_carrito`),
  ADD KEY `id_talla` (`id_productostalla`);

--
-- Indices de la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD PRIMARY KEY (`id_direccion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_direccion` (`id_direccion`);

--
-- Indices de la tabla `pedidos_detalle`
--
ALTER TABLE `pedidos_detalle`
  ADD PRIMARY KEY (`id_pedidodetalle`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `productos_talla`
--
ALTER TABLE `productos_talla`
  ADD PRIMARY KEY (`id_productostalla`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_talla` (`id_talla`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tallas`
--
ALTER TABLE `tallas`
  ADD PRIMARY KEY (`id_talla`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `mail` (`mail`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `carrito_detalle`
--
ALTER TABLE `carrito_detalle`
  MODIFY `id_carritodetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `direccion`
--
ALTER TABLE `direccion`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `pedidos_detalle`
--
ALTER TABLE `pedidos_detalle`
  MODIFY `id_pedidodetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `productos_talla`
--
ALTER TABLE `productos_talla`
  MODIFY `id_productostalla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `fk_carrito_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `carrito_detalle`
--
ALTER TABLE `carrito_detalle`
  ADD CONSTRAINT `fk_carritodetalle_carrito` FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`id_carrito`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_carritodetalle_productostalla` FOREIGN KEY (`id_productostalla`) REFERENCES `productos_talla` (`id_productostalla`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD CONSTRAINT `fk_direccion_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_direccion` FOREIGN KEY (`id_direccion`) REFERENCES `direccion` (`id_direccion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedidos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos_detalle`
--
ALTER TABLE `pedidos_detalle`
  ADD CONSTRAINT `fk_pedidosdetalle_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedidosdetalle_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos_talla`
--
ALTER TABLE `productos_talla`
  ADD CONSTRAINT `fk_productos_talla_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_productos_talla_talla` FOREIGN KEY (`id_talla`) REFERENCES `tallas` (`id_talla`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_roles` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
