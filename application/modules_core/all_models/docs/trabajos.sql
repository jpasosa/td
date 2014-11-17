--
-- 16 - Octubre - 2013 / 10.54
--

ALTER TABLE  `Trabajos` ADD  `archivo_vista_previa` VARCHAR( 200 ) NULL AFTER  `archivo_privado` ;
ALTER TABLE  `Trabajos` CHANGE  `archivo_publico`  `archivo_publico` VARCHAR( 200 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ;
ALTER TABLE  `Trabajos` CHANGE  `archivo_privado`  `archivo_privado` VARCHAR( 200 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ;


-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
------------------SUBIDO HASTA LA FECHA
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------

--
-- 25 - Septiembre - 2013 / 10.59
--


ALTER TABLE  `Trabajos` CHANGE  `idEstados`  `idEstados` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT  '1' COMMENT  '1. Pendiente | 2.Aprobado | 3. Inactivo';


--
-- 19 - Septiembre - 2013 / 11.08
--

ALTER TABLE  `Trabajos` ADD  `visitas` INT( 11 ) NULL COMMENT  'Contador de Visitas de la publicación.';
ALTER TABLE  `Trabajos` CHANGE  `visitas`  `visitas` INT( 11 ) NULL DEFAULT  '0' COMMENT  'Contador de Visitas de la publicación.';

--
-- 18 - Septiembre - 2013 / 10.56
--



ALTER TABLE  `Trabajos` CHANGE  `indice`  `indice` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ;