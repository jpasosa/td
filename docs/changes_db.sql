




-- Lunes 08 de diciembre del 2014
-- Agrego tabla Formas de Pago.
CREATE TABLE IF NOT EXISTS `FormasPago` (
  `idFormasPago` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(300) NOT NULL,
  PRIMARY KEY (`idFormasPago`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE  `Usuarios` ADD  `idFormasPago` INT( 10 ) NULL AFTER  `idRoles`;


-- Lunes 08 de diciembre del 2014
-- Agrego campos de dirección para que el administrador sepa donde entregar.
ALTER TABLE  `Usuarios` ADD  `direccion_calle` VARCHAR( 300 ) NULL AFTER  `clave` ,
ADD  `direccion_numero` VARCHAR( 300 ) NULL AFTER  `direccion_calle` ,
ADD  `cod_postal` VARCHAR( 300 ) NULL AFTER  `direccion_numero` ,
ADD  `localidad` VARCHAR( 300 ) NULL AFTER  `cod_postal` ,
ADD  `ciudad` VARCHAR( 300 ) NULL AFTER  `localidad` ,
ADD  `pais` VARCHAR( 300 ) NULL AFTER  `ciudad`;


-- Lunes 08 de diciembre del 2014
-- Agrego nombre a mostrar para visualizar en la parte pública de la web.
ALTER TABLE  `Usuarios` ADD  `nombre_mostrar` VARCHAR( 300 ) NULL AFTER  `email`;


-- Miércoles 3 de diciembre
-- Agrego para que introduzca nivel en la aplicación.

ALTER TABLE  `Trabajos` ADD  `nivel` ENUM(  'Secundario',  'Terciario',  'Profesional',  'Universitario',  'Otro' ) NULL DEFAULT  'Otro' AFTER  `destacado`;


-- Miércoles 3 de diciembre
-- Modifico campo resumen para que pueda ser nulo.

ALTER TABLE  `Trabajos` CHANGE  `resumen`  `resumen` TEXT CHARACTER
SET latin1 COLLATE latin1_swedish_ci NULL COMMENT 'resumen, es un poco más largo que la descripción breve.';