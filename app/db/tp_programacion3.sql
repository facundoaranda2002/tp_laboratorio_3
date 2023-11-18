-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-11-2023 a las 04:27:14
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tp_programacion3`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `estado` varchar(200) DEFAULT NULL,
  `idMesa` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`estado`, `idMesa`) VALUES
('Cerrado', 1),
('cerrada', 3),
('pepe', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `idMesa` bigint(20) DEFAULT NULL,
  `estado` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `nombreCliente` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `puntuacion` int(2) DEFAULT NULL,
  `comentarios` varchar(66) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `clavePedido` char(20) NOT NULL,
  `idPedido` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`idMesa`, `estado`, `nombreCliente`, `precio`, `puntuacion`, `comentarios`, `clavePedido`, `idPedido`) VALUES
(1, 'finalizado', 'Lucas', 101, 8, 'Muy Bueno', 'L7PR6', 1),
(3, 'finalizado', 'Juan', 900, 9, 'Excelente', '5JP2P', 2),
(5, 'pendiente', 'Juan', 900, 0, 'Comentario Pendiente', 'GBYO9', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `tiempo` float DEFAULT NULL,
  `sector` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `nombre` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `clavePedido` char(20) NOT NULL,
  `idUsuario` int(20) DEFAULT NULL,
  `idProducto` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`estado`, `tiempo`, `sector`, `nombre`, `clavePedido`, `idUsuario`, `idProducto`) VALUES
('finalizado', 10, 'bartender', 'cuba libre', 'L7PR6', 5, 3),
('finalizado', 0, 'cocinero', 'milanesa de caballo', '5JP2P', 4, 4),
('finalizado', 0, 'cocinero', '2 hamburguesas de garbanzo', '5JP2P', 4, 5),
('finalizado', 0, 'bartender', 'corona', '5JP2P', 5, 6),
('finalizado', 0, 'bartender', 'daikiri', '5JP2P', 5, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `sueldo` float DEFAULT NULL,
  `sector` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `fechaIngreso` date DEFAULT NULL,
  `nombreUsuario` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `password` varchar(10) NOT NULL,
  `idUsuario` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`sueldo`, `sector`, `fechaIngreso`, `nombreUsuario`, `password`, `idUsuario`) VALUES
(5000, 'socio', '2023-05-10', 'Pedro Rodriguez', '12345', 1),
(700, 'mozo', '2023-11-16', 'Lucas Ramos', '5678', 3),
(800, 'cocinero', '2023-05-10', 'Rodrigo Lopez', '789', 4),
(800, 'bartender', '2023-11-16', 'Esteban Fox', '1010', 5),
(800, 'mozo', '2023-11-17', 'Lucas Alon', '1111', 6);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`idMesa`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idPedido`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProducto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `idMesa` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPedido` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProducto` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
