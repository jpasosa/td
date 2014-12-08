-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 08-12-2014 a las 16:24:58
-- Versión del servidor: 5.0.45
-- Versión de PHP: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `wordrevdb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Categorias`
--

CREATE TABLE IF NOT EXISTS `Categorias` (
  `idCategorias` int(10) unsigned NOT NULL auto_increment,
  `nombreCategoria` varchar(45) NOT NULL,
  `parentId` int(10) unsigned NOT NULL default '0',
  `imagen` varchar(300) default NULL,
  PRIMARY KEY  (`idCategorias`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

--
-- Volcado de datos para la tabla `Categorias`
--

INSERT INTO `Categorias` (`idCategorias`, `nombreCategoria`, `parentId`, `imagen`) VALUES
(1, 'Antiguedades y Coleccionables', 0, 'antiguedades_y_coleccionables.jpg'),
(2, 'Arquitectura', 0, 'architectura.jpg'),
(3, 'Arte', 0, 'arte.jpg'),
(4, 'Cuerpo, Mente y Espíritu', 0, 'cuerpo_mente_espiritu.jpg'),
(5, 'Negocios y Economia', 0, 'negocios_economia.jpg'),
(6, 'Calendarios', 0, 'calendarios.jpg'),
(7, 'Ficción para Niños', 0, 'ficcion_para_ninos.jpg'),
(8, 'Niños y Jóvenes', 0, 'ninos_y_jovenes.jpg'),
(9, 'Comics y Novelas Gráficas', 0, 'comics_y_novelas_graficas.jpg'),
(10, 'Computación e Internet', 0, 'computacion_e_internet.jpg'),
(11, 'Cocina, Comida y Vinos', 0, 'cocina_comida_vinos.jpg'),
(12, 'Manualidades y Hobbies', 0, 'manualidades_y_hobbies.jpg'),
(13, 'Educación', 0, 'educacion.jpg'),
(14, 'Familia y Relaciones', 0, 'familia_y_relaciones.jpg'),
(15, 'Estudio de Lenguas Extranjeras', 0, 'estudio_de_lenguas_extranjeras.jpg'),
(16, 'Juegos', 0, 'juegos.jpg'),
(17, 'Jardinería', 0, 'jardineria.jpg'),
(18, 'Salud y Bienestar', 0, 'salud_y_bienestar.jpg'),
(19, 'Historia y Geografia', 0, 'historia_y_geografia.jpg'),
(20, 'Casa y Hogar', 0, 'casa_y_hogar.jpg'),
(21, 'Humor', 0, 'humor.jpg'),
(23, 'Juridicos y Leyes', 0, 'juridicos y leyes.jpg'),
(24, 'Ficción', 25, ''),
(25, 'Lengua y Literatura', 0, ''),
(30, 'Colecciones Literarias', 25, ''),
(31, 'Crítica Literaria  y Reseñas', 25, ''),
(32, 'Filosofía', 25, ''),
(33, 'Poesía', 25, ''),
(34, 'Diccionarios y Referencia', 25, ''),
(35, 'Religión y Espiritualidad', 0, 'religion_y_espiritualidad.jpg'),
(36, 'Matemáticas', 0, 'matematicas.jpg'),
(37, 'Medicina', 0, 'medicina.jpg'),
(38, 'Música', 0, 'musica.jpg'),
(39, 'Naturaleza y Aire libre', 0, 'naturaleza_aire_libre.jpg'),
(40, 'Artes Escénicas', 0, 'artes_escenicas.jpg'),
(41, 'Mascotas y Animales', 0, 'mascotas_y_animales.jpg'),
(42, 'Fotografía', 0, 'fotografia.jpg'),
(43, 'Erotismo', 0, 'erotismo.jpg'),
(44, 'Ciencia', 0, 'ciencia.jpg'),
(45, 'Autoayuda', 0, 'autoayuda.jpg'),
(46, 'Ciencias Sociales', 44, ''),
(47, 'Ciencias Políticas', 44, ''),
(48, 'Deportes y Recreación', 0, 'deportes_y_recreacion.jpg'),
(49, 'Tecnología', 0, 'tecnologia.jpg'),
(50, 'Transporte', 0, 'transporte.jpg'),
(51, 'Viajes', 0, 'viajes.jpg'),
(52, 'Crímenes Verdaderos  y Policiales', 25, ''),
(53, 'Drama', 40, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EstadosTrabajos`
--

CREATE TABLE IF NOT EXISTS `EstadosTrabajos` (
  `idEstados` smallint(5) unsigned NOT NULL auto_increment,
  `estado` varchar(45) default NULL,
  PRIMARY KEY  (`idEstados`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `EstadosTrabajos`
--

INSERT INTO `EstadosTrabajos` (`idEstados`, `estado`) VALUES
(1, 'Pendiente'),
(2, 'Aprobado'),
(3, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Favoritos`
--

CREATE TABLE IF NOT EXISTS `Favoritos` (
  `idFavoritos` int(10) NOT NULL auto_increment,
  `idTrabajos` int(10) unsigned NOT NULL,
  `idUsuarios` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idFavoritos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Imagenes`
--

CREATE TABLE IF NOT EXISTS `Imagenes` (
  `idImagenes` int(10) unsigned NOT NULL auto_increment,
  `idTrabajos` int(10) unsigned NOT NULL,
  `archivo` varchar(45) NOT NULL,
  PRIMARY KEY  (`idImagenes`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Pagos`
--

CREATE TABLE IF NOT EXISTS `Pagos` (
  `idPagos` int(11) NOT NULL auto_increment,
  `idUsuarios` int(11) default NULL,
  `monto_total` float(6,2) default NULL,
  `fecha_pago` date default NULL,
  PRIMARY KEY  (`idPagos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PagosRegalias`
--

CREATE TABLE IF NOT EXISTS `PagosRegalias` (
  `idPagosRegalias` int(11) NOT NULL auto_increment,
  `idPagos` int(11) default NULL,
  `idRegalias` int(11) default NULL,
  PRIMARY KEY  (`idPagosRegalias`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Pedidos`
--

CREATE TABLE IF NOT EXISTS `Pedidos` (
  `idPedidos` int(11) NOT NULL auto_increment,
  `fecha` date default NULL,
  `idUsuarios` int(11) default NULL COMMENT 'Es el Usuario que está comprando',
  `idTrabajos` int(11) default NULL,
  `monto_venta_total` decimal(6,2) default NULL COMMENT 'El monto de la compra',
  `modalidad` int(1) default NULL COMMENT '0:Sin cesión de derechos  |  1:Con cesión derechos',
  `entregado` int(1) NOT NULL default '0',
  `cant_bajadas` int(1) NOT NULL default '0',
  PRIMARY KEY  (`idPedidos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Permisos`
--

CREATE TABLE IF NOT EXISTS `Permisos` (
  `idPermisos` int(11) NOT NULL auto_increment,
  `idRoles` int(11) NOT NULL,
  `descripcion` varchar(250) NOT NULL default '',
  `valor` varchar(100) NOT NULL,
  `key` varchar(100) NOT NULL,
  PRIMARY KEY  (`idPermisos`,`idRoles`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Precios`
--

CREATE TABLE IF NOT EXISTS `Precios` (
  `idPrecios` int(10) unsigned NOT NULL auto_increment,
  `precio` varchar(256) default '0.00',
  PRIMARY KEY  (`idPrecios`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `Precios`
--

INSERT INTO `Precios` (`idPrecios`, `precio`) VALUES
(1, 'entre 100 y 200'),
(2, 'entre 300 y 501'),
(3, 'entre 700 y 901'),
(4, 'entre 1000 y 1300'),
(5, 'entre 1300 y 1500'),
(6, 'entre 1500 y 1900'),
(7, 'entre 1900 y 2500');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Regalias`
--

CREATE TABLE IF NOT EXISTS `Regalias` (
  `idRegalias` int(11) NOT NULL auto_increment,
  `fecha` date default NULL,
  `idPedidos` int(11) default NULL,
  `idUsuarios` int(11) default NULL COMMENT 'El usuario al que se le dá la regalia',
  `monto_al_autor` decimal(6,2) default NULL,
  `monto_de_venta` float(6,2) default NULL COMMENT 'Monto Real que se vendió la publicación.',
  `estado_regalias` int(2) default NULL COMMENT '0. no recibió regalias | 1. regalías confirmadas  |  2.Regalías ya pagadas',
  `notificado` int(1) default NULL COMMENT '0: No fue notificado :: 1: Ye está notificado',
  PRIMARY KEY  (`idRegalias`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Roles`
--

CREATE TABLE IF NOT EXISTS `Roles` (
  `idRoles` int(11) NOT NULL auto_increment,
  `descripcion` varchar(100) NOT NULL,
  `key` varchar(50) NOT NULL,
  PRIMARY KEY  (`idRoles`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `Roles`
--

INSERT INTO `Roles` (`idRoles`, `descripcion`, `key`) VALUES
(1, 'Administrador', 'administrador'),
(2, 'Usuario', 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Sesiones`
--

CREATE TABLE IF NOT EXISTS `Sesiones` (
  `session_id` varchar(40) NOT NULL default '0',
  `ip_address` varchar(45) NOT NULL default '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL default '0',
  `user_data` text NOT NULL,
  PRIMARY KEY  (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `Sesiones`
--

INSERT INTO `Sesiones` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('7ac3d1c8da6036e8cfa47b9ff7f2ca5a', '190.244.131.30', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36', 1374238718, 'a:7:{s:9:"user_data";s:0:"";s:10:"idUsuarios";s:1:"4";s:8:"userName";s:18:"admin@allytech.com";s:6:"rolKey";s:13:"administrador";s:7:"idRoles";s:1:"1";s:8:"permisos";a:20:{s:10:"idUsuarios";s:1:"4";s:7:"idRoles";s:1:"1";s:6:"nombre";s:11:"Juan Carlos";s:8:"apellido";s:5:"Lopez";s:5:"email";s:18:"admin@allytech.com";s:5:"clave";s:41:"*A4B6157319038724E3560894F7F932C8886EBFCF";s:8:"telefono";s:4:"2312";s:6:"estado";s:1:"1";s:11:"esEditorial";s:1:"0";s:6:"avatar";s:36:"bbec1fc8b0f9eee3cca7ebe2221b8fc0.jpg";s:8:"regalias";s:1:"0";s:7:"esAutor";s:1:"0";s:5:"fecha";N;s:9:"intereses";s:0:"";s:5:"lugar";s:0:"";s:9:"profesion";N;s:9:"biografia";s:0:"";s:6:"rolKey";s:13:"administrador";s:14:"rolDescripcion";s:13:"Administrador";s:8:"permisos";a:0:{}}s:4:"foto";N;}'),
('7b99d5ec1d75c826fd463e7b19092609', '190.244.131.30', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36', 1374187449, ''),
('cf84bf49280d7bccc769942f14a677d6', '186.108.199.116', 'Mozilla/5.0 (X11; Linux x86_64; rv:21.0) Gecko/20100101 Firefox/21.0', 1374238591, 'a:7:{s:9:"user_data";s:0:"";s:10:"idUsuarios";s:1:"4";s:8:"userName";s:18:"admin@allytech.com";s:6:"rolKey";s:13:"administrador";s:7:"idRoles";s:1:"1";s:8:"permisos";a:20:{s:10:"idUsuarios";s:1:"4";s:7:"idRoles";s:1:"1";s:6:"nombre";s:11:"Juan Carlos";s:8:"apellido";s:5:"Lopez";s:5:"email";s:18:"admin@allytech.com";s:5:"clave";s:41:"*A4B6157319038724E3560894F7F932C8886EBFCF";s:8:"telefono";s:4:"2312";s:6:"estado";s:1:"1";s:11:"esEditorial";s:1:"0";s:6:"avatar";s:36:"bbec1fc8b0f9eee3cca7ebe2221b8fc0.jpg";s:8:"regalias";s:1:"0";s:7:"esAutor";s:1:"0";s:5:"fecha";N;s:9:"intereses";s:0:"";s:5:"lugar";s:0:"";s:9:"profesion";N;s:9:"biografia";s:0:"";s:6:"rolKey";s:13:"administrador";s:14:"rolDescripcion";s:13:"Administrador";s:8:"permisos";a:0:{}}s:4:"foto";N;}'),
('396051dc6322b2265f2106820c99e9f0', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:22.0) Gecko/20100101 Firefox/22.0', 1374507361, 'a:6:{s:10:"idUsuarios";s:1:"4";s:8:"userName";s:18:"admin@allytech.com";s:6:"rolKey";s:13:"administrador";s:7:"idRoles";s:1:"1";s:8:"permisos";a:20:{s:10:"idUsuarios";s:1:"4";s:7:"idRoles";s:1:"1";s:6:"nombre";s:11:"Juan Carlos";s:8:"apellido";s:5:"Lopez";s:5:"email";s:18:"admin@allytech.com";s:5:"clave";s:41:"*A4B6157319038724E3560894F7F932C8886EBFCF";s:8:"telefono";s:4:"2312";s:6:"estado";s:1:"1";s:11:"esEditorial";s:1:"0";s:6:"avatar";s:36:"bbec1fc8b0f9eee3cca7ebe2221b8fc0.jpg";s:8:"regalias";s:1:"0";s:7:"esAutor";s:1:"0";s:5:"fecha";N;s:9:"intereses";s:0:"";s:5:"lugar";s:0:"";s:9:"profesion";N;s:9:"biografia";s:0:"";s:6:"rolKey";s:13:"administrador";s:14:"rolDescripcion";s:13:"Administrador";s:8:"permisos";a:0:{}}s:4:"foto";N;}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Trabajos`
--

CREATE TABLE IF NOT EXISTS `Trabajos` (
  `idTrabajos` int(11) NOT NULL auto_increment,
  `idUsuarios` int(10) unsigned NOT NULL,
  `idPrecios` int(10) default NULL,
  `titulo` varchar(200) NOT NULL default '',
  `texto` text NOT NULL COMMENT 'es la Descripción Breve',
  `resumen` text NOT NULL COMMENT 'resumen, es un poco más largo que la descripción breve.',
  `fecha` date NOT NULL,
  `palabrasClave` varchar(100) default '',
  `indice` longtext,
  `destacado` tinyint(1) unsigned NOT NULL default '0',
  `idEstados` smallint(5) unsigned NOT NULL default '1' COMMENT '1. Pendiente | 2.Aprobado | 3. Inactivo',
  `cantidadPalabras` smallint(6) NOT NULL default '0',
  `cantidad_paginas` smallint(6) default NULL,
  `foto` varchar(100) default '',
  `precio_sin_derecho` decimal(6,2) default '0.00',
  `precio_con_derecho` decimal(6,2) default '0.00',
  `monto_por_venta` decimal(6,2) default '0.00',
  `archivo_publico` varchar(200) default NULL,
  `archivo_privado` varchar(200) default NULL,
  `archivo_vista_previa` varchar(200) default NULL,
  `idCategorias_parentId` int(11) NOT NULL default '0',
  `notificado` int(1) default NULL,
  `visitas` int(11) default '0' COMMENT 'Contador de Visitas de la publicación.',
  PRIMARY KEY  (`idTrabajos`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `Trabajos`
--

INSERT INTO `Trabajos` (`idTrabajos`, `idUsuarios`, `idPrecios`, `titulo`, `texto`, `resumen`, `fecha`, `palabrasClave`, `indice`, `destacado`, `idEstados`, `cantidadPalabras`, `cantidad_paginas`, `foto`, `precio_sin_derecho`, `precio_con_derecho`, `monto_por_venta`, `archivo_publico`, `archivo_privado`, `archivo_vista_previa`, `idCategorias_parentId`, `notificado`, `visitas`) VALUES
(1, 4, NULL, 'libro de prueba', 'Considerando que la aplicación del derecho requiere de argumentos y éstos a su vez dependen de cuán correctamente expuestos estén, lo que demanda habilidad lingüística, oral y de redacción. Se detallan las mejores expresiones, las aplicaciones técnicas como signos matemáticos o relaciones de pesos y medidas, las abreviaturas y siglas, la acentuación correcta, un glosario terminológico e incluso aforismos latinos. No deja de lado el decálogo de los abogados y los mandamientos del mismo en relación con la ética y principios.', 'Considerando que la aplicación del derecho requiere de argumentos y éstos a su vez dependen de cuán correctamente expuestos estén, lo que demanda habilidad lingüística, oral y de redacción. Se detallan las mejores expresiones, las aplicaciones técnicas como signos matemáticos o relaciones de pesos y medidas, las abreviaturas y siglas, la acentuación correcta, un glosario terminológico e incluso aforismos latinos. No deja de lado el decálogo de los abogados y los mandamientos del mismo en relación con la ética y principios.2', '2014-08-12', '', '', 0, 2, 0, 213, '', '9.99', '0.00', '0.00', '', '', '', 1, 1, 112);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TrabajosCategorias`
--

CREATE TABLE IF NOT EXISTS `TrabajosCategorias` (
  `idTrabajos` int(10) unsigned NOT NULL,
  `idCategorias` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE IF NOT EXISTS `Usuarios` (
  `idUsuarios` int(11) unsigned NOT NULL auto_increment,
  `idRoles` int(11) NOT NULL default '2',
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `email` varchar(200) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `estado` tinyint(1) unsigned NOT NULL default '1',
  `esEditorial` tinyint(1) unsigned NOT NULL default '0',
  `avatar` varchar(300) NOT NULL default '',
  `regalias` float NOT NULL default '0',
  `esAutor` tinyint(1) unsigned NOT NULL default '0',
  `fecha` date default NULL,
  `fecha_created_at` date default NULL,
  `intereses` text NOT NULL,
  `lugar` varchar(45) default '',
  `profesion` varchar(45) default NULL,
  `biografia` text NOT NULL,
  `verificacion` varchar(100) default NULL,
  `observaciones` longtext,
  PRIMARY KEY  (`idUsuarios`,`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`idUsuarios`, `idRoles`, `nombre`, `apellido`, `email`, `clave`, `telefono`, `estado`, `esEditorial`, `avatar`, `regalias`, `esAutor`, `fecha`, `fecha_created_at`, `intereses`, `lugar`, `profesion`, `biografia`, `verificacion`, `observaciones`) VALUES
(1, 1, 'Administrador Wordrev', 'Administrador', 'admin@wordrev.com', '30f6a770331f4fc0', '', 1, 0, '', 0, 0, '1969-12-31', '2013-11-08', '', '', '', '', 'ad3d77e94a00f258f437ca26517b4702', NULL),
(2, 2, 'Juan Pablo', 'Testing', 'testing@wordrev.com', '446a12100c856ce9', '1234-1234', 1, 0, 'dd371a855d89e81c620e8b0308ebe329.jpg', 0, 1, '1969-12-31', '2013-11-13', '', '', '', '', '7ebad5f04ca8a56ad26e2367313b699a', NULL),
(3, 2, 'Jose', 'Pérez', 'sistemas@zerodigital.com.ar', '565491d704013245', '9876-6543', 1, 0, 'site_mail.jpg', 0, 0, '1996-11-01', '2013-11-14', 'Test', 'Argentina', 'Escritor', '', '35113f6cb7061f535a24f6e08af59b19', NULL),
(4, 2, 'emiliano', 'valletta', 'xxemilianoxx@gmail.com', '5dbd57353fbee199', '', 1, 0, '', 0, 1, '1969-12-31', '2014-08-12', '', 'Argentina', '', '', '7a0177296b516ed3fb05fc711c9b8830', NULL),
(5, 2, 'juan pablo', 'prueba', 'info@zerodigital.com.ar', '565491d704013245', '1234-1234', 1, 0, '', 0, 0, '1969-12-31', '2014-08-20', 'a', '', '', '', '1b41542a48a4bcf44545d5f8852f0c00', NULL),
(6, 2, 'juan pablo', 'sosa', 'juanpablososa@gmail.com', '446a12100c856ce9', '', 0, 0, '', 0, 0, '1969-12-31', '2014-10-18', '', '', '', '', '1b41542a48a4bcf44545d5f8852f0c00', NULL),
(7, 2, 'juan pablo', 'sosa', 'jpasosa@gmail.com', '446a12100c856ce9', '', 1, 0, '', 0, 0, '1969-12-31', '2014-10-18', '', '', '', '', 'cfb160bb40c6ed3aa33d8f04c168582b', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
