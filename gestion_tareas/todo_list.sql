-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-04-2026 a las 11:45:46
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
-- Base de datos: `todo_list`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `valor` text NOT NULL,
  `aux1` varchar(50) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `clave`, `valor`, `aux1`) VALUES
(1, 'webhook_url', 'https://webhook.site/tu-codigo-aqui', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea_data`
--

CREATE TABLE `tarea_data` (
  `id` int(11) NOT NULL,
  `Fecha_creacion` date DEFAULT NULL,
  `Fecha_vencimiento` date DEFAULT NULL,
  `Estado` varchar(20) DEFAULT NULL,
  `aux1` varchar(50) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarea_data`
--

INSERT INTO `tarea_data` (`id`, `Fecha_creacion`, `Fecha_vencimiento`, `Estado`, `aux1`) VALUES
(22, '2026-04-28', NULL, 'en_progreso', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea_dataexten`
--

CREATE TABLE `tarea_dataexten` (
  `id` int(11) NOT NULL,
  `Tarea_id` int(11) DEFAULT NULL,
  `Titulo` varchar(255) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `aux1` varchar(50) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarea_dataexten`
--

INSERT INTO `tarea_dataexten` (`id`, `Tarea_id`, `Titulo`, `Descripcion`, `aux1`) VALUES
(22, 22, NULL, 'ergqer', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tarea_data`
--
ALTER TABLE `tarea_data`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tarea_dataexten`
--
ALTER TABLE `tarea_dataexten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Tarea_id` (`Tarea_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tarea_data`
--
ALTER TABLE `tarea_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `tarea_dataexten`
--
ALTER TABLE `tarea_dataexten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tarea_dataexten`
--
ALTER TABLE `tarea_dataexten`
  ADD CONSTRAINT `tarea_dataexten_ibfk_1` FOREIGN KEY (`Tarea_id`) REFERENCES `tarea_data` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
