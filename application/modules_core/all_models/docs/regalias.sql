

-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
------------------SUBIDO HASTA LA FECHA
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------


--
-- 26 - Septiembre - 2013 / 14.32
--

ALTER TABLE  `Regalias` CHANGE  `estado_regalias`  `estado_regalias` INT( 2 ) NULL DEFAULT NULL COMMENT '0. no recibió regalias | 1. regalías confirmadas  |  2.Regalías ya pagadas';



--
-- 24 - Septiembre - 2013 / 09.46
--

ALTER TABLE  `Regalias` CHANGE  `estado`  `estado_regalias` INT( 2 ) NULL DEFAULT NULL COMMENT  '0. no recibió regalias | 1. regalías confirmadas';

--
-- 24 - Septiembre - 2013 / 09.11
--

ALTER TABLE  `Regalias` ADD  `monto_de_venta` FLOAT( 6, 2 ) NULL COMMENT  'Monto Real que se vendió la publicación.' AFTER  `monto_al_autor` ;



--
-- 20 - Septiembre - 2013 / 13.18
--

ALTER TABLE  `Regalias` CHANGE  `idCompras`  `idPedidos` INT( 11 ) NULL DEFAULT NULL ;

--
-- 19 - Septiembre - 2013 / 14.45
--

ALTER TABLE  `Regalias` CHANGE  `monto_por_venta`  `monto_al_autor` DECIMAL( 6, 2 ) NULL DEFAULT NULL ;

ALTER TABLE  `Regalias` ADD  `notificado` INT( 1 ) NULL COMMENT  '0: No fue notificado :: 1: Ye está notificado';



-- phpMyAdmin SQL Dump
-- version 4.0.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 17, 2013 at 10:44 AM
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
-- Table structure for table `Regalias`
--

CREATE TABLE IF NOT EXISTS `Regalias` (
  `idRegalias` int(11) NOT NULL AUTO_INCREMENT,
  `idCompras` int(11) DEFAULT NULL,
  `idUsuarios` int(11) DEFAULT NULL COMMENT 'El usuario al que se le dá la regalia',
  `monto_por_venta` decimal(6,2) DEFAULT NULL,
  `estado` int(2) DEFAULT NULL COMMENT '1. acumulandose regalias  /  2. ya pasó el monto de las regalías acumuladas  /  3.ya cobró por esas regalías.',
  PRIMARY KEY (`idRegalias`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;