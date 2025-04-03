-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-04-2025 a las 00:08:29
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
-- Base de datos: `login_system`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informes`
--

CREATE TABLE `informes` (
  `id` int(11) NOT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `resultado` varchar(255) DEFAULT NULL,
  `medida_inicial` decimal(5,2) DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `medida_final` decimal(5,2) DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `imagen1` varchar(255) DEFAULT NULL,
  `imagen2` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('en_espera','aprobado','no_aprobado') DEFAULT 'en_espera',
  `correcciones` text DEFAULT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `informes`:
--   `servicio_id`
--       `servicios` -> `id`
--   `created_by`
--       `usuarios` -> `id`
--

--
-- Volcado de datos para la tabla `informes`
--

INSERT INTO `informes` (`id`, `servicio_id`, `fecha_inicio`, `observaciones`, `resultado`, `medida_inicial`, `hora_inicio`, `medida_final`, `hora_fin`, `imagen1`, `imagen2`, `created_at`, `estado`, `correcciones`, `created_by`) VALUES
(1, 1, '2025-03-27', 'Se daño iudnisbd', 'Efectivo', 23.00, '14:40:00', 26.00, '15:40:00', 'uploads/servicio.jpg', 'uploads/img.jpg', '2025-03-27 19:41:04', 'en_espera', NULL, 1),
(2, 1, '2025-03-27', 's', '1', 1.00, '18:19:00', 11.00, '19:20:00', 'uploads/img.jpg', NULL, '2025-03-27 20:16:42', 'no_aprobado', 'Se necesitan mas evidencias. ', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `solicitud_no` varchar(50) DEFAULT NULL,
  `tipo_servicio` varchar(100) DEFAULT NULL,
  `razon_social` varchar(100) DEFAULT NULL,
  `establecimiento` varchar(100) DEFAULT NULL,
  `nit` varchar(20) DEFAULT NULL,
  `material` varchar(50) DEFAULT NULL,
  `tipo_tanque` varchar(50) DEFAULT NULL,
  `capacidad` int(11) DEFAULT NULL,
  `producto` varchar(50) DEFAULT NULL,
  `ano_fabricacion` int(11) DEFAULT NULL,
  `sucursal` varchar(100) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `servicios`:
--

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `solicitud_no`, `tipo_servicio`, `razon_social`, `establecimiento`, `nit`, `material`, `tipo_tanque`, `capacidad`, `producto`, `ano_fabricacion`, `sucursal`, `usuario`, `fecha_inicio`, `created_by`, `created_at`) VALUES
(1, '1', 'Prueba de tanques', 'alsn', 'FET', '1234467', 'tubos ', 'Superficial', 7, 'acero', 2005, 'rivera', 'Andrea10', NULL, 2, '2025-03-21 14:24:55'),
(2, '2', 'Prueba funcionamiento ', 'ewrwert', 'Postobon SAS', '7980', 'tubos ', 'Subterráneo', 8, 'acero', 2004, 'rivera', 'Andrea10', NULL, 2, '2025-03-21 14:49:01'),
(3, '5', 'Prueba funcionamiento ', 'alsn', 'Coca Cola', '12345678', 'tubos', 'Superficial', 34, 'ksbfisu', 2005, 'neiva', 'sebas3', NULL, 2, '2025-03-27 20:51:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('administrador','tecnico') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `usuarios`:
--

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_completo`, `email`, `telefono`, `username`, `password`, `rol`, `created_at`) VALUES
(1, 'Andrea Narvaez Trujillo', 'andreatr0620@gmail.com', '3167073346', 'Andrea10', '12345', 'tecnico', '2025-03-20 21:46:58'),
(2, 'Carlos Rubio', 'carlos_rubio@gmail.com', '3214567', 'carlos1', '12345', 'administrador', '2025-03-20 21:52:07'),
(3, 'Sebastian', 'sebas@gmail.com', '3167073346', 'sebas3', '4321', 'tecnico', '2025-03-27 20:49:22'),
(5, 'Andrea Narvaez Trujillo', 'azucena_tus36@hotmail.com', '3167073346', 'andy', '1234', 'tecnico', '2025-03-27 22:46:47');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `informes`
--
ALTER TABLE `informes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `servicio_id` (`servicio_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `informes`
--
ALTER TABLE `informes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `informes`
--
ALTER TABLE `informes`
  ADD CONSTRAINT `informes_ibfk_1` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`),
  ADD CONSTRAINT `informes_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
