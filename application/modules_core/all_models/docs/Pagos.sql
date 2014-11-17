
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
------------------SUBIDO HASTA LA FECHA
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------


--
-- 26 - Septiembre - 2013 / 10.28
--


-- phpMyAdmin SQL Dump
-- version 4.0.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 26, 2013 at 10:27 AM
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
-- Table structure for table `Pagos`
--

CREATE TABLE IF NOT EXISTS `Pagos` (
  `idPagos` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuarios` int(11) DEFAULT NULL,
  `monto_total` float(6,2) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  PRIMARY KEY (`idPagos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;