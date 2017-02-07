-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2016 at 11:21 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sipre`
--
CREATE DATABASE IF NOT EXISTS `sipre` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `sipre`;

-- --------------------------------------------------------

--
-- Table structure for table `centros`
--

CREATE TABLE `centros` (
  `id_centro` int(10) UNSIGNED NOT NULL,
  `cent_nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `cent_fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `centros`
--

INSERT INTO `centros` (`id_centro`, `cent_nombre`, `cent_fecha_reg`) VALUES
(1, 'E.B. TAGUANES', '2016-12-14 22:45:15'),
(2, 'E.B. MANUELITA SAENZ', '2016-12-14 22:45:50'),
(3, 'C.D. MARIANO FERNANDEZ FORTIQUE', '2016-12-14 22:45:50'),
(4, 'C.D. RAFAEL HERNANDEZ LEON', '2016-12-19 14:58:50'),
(5, 'U.E. SANTISIMO SALVADOR', '2016-12-19 14:58:50'),
(6, 'E.B. LUCIO ANTONIO FIGUERA', '2016-12-19 14:59:19'),
(7, 'E.B. MEREGOTO', '2016-12-19 14:59:19'),
(8, 'E.B. ANDRES BELLO', '2016-12-19 14:59:36'),
(9, 'E.B. ALIDA PEREZ MATOS', '2016-12-19 14:59:36'),
(10, 'U.E. PRIVADA CECILIO ACOSTA', '2016-12-19 15:00:13'),
(11, 'E.B. LUIS ALEJANDRO ALVARADO', '2016-12-19 15:00:13'),
(12, 'E.B. CESAR ZUMETA', '2016-12-19 15:00:33'),
(13, 'COLEGIO EL CARMELO', '2016-12-19 15:00:33'),
(14, 'E.B. AMELIA MIRANDA ORTA', '2016-12-19 15:00:48'),
(15, 'U.E.N. SOTERO ARTEAGA MIGUELENA', '2016-12-19 15:00:48'),
(16, 'INSTITUTO TECNOLOGICO PASCAL', '2016-12-19 15:01:00'),
(17, 'U.E. COLEGIO ARAGUA ESTUDIANTIL', '2016-12-19 15:01:00'),
(18, 'U.E. JOSE HELIMENAS BARRIOS – FUNDACIÓN', '2016-12-19 15:01:15'),
(19, 'U.E. JOSE HELIMENAS BARRIOS – CENTRO', '2016-12-19 15:01:15'),
(20, 'E.B. NACIONAL FELIPE LARRAZABAL', '2016-12-19 15:01:30'),
(21, 'U.E. ESTADAL PADRE FRANCISCO AMIGO', '2016-12-19 15:01:30'),
(22, 'U.E.N. RAFAEL BOLÍVAR', '2016-12-19 15:01:46'),
(23, 'U.E. AMPARO MONROY POWER', '2016-12-19 15:01:46'),
(24, 'U.E.N. SUCRE', '2016-12-19 15:01:58'),
(25, 'U.E.N. MANUEL MANZO GORESTEGUI', '2016-12-19 15:01:58'),
(26, 'P.N.B. ANTONIO JOSE DE SUCRE', '2016-12-19 15:02:11'),
(27, 'PREESC. LUIS ALEJANDRO ALVARADO', '2016-12-19 15:02:11'),
(28, 'CENTRO MOVIL CANCHA RAFAEL URDANETA', '2016-12-19 15:02:24'),
(29, 'E.B. CIRO MALDONADO ZERPA', '2016-12-19 15:02:24'),
(30, 'U.E.N. CREACION BELLA VISTA', '2016-12-19 15:02:43'),
(31, 'P.N.B. BELLA VISTA II', '2016-12-19 15:02:43'),
(32, 'NUESTRA SEÑORA DEL VALLE', '2016-12-19 15:02:52');

-- --------------------------------------------------------

--
-- Table structure for table `electores`
--

CREATE TABLE `electores` (
  `id_elector` int(10) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL COMMENT 'Usuario que registro al elector',
  `elec_eliminado` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `elec_nombres` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `elec_apellidos` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `elec_cedula` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `elec_email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `elec_sexo` tinytext COLLATE utf8_spanish_ci NOT NULL,
  `elec_telefono` varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  `elec_nacimiento` date NOT NULL,
  `elec_profesion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `id_sector` mediumint(10) UNSIGNED NOT NULL,
  `id_sh` int(10) UNSIGNED NOT NULL,
  `elec_direccion` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `id_centro` mediumint(10) UNSIGNED NOT NULL,
  `elec_facebook` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `elec_twitter` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `elec_instagram` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `elec_fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `electores`
--

INSERT INTO `electores` (`id_elector`, `id_user`, `elec_eliminado`, `elec_nombres`, `elec_apellidos`, `elec_cedula`, `elec_email`, `elec_sexo`, `elec_telefono`, `elec_nacimiento`, `elec_profesion`, `id_sector`, `id_sh`, `elec_direccion`, `id_centro`, `elec_facebook`, `elec_twitter`, `elec_instagram`, `elec_fecha_reg`) VALUES
(1, 1, 0, 'Orlando Jose', 'Perez Malave', '20336372', 'Orlandoj_315@hotmail.com', 'M', '04121766533', '1992-11-05', 'Ingeniero', 8, 28, 'Ciudad Jardin', 17, 'Orlando315', 'Orlando315', 'Orlando315', '2016-12-15 17:28:52'),
(5, 1, 0, 'Carolina', 'Olivares', '1111111111', 'Orlando@p4d.comv.e', 'F', '04214353535', '1980-03-21', 'Educador', 2, 2, 'asascascasc', 2, NULL, NULL, 'Carola', '2016-12-16 21:45:56'),
(6, 1, 0, 'Pedro', 'Perez', '23123123', 'Prueba@sipre.com.ve', 'M', '04121213123', '1985-12-05', 'Albañil', 1, 1, 'Ninguna', 1, NULL, NULL, NULL, '2016-12-17 20:28:24');

-- --------------------------------------------------------

--
-- Table structure for table `sectores`
--

CREATE TABLE `sectores` (
  `id_sector` int(10) UNSIGNED NOT NULL,
  `sect_nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `sect_fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Sectores de viviendeas';

--
-- Dumping data for table `sectores`
--

INSERT INTO `sectores` (`id_sector`, `sect_nombre`, `sect_fecha_reg`) VALUES
(1, 'SECTOR 1', '2016-12-14 22:47:10'),
(2, 'SECTOR 2', '2016-12-14 22:47:10'),
(3, 'SECTOR 3', '2016-12-19 14:56:41'),
(4, 'SECTOR 4', '2016-12-19 14:56:41'),
(5, 'SECTOR 5', '2016-12-19 15:07:30'),
(6, 'SECTOR 6', '2016-12-19 15:07:30'),
(7, 'SECTOR 7', '2016-12-19 15:08:22'),
(8, 'SECTOR 8', '2016-12-19 15:08:39');

-- --------------------------------------------------------

--
-- Table structure for table `sectores_hijos`
--

CREATE TABLE `sectores_hijos` (
  `id_sh` int(11) UNSIGNED NOT NULL,
  `id_sector` int(11) UNSIGNED NOT NULL,
  `sh_nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `sh_fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `sectores_hijos`
--

INSERT INTO `sectores_hijos` (`id_sh`, `id_sector`, `sh_nombre`, `sh_fecha_reg`) VALUES
(1, 1, 'FUNDA CAGUA', '2016-12-14 22:50:30'),
(2, 2, 'RAFAEL URDANETA', '2016-12-14 22:50:30'),
(5, 1, 'SANTA ROSALIA', '2016-12-19 15:03:28'),
(6, 1, 'RESIDENCIA CODAZZI', '2016-12-19 15:03:28'),
(7, 2, 'EL GUARIL', '2016-12-19 15:04:18'),
(8, 2, 'GRAN MARISCAL', '2016-12-19 15:04:18'),
(9, 3, 'LA HACIENDITA', '2016-12-19 15:04:55'),
(10, 3, 'EL SAMAN', '2016-12-19 15:04:55'),
(11, 4, 'TAMBORITO', '2016-12-19 15:05:16'),
(12, 4, 'GUILLEN', '2016-12-19 15:05:16'),
(13, 4, 'LAS VEGAS', '2016-12-19 15:05:48'),
(14, 4, 'LOS COCOS', '2016-12-19 15:05:48'),
(15, 5, 'LA DEMOCRACIA', '2016-12-19 15:06:09'),
(16, 5, 'BARRIO BOLIVAR', '2016-12-19 15:06:09'),
(17, 5, 'ROMULO GALLEGOS', '2016-12-19 15:06:27'),
(18, 5, 'LA CARPIERA', '2016-12-19 15:06:27'),
(19, 6, 'BELLA VISTA', '2016-12-19 15:07:52'),
(20, 7, 'FUNDACIÓN', '2016-12-19 15:09:23'),
(21, 7, '12 DE OCTUBRE', '2016-12-19 15:09:23'),
(22, 7, 'ANDRES BELLO', '2016-12-19 15:09:42'),
(23, 7, 'EL LECHOZAL', '2016-12-19 15:09:42'),
(24, 8, 'ALTOS DE KORINSA', '2016-12-19 15:10:03'),
(25, 8, 'CORINSA', '2016-12-19 15:10:03'),
(26, 8, 'CORINSA COLONIAL', '2016-12-19 15:10:22'),
(27, 8, 'PRADOS DE LA ENCRUCIJADA', '2016-12-19 15:10:22'),
(28, 8, 'CIUDAD JARDIN', '2016-12-19 15:10:37'),
(29, 1, 'BARRANCON', '2016-12-19 15:11:37'),
(30, 1, 'RESIDENCIAS NATALY', '2016-12-19 15:11:37'),
(31, 1, 'AGUIRRE', '2016-12-19 15:11:56'),
(32, 1, 'CASCO CENTRAL', '2016-12-19 15:11:56'),
(33, 1, 'MEREGOTO', '2016-12-19 15:12:13'),
(34, 1, 'CRUZ VERDE', '2016-12-19 15:12:13'),
(35, 1, 'CANTARRANA', '2016-12-19 15:12:34'),
(36, 3, 'LA TRINIDAD', '2016-12-19 15:13:28'),
(37, 3, 'LA EXCLUSIVA', '2016-12-19 15:13:28'),
(38, 3, 'EL BOSQUE', '2016-12-19 15:13:48'),
(39, 3, 'LOS LIRIOS', '2016-12-19 15:13:48'),
(40, 3, 'YARAVI', '2016-12-19 15:14:00'),
(41, 4, 'MANUELITA SAENZ', '2016-12-19 15:14:37'),
(42, 4, 'SAN JOSE', '2016-12-19 15:14:37'),
(43, 4, 'JESUS DE NAZARETH', '2016-12-19 15:14:54'),
(44, 4, 'SANTA EDUVIGES', '2016-12-19 15:14:54'),
(45, 4, 'AUTOCONSTRUCCION LAS VEGAS', '2016-12-19 15:15:12'),
(46, 4, 'ANTONIO JOSE DE SUCRE', '2016-12-19 15:15:12'),
(47, 4, 'MARIA ANGELICA LUSINCHI', '2016-12-19 15:15:36'),
(48, 5, 'LA CANDELARIA', '2016-12-19 15:16:11'),
(49, 5, 'LIBERTADOR', '2016-12-19 15:16:11'),
(50, 5, 'LOS MANGOS', '2016-12-19 15:16:32'),
(51, 5, 'LA CIUDADELA', '2016-12-19 15:16:32'),
(52, 5, 'PRADOS DE ARAGUA', '2016-12-19 15:16:50'),
(53, 5, 'COROZAL', '2016-12-19 15:16:50'),
(54, 7, 'LA COMUNA HUGO CHAVEZ', '2016-12-19 15:17:39'),
(55, 7, 'LA COMUNA AGRICOLA HUGO CHAVEZ', '2016-12-19 15:17:39'),
(56, 7, 'ALI PRIMERA', '2016-12-19 15:18:04'),
(57, 7, 'HUETE', '2016-12-19 15:18:04'),
(58, 7, 'CAMPO ALEGRE', '2016-12-19 15:18:22'),
(59, 7, 'BELLA CAGUA', '2016-12-19 15:18:22');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `user_eliminado` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `user_nivel` varchar(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'U',
  `user_estado` varchar(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'I',
  `user_nombres` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `user_apellidos` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `user_cedula` varchar(9) COLLATE utf8_spanish_ci NOT NULL,
  `user_email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `user_pass` varchar(75) COLLATE utf8_spanish_ci NOT NULL,
  `user_sexo` tinytext COLLATE utf8_spanish_ci NOT NULL,
  `user_telefono` varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  `user_recovery` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `user_fecha_reg` date NOT NULL,
  `user_hora_reg` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_user`, `user_eliminado`, `user_nivel`, `user_estado`, `user_nombres`, `user_apellidos`, `user_cedula`, `user_email`, `user_pass`, `user_sexo`, `user_telefono`, `user_recovery`, `user_fecha_reg`, `user_hora_reg`) VALUES
(1, 0, 'A', 'A', 'Orlando', 'Perez', '20336372', 'orlando@p4d.com.ve', '$2y$10$pDinWl1DsxdpjxqfuiRsvOYZQ4AKpMoTZQW9olQgERXePNhJPJrMC', 'M', '32342342342', '', '0000-00-00', '00:00:00'),
(11, 1, 'C', 'I', 'Colaborado', 'P1', '212312312', 'Orlando@sipre.com', '$2y$10$F8r83vjXI8Sji19HODdtGeGGrZaEsstHGVNzMoy3lUykzqe7vMSju', 'M', '12345567891', NULL, '2016-12-14', '15:10:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `centros`
--
ALTER TABLE `centros`
  ADD PRIMARY KEY (`id_centro`);

--
-- Indexes for table `electores`
--
ALTER TABLE `electores`
  ADD PRIMARY KEY (`id_elector`);

--
-- Indexes for table `sectores`
--
ALTER TABLE `sectores`
  ADD PRIMARY KEY (`id_sector`);

--
-- Indexes for table `sectores_hijos`
--
ALTER TABLE `sectores_hijos`
  ADD PRIMARY KEY (`id_sh`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `centros`
--
ALTER TABLE `centros`
  MODIFY `id_centro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `electores`
--
ALTER TABLE `electores`
  MODIFY `id_elector` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `sectores`
--
ALTER TABLE `sectores`
  MODIFY `id_sector` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `sectores_hijos`
--
ALTER TABLE `sectores_hijos`
  MODIFY `id_sh` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
