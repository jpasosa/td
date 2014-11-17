
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
------------------SUBIDO HASTA LA FECHA
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------

--
-- 19 - Septiembre - 2013 / 14.45
--
ALTER TABLE  `Compras` CHANGE  `monto_por_venta`  `monto_venta_total` DECIMAL( 6, 2 ) NULL DEFAULT NULL COMMENT  'El monto de la compra';

ALTER TABLE  `Compras` ADD  `modalidad` INT( 1 ) NULL COMMENT  '0:Sin cesión de derechos  |  1:Con cesión derechos';

-- phpMyAdmin SQL Dump
-- version 4.0.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 17, 2013 at 10:43 AM
-- Server version: 5.5.32
-- PHP Version: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `TextoDigitales`
--

-- --------------------------------------------------------

--
-- Table structure for table `Compras`
--

CREATE TABLE IF NOT EXISTS `Compras` (
  `idCompras` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `idUsuarios` int(11) DEFAULT NULL COMMENT 'Es el Usuario que está comprando',
  `idTrabajos` int(11) DEFAULT NULL,
  `monto_por_venta` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`idCompras`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;