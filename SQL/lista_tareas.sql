-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-02-2025 a las 13:47:06
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
-- Base de datos: `lista_tareas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `ID` int(11) NOT NULL,
  `nombretarea` varchar(255) NOT NULL,
  `fechainicio` date NOT NULL,
  `fechafin` date NOT NULL,
  `prioridad` enum('baja','media','alta','inmediata') NOT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `completada` tinyint(1) DEFAULT 0,
  `favorito` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`ID`, `nombretarea`, `fechainicio`, `fechafin`, `prioridad`, `idusuario`, `completada`, `favorito`) VALUES
(7, 'tarea', '2025-02-26', '2025-02-28', 'media', 13, 0, 0),
(8, 'tarea2', '2025-02-26', '2025-03-28', 'alta', 13, 0, 0),
(23, '3', '2025-02-22', '2027-01-27', 'inmediata', 10, 0, 0),
(25, 'Agregar a', '2025-02-26', '2025-02-28', 'media', 11, 0, 0),
(28, 'Prueba', '2025-02-26', '2025-03-01', 'baja', 10, 0, 0),
(31, 'Tarea Prueba', '2025-02-27', '2025-03-01', 'inmediata', 11, 0, 0),
(37, 'a', '2025-02-27', '2025-02-28', 'baja', 16, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombreusuario` varchar(50) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombreusuario`, `correo`, `contrasena`) VALUES
(10, 'ernestoo', 'ernessttooo16@gmail.com', '1234'),
(11, 'admin', 'admin@gmail.com', 'admin'),
(12, 'admin2', 'admin2@gmail.com', '1234'),
(13, 'ernesto', 'ernesto@gmail.com', '1234'),
(14, 'admin3', 'admin3@gmail.com', 'admin'),
(15, 'admim', 'admina@gmail.com', '12344'),
(16, 'a', 'a@gmail.com', '1234');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`nombreusuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
