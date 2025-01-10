-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-10-2020 a las 19:48:53
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
-- create table Eventos(
--     idEvento int not null PRIMARY key,
--     nombreEvento varchar (100) not null,
--     fechaEvento date not null,
--     lugarEvento varchar(100) not null
-- )
--
-- Base de datos: `sistemapersonal`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

-- CREATE TABLE Usuarios (
--     idUsuario INT(11) NOT NULL AUTO_INCREMENT,
--     nombre VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
--     email VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
--     direccion VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
--     telefono BIGINT(20) NOT NULL,
--     password VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
--     fechaNac DATE NOT NULL,
--     PRIMARY KEY (idUsuario)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO Usuarios (nombre, email, direccion, telefono, password, fechaNac) VALUES
('Juan Pérez', 'juan.perez@example.com', 'Calle Falsa 123, Ciudad', 5551234567, 'password123', '1985-04-10'),
('María López', 'maria.lopez@example.com', 'Av. Siempre Viva 742, Ciudad', 5559876543, 'mypass456', '1990-07-22'),
('Carlos García', 'carlos.garcia@example.com', 'Av. Libertad 500, Ciudad', 5555555555, 'secure789', '1978-12-05'),
('Ana Torres', 'ana.torres@example.com', 'Calle del Sol 22, Ciudad', 5551237890, 'torresAna1', '1995-03-15'),
('Luis Martínez', 'luis.martinez@example.com', 'Av. del Parque 100, Ciudad', 5554561234, 'martinez2023', '1982-08-30');

INSERT INTO Eventos (nombreEvento, fechaEvento, lugarEvento) VALUES
('Concierto de Rock', '2024-05-15', 'Estadio Nacional'),
('Feria de Libros', '2024-06-20', 'Centro de Convenciones'),
('Conferencia de Tecnología', '2024-09-10', 'Auditorio Central'),
('Festival de Cine', '2024-11-05', 'Teatro Principal'),
('Exposición de Arte', '2024-12-12', 'Galería de Arte Moderna');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `personas`
--
-- ALTER TABLE `usuarios`
--   ADD PRIMARY KEY (`idPersona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE 'Eventos'
  MODIFY 'idEvento' int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
-- ALTER TABLE `Usuarios`
--   MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
-- COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
