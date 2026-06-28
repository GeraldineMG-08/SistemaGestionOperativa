-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-06-2026 a las 05:25:49
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
-- Base de datos: `bd_hospedaje_maritimos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id_usuario` int(11) NOT NULL,
  `nivel_aprobacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id_usuario`, `nivel_aprobacion`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierre_caja`
--

CREATE TABLE `cierre_caja` (
  `id_cierre` int(11) NOT NULL,
  `id_turno` int(11) NOT NULL,
  `monto_systema` decimal(10,2) NOT NULL,
  `monto_fisico` decimal(10,2) NOT NULL,
  `diferencia` decimal(10,2) NOT NULL,
  `total_egresos` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cierre_caja`
--

INSERT INTO `cierre_caja` (`id_cierre`, `id_turno`, `monto_systema`, `monto_fisico`, `diferencia`, `total_egresos`) VALUES
(1, 1, 69.50, 69.50, 0.00, 0.00),
(2, 2, 290.00, 290.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_consumo`
--

CREATE TABLE `detalle_consumo` (
  `id_detalle` int(11) NOT NULL,
  `id_registro` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `monto_consumo_pagado` decimal(10,2) NOT NULL,
  `medio_pago_consumo` enum('Efectivo','Tarjeta','Yape') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_consumo`
--

INSERT INTO `detalle_consumo` (`id_detalle`, `id_registro`, `id_producto`, `cantidad`, `monto_consumo_pagado`, `medio_pago_consumo`) VALUES
(1, 1, 25, 2, 8.00, 'Efectivo'),
(2, 1, 37, 1, 1.50, 'Efectivo'),
(3, 2, 1, 1, 4.50, 'Tarjeta'),
(4, 2, 35, 2, 4.00, 'Tarjeta'),
(5, 3, 7, 2, 2.00, 'Yape'),
(6, 3, 31, 1, 3.50, 'Yape'),
(7, 4, 11, 1, 6.00, 'Efectivo'),
(8, 5, 43, 2, 6.00, 'Tarjeta'),
(9, 5, 27, 1, 4.00, 'Tarjeta'),
(10, 6, 38, 1, 1.50, 'Efectivo'),
(11, 7, 33, 1, 5.00, 'Yape'),
(12, 7, 18, 1, 2.00, 'Yape'),
(13, 10, 16, 1, 2.00, 'Yape'),
(14, 12, 49, 1, 1.00, 'Efectivo'),
(15, 8, 37, 2, 3.00, 'Yape'),
(16, 15, 1, 4, 18.00, 'Yape');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitacion`
--

CREATE TABLE `habitacion` (
  `id_habitacion` int(11) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `tipo` enum('Simple','Doble','Triple') NOT NULL,
  `estado` enum('Disponible','Ocupada','Limpieza') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `habitacion`
--

INSERT INTO `habitacion` (`id_habitacion`, `numero`, `tipo`, `estado`) VALUES
(1, '101', 'Simple', 'Disponible'),
(2, '102', 'Simple', 'Disponible'),
(3, '103', 'Simple', 'Limpieza'),
(4, '201', 'Simple', 'Ocupada'),
(5, '202', 'Simple', 'Disponible'),
(6, '203', 'Simple', 'Disponible'),
(7, '204', 'Simple', 'Disponible'),
(8, '205', 'Simple', 'Ocupada'),
(9, '206', 'Doble', 'Ocupada'),
(10, '207', 'Doble', 'Disponible'),
(11, '208', 'Doble', 'Disponible'),
(12, '209', 'Triple', 'Ocupada'),
(13, '301', 'Simple', 'Ocupada'),
(14, '302', 'Simple', 'Disponible'),
(15, '303', 'Simple', 'Disponible'),
(16, '304', 'Simple', 'Limpieza'),
(17, '305', 'Simple', 'Disponible'),
(18, '306', 'Doble', 'Disponible'),
(19, '307', 'Doble', 'Ocupada'),
(20, '308', 'Doble', 'Disponible'),
(21, '309', 'Triple', 'Ocupada'),
(22, '401', 'Simple', 'Disponible'),
(23, '402', 'Simple', 'Ocupada'),
(24, '403', 'Simple', 'Ocupada'),
(25, '404', 'Simple', 'Limpieza'),
(26, '405', 'Simple', 'Disponible'),
(27, '406', 'Doble', 'Disponible'),
(28, '407', 'Doble', 'Disponible'),
(29, '408', 'Doble', 'Disponible'),
(30, '409', 'Triple', 'Ocupada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `huesped`
--

CREATE TABLE `huesped` (
  `id_huesped` int(11) NOT NULL,
  `dni` varchar(15) NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `lugar_procedencia` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `huesped`
--

INSERT INTO `huesped` (`id_huesped`, `dni`, `nombre_completo`, `lugar_procedencia`) VALUES
(1, '71234561', 'Juan Carlos Perez', 'Tacna'),
(2, '71234562', 'María Quispe Flores', 'Arequipa'),
(3, '71234563', 'Luis Alberto Ramos', 'Moquegua'),
(4, '71234564', 'Rosa Mendoza Huamani', 'Puno'),
(5, '71234565', 'José Huanca Mamani', 'Cusco'),
(6, '71234566', 'Ana Torres Vargas', 'Lima'),
(7, '71234567', 'Pedro Condori Mamani', 'Juliaca'),
(8, '71234568', 'Karla Flores Diaz', 'Ilo'),
(9, '71234569', 'Miguel Rojas Soto', 'Arica'),
(10, '71234570', 'Carmen Salas Medina', 'Tacna'),
(11, '71454811', 'Luciana Flores Gonzales', 'Arequipa'),
(12, '71201530', 'Luciano Callomamani Lopez', 'Piura'),
(13, '42456617', 'Mariano Sosa Fernandez', 'Tacna'),
(14, '61002016', 'Geraldine Mamani Gomez', 'Lima'),
(15, '42417899', 'Valentino Quispe Apaza', 'Loreto'),
(16, '00254512', 'Soledad Mamani Laquiticona', 'Junin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(100) NOT NULL,
  `categoria` enum('Cuidado_Personal','Gaseosas','Golosinas') NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre_producto`, `categoria`, `precio_venta`, `stock`) VALUES
(1, 'COLGATE MAXIMA PROT.', 'Cuidado_Personal', 4.50, 15),
(2, 'CEPILLO DE DIENTES', 'Cuidado_Personal', 3.50, 20),
(3, 'AFEITADOR GRANDE', 'Cuidado_Personal', 5.00, 20),
(4, 'AFEITADOR PEQUEÑO', 'Cuidado_Personal', 2.00, 20),
(5, 'JABON NEKO', 'Cuidado_Personal', 4.00, 20),
(6, 'JABON PROTEX', 'Cuidado_Personal', 5.00, 20),
(7, 'TOALLAS NOSOTRAS', 'Cuidado_Personal', 1.00, 18),
(8, 'CREMA PONDS', 'Cuidado_Personal', 1.50, 20),
(9, 'REPELENTE', 'Cuidado_Personal', 2.50, 20),
(10, 'BLOQUEADOR', 'Cuidado_Personal', 2.50, 20),
(11, 'PRESERVATIVO', 'Cuidado_Personal', 6.00, 19),
(12, 'PEINE', 'Cuidado_Personal', 1.50, 20),
(13, 'JOHNSONS BABY', 'Cuidado_Personal', 2.00, 20),
(14, 'PANTENE', 'Cuidado_Personal', 2.00, 20),
(15, 'HEAD & SHOULDERS', 'Cuidado_Personal', 2.00, 20),
(16, 'SEDAL', 'Cuidado_Personal', 2.00, 19),
(17, 'BONAWELL', 'Cuidado_Personal', 2.00, 20),
(18, 'KONZIL', 'Cuidado_Personal', 2.00, 19),
(19, 'REXONA & NIVEA', 'Cuidado_Personal', 2.00, 20),
(20, 'LADY SPEED STICK', 'Cuidado_Personal', 1.50, 20),
(21, 'SAL DE ANDREWS', 'Cuidado_Personal', 1.50, 20),
(22, 'CUCHARA, TENEDOR', 'Cuidado_Personal', 0.50, 20),
(23, 'VASO DE PLASTICO', 'Cuidado_Personal', 0.10, 50),
(24, 'MASCARILLA', 'Cuidado_Personal', 1.00, 50),
(25, 'COCA COLA & INCA COLA', 'Gaseosas', 4.00, 28),
(26, 'FANTA & POWERADE', 'Gaseosas', 4.00, 20),
(27, 'GATORADE', 'Gaseosas', 3.50, 19),
(28, 'ELECTROLIGHT', 'Gaseosas', 3.00, 20),
(29, 'FRUGOS', 'Gaseosas', 2.00, 20),
(30, 'SAN LUIS', 'Gaseosas', 2.50, 20),
(31, 'SAN LUIS 1L', 'Gaseosas', 3.50, 19),
(32, 'PURA VIDA', 'Gaseosas', 2.00, 20),
(33, 'PURA VIDA 3L', 'Gaseosas', 4.50, 19),
(34, 'FRUGOS 1.5L', 'Gaseosas', 5.00, 20),
(35, 'POWERADE 1L', 'Gaseosas', 5.50, 18),
(36, 'COCA & INCA COLA 2.5L', 'Gaseosas', 12.00, 15),
(37, 'SODA', 'Golosinas', 1.50, 27),
(38, 'TENTACION', 'Golosinas', 1.50, 29),
(39, 'CHOCOCHIP', 'Golosinas', 1.50, 30),
(40, 'FRAC', 'Golosinas', 1.50, 30),
(41, 'RITZ', 'Golosinas', 2.00, 30),
(42, 'CHOCOSODA', 'Golosinas', 2.00, 30),
(43, 'PAPAS LAYS', 'Golosinas', 3.00, 28),
(44, 'PIQUEOS', 'Golosinas', 3.00, 30),
(45, 'OREO TUBO', 'Golosinas', 3.50, 30),
(46, 'HALLS', 'Golosinas', 1.50, 30),
(47, 'TRIDENT', 'Golosinas', 1.50, 30),
(48, 'WAFER', 'Golosinas', 3.50, 30),
(49, 'LAPICERO', 'Golosinas', 1.00, 19),
(50, 'VAPORUB', 'Golosinas', 2.50, 10),
(51, 'CHICHA', 'Golosinas', 2.00, 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recepcionista`
--

CREATE TABLE `recepcionista` (
  `id_usuario` int(11) NOT NULL,
  `codigo_empleado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `recepcionista`
--

INSERT INTO `recepcionista` (`id_usuario`, `codigo_empleado`) VALUES
(2, 'REC001');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_huesped`
--

CREATE TABLE `registro_huesped` (
  `id_registro` int(11) NOT NULL,
  `id_huesped` int(11) NOT NULL,
  `id_habitacion` int(11) NOT NULL,
  `id_tarifa` int(11) NOT NULL,
  `id_turno` int(11) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  `fecha_salida` datetime NOT NULL,
  `dias_estadia` int(11) NOT NULL,
  `monto_hospedaje_pagado` decimal(10,2) NOT NULL,
  `medio_pago` enum('Efectivo','Tarjeta','Yape') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `registro_huesped`
--

INSERT INTO `registro_huesped` (`id_registro`, `id_huesped`, `id_habitacion`, `id_tarifa`, `id_turno`, `fecha_ingreso`, `fecha_salida`, `dias_estadia`, `monto_hospedaje_pagado`, `medio_pago`) VALUES
(1, 1, 1, 1, 1, '2026-06-26 09:15:00', '2026-06-28 11:18:01', 1, 60.00, 'Efectivo'),
(2, 2, 6, 1, 2, '2026-06-27 10:00:00', '2026-06-29 12:00:00', 2, 120.00, 'Tarjeta'),
(3, 3, 7, 2, 2, '2026-06-27 18:00:00', '2026-06-29 10:00:00', 2, 160.00, 'Yape'),
(4, 4, 13, 1, 3, '2026-06-28 08:30:00', '2026-06-29 11:00:00', 1, 60.00, 'Efectivo'),
(5, 5, 19, 2, 3, '2026-06-28 11:40:00', '2026-06-30 12:00:00', 2, 160.00, 'Tarjeta'),
(6, 6, 22, 1, 3, '2026-06-28 15:00:00', '2026-06-29 10:30:00', 1, 60.00, 'Efectivo'),
(7, 7, 30, 3, 3, '2026-06-28 16:20:00', '2026-06-30 11:00:00', 2, 240.00, 'Yape'),
(8, 11, 2, 1, 3, '2026-06-30 11:21:00', '2026-06-28 17:07:30', 2, 120.00, 'Efectivo'),
(9, 12, 12, 3, 3, '2026-06-28 12:18:00', '2026-07-01 12:18:00', 3, 360.00, 'Tarjeta'),
(10, 13, 24, 1, 3, '2026-06-28 12:59:00', '2026-06-29 12:59:00', 1, 60.00, 'Efectivo'),
(11, 1, 1, 1, 3, '2026-06-28 16:37:00', '2026-06-28 16:53:24', 2, 120.00, 'Yape'),
(12, 14, 3, 1, 3, '2026-06-28 16:39:00', '2026-06-28 16:53:21', 1, 60.00, 'Tarjeta'),
(13, 15, 4, 1, 3, '2026-06-28 20:52:00', '2026-07-03 20:52:00', 5, 310.00, 'Yape'),
(14, 15, 3, 1, 3, '2026-06-30 16:51:00', '2026-07-01 16:51:00', 1, 60.00, 'Tarjeta'),
(15, 16, 21, 3, 3, '2026-06-28 17:03:00', '2026-06-30 17:03:00', 2, 260.00, 'Yape');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifa`
--

CREATE TABLE `tarifa` (
  `id_tarifa` int(11) NOT NULL,
  `tipo_habitacion` enum('Simple','Doble','Triple') NOT NULL,
  `precio_base` decimal(10,2) NOT NULL,
  `recargo_anticipado` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tarifa`
--

INSERT INTO `tarifa` (`id_tarifa`, `tipo_habitacion`, `precio_base`, `recargo_anticipado`) VALUES
(1, 'Simple', 60.00, 0.00),
(2, 'Doble', 80.00, 0.00),
(3, 'Triple', 120.00, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE `turno` (
  `id_turno` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_apertura` time NOT NULL,
  `hora_cierre` time DEFAULT NULL,
  `saldo_inicial` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`id_turno`, `id_usuario`, `fecha`, `hora_apertura`, `hora_cierre`, `saldo_inicial`) VALUES
(1, 2, '2026-06-26', '08:00:00', '20:00:00', 300.00),
(2, 2, '2026-06-27', '08:00:00', '20:00:00', 250.00),
(3, 2, '2026-06-28', '08:00:00', NULL, 350.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` enum('Administradora','Recepcionista') NOT NULL,
  `estado` enum('Activo','Inactivo','Suspendido') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre_completo`, `usuario`, `clave`, `rol`, `estado`) VALUES
(1, 'Victoria Ceferina Aguilar', 'vaguilar', '1234', 'Administradora', 'Activo'),
(2, 'Adriana Contreras Quispe', 'acontreras', '1234', 'Recepcionista', 'Activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `cierre_caja`
--
ALTER TABLE `cierre_caja`
  ADD PRIMARY KEY (`id_cierre`),
  ADD UNIQUE KEY `uq_cierre_turno` (`id_turno`);

--
-- Indices de la tabla `detalle_consumo`
--
ALTER TABLE `detalle_consumo`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `fk_detalle_registro` (`id_registro`),
  ADD KEY `fk_detalle_producto` (`id_producto`);

--
-- Indices de la tabla `habitacion`
--
ALTER TABLE `habitacion`
  ADD PRIMARY KEY (`id_habitacion`),
  ADD UNIQUE KEY `uq_numero_habitacion` (`numero`);

--
-- Indices de la tabla `huesped`
--
ALTER TABLE `huesped`
  ADD PRIMARY KEY (`id_huesped`),
  ADD UNIQUE KEY `uq_dni_huesped` (`dni`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `recepcionista`
--
ALTER TABLE `recepcionista`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `uq_codigo_empleado` (`codigo_empleado`);

--
-- Indices de la tabla `registro_huesped`
--
ALTER TABLE `registro_huesped`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `fk_registro_huesped` (`id_huesped`),
  ADD KEY `fk_registro_habitacion` (`id_habitacion`),
  ADD KEY `fk_registro_turno` (`id_turno`),
  ADD KEY `fk_registro_tarifa` (`id_tarifa`);

--
-- Indices de la tabla `tarifa`
--
ALTER TABLE `tarifa`
  ADD PRIMARY KEY (`id_tarifa`),
  ADD UNIQUE KEY `uq_tipo_tarifa` (`tipo_habitacion`);

--
-- Indices de la tabla `turno`
--
ALTER TABLE `turno`
  ADD PRIMARY KEY (`id_turno`),
  ADD KEY `fk_turno_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `uq_usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cierre_caja`
--
ALTER TABLE `cierre_caja`
  MODIFY `id_cierre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_consumo`
--
ALTER TABLE `detalle_consumo`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `habitacion`
--
ALTER TABLE `habitacion`
  MODIFY `id_habitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `huesped`
--
ALTER TABLE `huesped`
  MODIFY `id_huesped` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `registro_huesped`
--
ALTER TABLE `registro_huesped`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `tarifa`
--
ALTER TABLE `tarifa`
  MODIFY `id_tarifa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `turno`
--
ALTER TABLE `turno`
  MODIFY `id_turno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `fk_administrador_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cierre_caja`
--
ALTER TABLE `cierre_caja`
  ADD CONSTRAINT `fk_cierre_turno` FOREIGN KEY (`id_turno`) REFERENCES `turno` (`id_turno`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_consumo`
--
ALTER TABLE `detalle_consumo`
  ADD CONSTRAINT `fk_detalle_producto` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_registro` FOREIGN KEY (`id_registro`) REFERENCES `registro_huesped` (`id_registro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `recepcionista`
--
ALTER TABLE `recepcionista`
  ADD CONSTRAINT `fk_recepcionista_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `registro_huesped`
--
ALTER TABLE `registro_huesped`
  ADD CONSTRAINT `fk_registro_habitacion` FOREIGN KEY (`id_habitacion`) REFERENCES `habitacion` (`id_habitacion`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_registro_huesped` FOREIGN KEY (`id_huesped`) REFERENCES `huesped` (`id_huesped`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_registro_tarifa` FOREIGN KEY (`id_tarifa`) REFERENCES `tarifa` (`id_tarifa`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_registro_turno` FOREIGN KEY (`id_turno`) REFERENCES `turno` (`id_turno`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `turno`
--
ALTER TABLE `turno`
  ADD CONSTRAINT `fk_turno_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
