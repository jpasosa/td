

-- Miércoles 3 de diciembre
-- Agrego para que introduzca nivel en la aplicación.

ALTER TABLE  `Trabajos` ADD  `nivel` ENUM(  'Secundario',  'Terciario',  'Profesional',  'Universitario',  'Otro' ) NULL DEFAULT  'Otro' AFTER  `destacado`


-- Miércoles 3 de diciembre
-- Modifico campo resumen para que pueda ser nulo.

ALTER TABLE  `Trabajos` CHANGE  `resumen`  `resumen` TEXT CHARACTER
SET latin1 COLLATE latin1_swedish_ci NULL COMMENT 'resumen, es un poco más largo que la descripción breve.'