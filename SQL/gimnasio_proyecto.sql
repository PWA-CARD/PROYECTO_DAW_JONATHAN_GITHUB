-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-12-2025 a las 21:52:24
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
-- Base de datos: `gimnasio_proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrativo`
--

CREATE TABLE `administrativo` (
  `idEmpleado` int(10) UNSIGNED NOT NULL,
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `salario` decimal(6,2) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clase`
--

CREATE TABLE `clase` (
  `idClase` int(11) NOT NULL,
  `nombreClase` varchar(20) NOT NULL,
  `duracion` smallint(6) NOT NULL,
  `capacidad` smallint(6) DEFAULT NULL CHECK (`capacidad` > 0 and `capacidad` <= 40),
  `idEmpleado` int(10) UNSIGNED NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clase`
--

INSERT INTO `clase` (`idClase`, `nombreClase`, `duracion`, `capacidad`, `idEmpleado`, `activo`, `fechaBaja`) VALUES
(1, 'Spinning', 45, 30, 1, 1, NULL),
(2, 'Yoga', 60, 20, 2, 1, NULL),
(3, 'Boxeo', 50, 25, 3, 1, NULL),
(4, 'CrossFit', 55, 20, 4, 1, NULL),
(5, 'Pilates', 60, 15, 5, 1, NULL),
(6, 'Zumba', 40, 30, 6, 1, NULL),
(7, 'TRX', 45, 20, 7, 1, NULL),
(8, 'BodyPump', 50, 25, 8, 1, NULL),
(9, 'Funcional', 55, 25, 9, 1, NULL),
(10, 'Cardio', 40, 35, 10, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idCliente` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `telefono` varchar(12) NOT NULL,
  `fechaNacimiento` date NOT NULL,
  `eMail` varchar(64) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idCliente`, `nombre`, `telefono`, `fechaNacimiento`, `eMail`, `activo`, `fechaBaja`) VALUES
(1, 'María Sánchez Pérez', '+34699999999', '2000-01-01', 'maria@gmail.com', 1, NULL),
(2, 'Jorge López García', '+34612345678', '1998-03-05', 'jorge@gmail.com', 1, NULL),
(3, 'Claudia Torres Díaz', '+34623456789', '1995-07-10', 'claudia@gmail.com', 1, NULL),
(4, 'Andrés Ruiz Mora', '+34634567890', '2001-11-12', 'andres@gmail.com', 1, NULL),
(5, 'Lucía Vidal León', '+34645678901', '1999-09-25', 'lucia@gmail.com', 1, NULL),
(6, 'Manuel Pérez Ramos', '+34656789012', '1996-05-17', 'manuel@gmail.com', 1, NULL),
(7, 'Paula Gómez Alba', '+34667890123', '1997-02-08', 'paula@gmail.com', 1, NULL),
(8, 'Antonio Díaz Ruiz', '+34678901234', '2000-06-20', 'antonio@gmail.com', 1, NULL),
(9, 'Sara García Torres', '+34689012345', '1998-12-30', 'sara@gmail.com', 1, NULL),
(10, 'Miguel Hernández López', '+34690123456', '1995-01-15', 'miguel@gmail.com', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenador`
--

CREATE TABLE `entrenador` (
  `idEmpleado` int(10) UNSIGNED NOT NULL,
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `salario` decimal(6,2) NOT NULL,
  `anoExperiencia` smallint(6) NOT NULL,
  `especialidad` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `entrenador`
--

INSERT INTO `entrenador` (`idEmpleado`, `dni`, `nombre`, `fechaNacimiento`, `salario`, `anoExperiencia`, `especialidad`, `activo`, `fechaBaja`) VALUES
(1, '12345678F', 'Francisco Robledo Tristán', '1999-12-12', 1800.00, 12, 'Spinning', 1, NULL),
(2, '22345678G', 'Ana Beltrán Ruiz', '1995-08-15', 1750.00, 10, 'Yoga', 1, NULL),
(3, '32345678H', 'Javier Molina Torres', '1990-06-25', 1900.00, 15, 'Boxeo', 1, NULL),
(4, '42345678J', 'Sara Gómez Pérez', '1997-03-10', 1600.00, 8, 'CrossFit', 1, NULL),
(5, '52345678K', 'Pedro López Díaz', '1994-09-17', 1850.00, 11, 'Pilates', 1, NULL),
(6, '62345678L', 'Elena Márquez Mora', '1993-12-19', 1700.00, 9, 'Zumba', 1, NULL),
(7, '72345678M', 'José Vidal Ruiz', '1992-05-23', 1950.00, 14, 'TRX', 1, NULL),
(8, '82345678N', 'Laura Pérez García', '1998-04-30', 1780.00, 7, 'BodyPump', 1, NULL),
(9, '92345678P', 'Sergio García León', '1996-10-01', 1825.00, 10, 'Funcional', 1, NULL),
(10, '03345678Q', 'Carmen Torres Alba', '1991-07-21', 1880.00, 13, 'Cardio', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenadorespecialidad`
--

CREATE TABLE `entrenadorespecialidad` (
  `especialidad` varchar(50) NOT NULL,
  `idEmpleado` int(10) UNSIGNED NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `idEquipo` int(10) UNSIGNED NOT NULL,
  `nombreEquipo` varchar(40) NOT NULL,
  `fechaAdquisicion` date NOT NULL,
  `estado` enum('funcional','mantenimiento') DEFAULT NULL,
  `idClase` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion`
--

CREATE TABLE `inscripcion` (
  `fechaInscripcion` date NOT NULL,
  `idCliente` int(10) UNSIGNED NOT NULL,
  `idClase` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inscripcion`
--

INSERT INTO `inscripcion` (`fechaInscripcion`, `idCliente`, `idClase`, `activo`, `fechaBaja`) VALUES
('2024-01-02', 1, 1, 1, NULL),
('2024-01-03', 1, 6, 1, NULL),
('2024-01-04', 2, 2, 1, NULL),
('2024-01-05', 2, 4, 1, NULL),
('2024-01-06', 3, 3, 1, NULL),
('2024-01-07', 3, 7, 1, NULL),
('2024-01-08', 4, 5, 1, NULL),
('2024-01-09', 4, 9, 1, NULL),
('2024-01-10', 5, 8, 1, NULL),
('2024-01-11', 5, 10, 1, NULL),
('2024-01-12', 6, 1, 1, NULL),
('2024-01-13', 7, 2, 1, NULL),
('2024-01-14', 8, 3, 1, NULL),
('2024-01-15', 9, 4, 1, NULL),
('2024-01-16', 10, 5, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `idPago` int(11) NOT NULL,
  `servicio` enum('membresia mensual','clase individual','entrenamiento personal') DEFAULT NULL,
  `cantidad` decimal(5,2) NOT NULL CHECK (`cantidad` > 0),
  `fechaPago` date NOT NULL,
  `tipoPago` enum('efectivo','tarjeta','bizum') DEFAULT NULL,
  `idCliente` int(10) UNSIGNED NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`idPago`, `servicio`, `cantidad`, `fechaPago`, `tipoPago`, `idCliente`, `activo`, `fechaBaja`) VALUES
(1, 'membresia mensual', 45.00, '2024-01-02', 'tarjeta', 1, 1, NULL),
(2, 'membresia mensual', 45.00, '2024-01-03', 'tarjeta', 2, 1, NULL),
(3, 'clase individual', 12.00, '2024-01-05', 'efectivo', 3, 1, NULL),
(4, 'entrenamiento personal', 30.00, '2024-01-07', 'bizum', 4, 1, NULL),
(5, 'clase individual', 12.00, '2024-01-08', 'bizum', 5, 1, NULL),
(6, 'membresia mensual', 45.00, '2024-01-09', 'tarjeta', 6, 1, NULL),
(7, 'membresia mensual', 45.00, '2024-01-10', 'tarjeta', 7, 1, NULL),
(8, 'clase individual', 12.00, '2024-01-11', 'efectivo', 8, 1, NULL),
(9, 'entrenamiento personal', 30.00, '2024-01-12', 'bizum', 9, 1, NULL),
(10, 'membresia mensual', 45.00, '2024-01-13', 'tarjeta', 10, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recepcionista`
--

CREATE TABLE `recepcionista` (
  `idEmpleado` int(10) UNSIGNED NOT NULL,
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `salario` decimal(6,2) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','editor','usuario') NOT NULL DEFAULT 'usuario',
  `avatar` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `nombre`, `email`, `password`, `rol`, `avatar`, `activo`, `fechaBaja`) VALUES
(1, 'Administrador', 'admin@gimnasio.com', '1234', 'admin', NULL, 1, NULL),
(5, 'Editor', 'editor@gimnasio.com', '1234', 'editor', NULL, 1, NULL),
(6, 'Usuario demo', 'user@gimnasio.com', '1234', 'usuario', NULL, 1, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrativo`
--
ALTER TABLE `administrativo`
  ADD PRIMARY KEY (`idEmpleado`),
  ADD UNIQUE KEY `uk_administrativo_dni` (`dni`);

--
-- Indices de la tabla `clase`
--
ALTER TABLE `clase`
  ADD PRIMARY KEY (`idClase`),
  ADD KEY `idx_clase_entrenador` (`idEmpleado`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idCliente`);

--
-- Indices de la tabla `entrenador`
--
ALTER TABLE `entrenador`
  ADD PRIMARY KEY (`idEmpleado`),
  ADD UNIQUE KEY `uk_entrenador_dni` (`dni`);

--
-- Indices de la tabla `entrenadorespecialidad`
--
ALTER TABLE `entrenadorespecialidad`
  ADD PRIMARY KEY (`especialidad`,`idEmpleado`),
  ADD KEY `idx_especialidad_entrenador` (`idEmpleado`);

--
-- Indices de la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`idEquipo`),
  ADD KEY `idx_equipo_clase` (`idClase`);

--
-- Indices de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD PRIMARY KEY (`idCliente`,`idClase`,`fechaInscripcion`),
  ADD KEY `idx_inscripcion_clase` (`idClase`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`idPago`),
  ADD KEY `idx_pago_cliente` (`idCliente`);

--
-- Indices de la tabla `recepcionista`
--
ALTER TABLE `recepcionista`
  ADD PRIMARY KEY (`idEmpleado`),
  ADD UNIQUE KEY `uk_recepcionista_dni` (`dni`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrativo`
--
ALTER TABLE `administrativo`
  MODIFY `idEmpleado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clase`
--
ALTER TABLE `clase`
  MODIFY `idClase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idCliente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `entrenador`
--
ALTER TABLE `entrenador`
  MODIFY `idEmpleado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `idEquipo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `idPago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `recepcionista`
--
ALTER TABLE `recepcionista`
  MODIFY `idEmpleado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clase`
--
ALTER TABLE `clase`
  ADD CONSTRAINT `fk_clase_entrenador` FOREIGN KEY (`idEmpleado`) REFERENCES `entrenador` (`idEmpleado`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `entrenadorespecialidad`
--
ALTER TABLE `entrenadorespecialidad`
  ADD CONSTRAINT `fk_especialidad_entrenador` FOREIGN KEY (`idEmpleado`) REFERENCES `entrenador` (`idEmpleado`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD CONSTRAINT `fk_equipo_clase` FOREIGN KEY (`idClase`) REFERENCES `clase` (`idClase`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD CONSTRAINT `fk_inscripcion_clase` FOREIGN KEY (`idClase`) REFERENCES `clase` (`idClase`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inscripcion_cliente` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `fk_pago_cliente` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
