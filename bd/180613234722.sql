/*
MySQL Backup
Source Server Version: 8.0.11
Source Database: cfit
Date: 13/06/2018 23:47:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `clases`
-- ----------------------------
DROP TABLE IF EXISTS `clases`;
CREATE TABLE `clases` (
  `cod_clase` decimal(10,0) NOT NULL,
  `cod_disciplina` decimal(10,0) DEFAULT NULL,
  `cod_personal` decimal(10,0) DEFAULT NULL,
  `cod_sala` decimal(10,0) DEFAULT NULL,
  `aforo` decimal(10,0) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora_ini` varchar(5) DEFAULT NULL,
  `hora_fin` varchar(5) DEFAULT NULL,
  `repetir` varchar(1) DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  PRIMARY KEY (`cod_clase`),
  KEY `cod_disciplina` (`cod_disciplina`),
  KEY `cod_personal` (`cod_personal`),
  KEY `cod_sala` (`cod_sala`),
  CONSTRAINT `clases_ibfk_1` FOREIGN KEY (`cod_disciplina`) REFERENCES `disciplina` (`cod_disciplina`),
  CONSTRAINT `clases_ibfk_2` FOREIGN KEY (`cod_personal`) REFERENCES `personal` (`cod_personal`),
  CONSTRAINT `clases_ibfk_3` FOREIGN KEY (`cod_sala`) REFERENCES `sala` (`cod_sala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `clase_cliente`
-- ----------------------------
DROP TABLE IF EXISTS `clase_cliente`;
CREATE TABLE `clase_cliente` (
  `cod_clase` decimal(10,0) NOT NULL,
  `cod_cliente` decimal(10,0) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`cod_clase`,`cod_cliente`,`fecha`),
  KEY `cod_cliente` (`cod_cliente`),
  CONSTRAINT `clase_cliente_ibfk_1` FOREIGN KEY (`cod_clase`) REFERENCES `clases` (`cod_clase`),
  CONSTRAINT `clase_cliente_ibfk_2` FOREIGN KEY (`cod_cliente`) REFERENCES `cliente` (`cod_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `clase_repetir`
-- ----------------------------
DROP TABLE IF EXISTS `clase_repetir`;
CREATE TABLE `clase_repetir` (
  `cod_clase` decimal(10,0) NOT NULL,
  `cod_dia` decimal(10,0) NOT NULL,
  PRIMARY KEY (`cod_clase`,`cod_dia`),
  KEY `cod_dia` (`cod_dia`),
  CONSTRAINT `clase_repetir_ibfk_1` FOREIGN KEY (`cod_clase`) REFERENCES `clases` (`cod_clase`),
  CONSTRAINT `clase_repetir_ibfk_2` FOREIGN KEY (`cod_dia`) REFERENCES `dias` (`cod_dia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `cliente`
-- ----------------------------
DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `cod_cliente` decimal(10,0) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `appat` varchar(100) DEFAULT NULL,
  `apmat` varchar(100) DEFAULT NULL,
  `dni` decimal(8,0) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `cod_dist` decimal(10,0) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `contac_emerg` varchar(100) DEFAULT NULL,
  `telefono_emerg` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`cod_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `comprobante`
-- ----------------------------
DROP TABLE IF EXISTS `comprobante`;
CREATE TABLE `comprobante` (
  `cod_comprobante` decimal(10,0) NOT NULL,
  `nom_comprobante` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`cod_comprobante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `dias`
-- ----------------------------
DROP TABLE IF EXISTS `dias`;
CREATE TABLE `dias` (
  `cod_dia` decimal(10,0) NOT NULL,
  `nom_dia` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`cod_dia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `disciplina`
-- ----------------------------
DROP TABLE IF EXISTS `disciplina`;
CREATE TABLE `disciplina` (
  `cod_disciplina` decimal(10,0) NOT NULL,
  `nom_disciplina` varchar(255) DEFAULT NULL,
  `color` varchar(8) DEFAULT NULL,
  `des_disciplina` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`cod_disciplina`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `metodo_pago`
-- ----------------------------
DROP TABLE IF EXISTS `metodo_pago`;
CREATE TABLE `metodo_pago` (
  `cod_metpago` decimal(10,0) NOT NULL,
  `nom_metpago` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cod_metpago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `personal`
-- ----------------------------
DROP TABLE IF EXISTS `personal`;
CREATE TABLE `personal` (
  `cod_personal` decimal(10,0) NOT NULL,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `appat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `apmat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `dni` decimal(8,0) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `sexo` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `telefono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `cod_dist` decimal(10,0) DEFAULT NULL,
  `direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `cod_rol` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`cod_personal`),
  KEY `cod_rol` (`cod_rol`),
  CONSTRAINT `personal_ibfk_1` FOREIGN KEY (`cod_rol`) REFERENCES `rol` (`cod_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `personal_disciplina`
-- ----------------------------
DROP TABLE IF EXISTS `personal_disciplina`;
CREATE TABLE `personal_disciplina` (
  `cod_disciplina` decimal(10,0) NOT NULL,
  `cod_personal` decimal(10,0) NOT NULL,
  PRIMARY KEY (`cod_disciplina`,`cod_personal`),
  KEY `cod_personal` (`cod_personal`),
  CONSTRAINT `personal_disciplina_ibfk_1` FOREIGN KEY (`cod_disciplina`) REFERENCES `disciplina` (`cod_disciplina`),
  CONSTRAINT `personal_disciplina_ibfk_2` FOREIGN KEY (`cod_personal`) REFERENCES `personal` (`cod_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `plan`
-- ----------------------------
DROP TABLE IF EXISTS `plan`;
CREATE TABLE `plan` (
  `cod_plan` decimal(10,0) NOT NULL,
  `nom_plan` varchar(255) DEFAULT NULL,
  `cod_disciplina` decimal(10,0) DEFAULT NULL,
  `nro_sesiones` decimal(10,0) DEFAULT NULL,
  `duracion_meses` decimal(10,0) DEFAULT NULL,
  `duracion_dias` decimal(10,0) DEFAULT NULL,
  `nro_dias_freezee` decimal(10,0) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `cod_tipo_venta` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`cod_plan`),
  KEY `cod_disciplina` (`cod_disciplina`),
  KEY `cod_tipo_venta` (`cod_tipo_venta`),
  CONSTRAINT `plan_ibfk_1` FOREIGN KEY (`cod_disciplina`) REFERENCES `disciplina` (`cod_disciplina`),
  CONSTRAINT `plan_ibfk_2` FOREIGN KEY (`cod_tipo_venta`) REFERENCES `tipo_venta` (`cod_tipo_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `plan_disciplina_extra`
-- ----------------------------
DROP TABLE IF EXISTS `plan_disciplina_extra`;
CREATE TABLE `plan_disciplina_extra` (
  `cod_plan` decimal(10,0) NOT NULL,
  `cod_disciplina_extra` decimal(10,0) NOT NULL,
  PRIMARY KEY (`cod_plan`,`cod_disciplina_extra`),
  KEY `cod_disciplina_extra` (`cod_disciplina_extra`),
  CONSTRAINT `plan_disciplina_extra_ibfk_1` FOREIGN KEY (`cod_plan`) REFERENCES `plan` (`cod_plan`),
  CONSTRAINT `plan_disciplina_extra_ibfk_2` FOREIGN KEY (`cod_disciplina_extra`) REFERENCES `disciplina` (`cod_disciplina`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `rol`
-- ----------------------------
DROP TABLE IF EXISTS `rol`;
CREATE TABLE `rol` (
  `cod_rol` decimal(10,0) NOT NULL,
  `nom_rol` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cod_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `sala`
-- ----------------------------
DROP TABLE IF EXISTS `sala`;
CREATE TABLE `sala` (
  `cod_sala` decimal(10,0) NOT NULL,
  `nom_sale` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`cod_sala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `tipo_membresia`
-- ----------------------------
DROP TABLE IF EXISTS `tipo_membresia`;
CREATE TABLE `tipo_membresia` (
  `cod_tipo_membresia` decimal(10,0) NOT NULL,
  `nom_tipo_membresia` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`cod_tipo_membresia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `tipo_venta`
-- ----------------------------
DROP TABLE IF EXISTS `tipo_venta`;
CREATE TABLE `tipo_venta` (
  `cod_tipo_venta` decimal(10,0) NOT NULL,
  `nom_tipo_venta` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cod_tipo_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `venta_membresia`
-- ----------------------------
DROP TABLE IF EXISTS `venta_membresia`;
CREATE TABLE `venta_membresia` (
  `cod_venta_membresia` decimal(10,0) NOT NULL,
  `cod_cliente` decimal(10,0) DEFAULT NULL,
  `cod_plan` decimal(10,0) DEFAULT NULL,
  `cod_tipo_membresia` decimal(10,0) DEFAULT NULL,
  `fecha_inicio_plan` date DEFAULT NULL,
  `descuento_mon` decimal(10,2) DEFAULT NULL,
  `decuento_proc` decimal(10,2) DEFAULT NULL,
  `cod_vendedor` decimal(10,0) DEFAULT NULL,
  `cod_estado` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`cod_venta_membresia`),
  KEY `cod_cliente` (`cod_cliente`),
  KEY `cod_plan` (`cod_plan`),
  KEY `cod_tipo_membresia` (`cod_tipo_membresia`),
  KEY `cod_vendedor` (`cod_vendedor`),
  CONSTRAINT `venta_membresia_ibfk_1` FOREIGN KEY (`cod_cliente`) REFERENCES `cliente` (`cod_cliente`),
  CONSTRAINT `venta_membresia_ibfk_2` FOREIGN KEY (`cod_plan`) REFERENCES `plan` (`cod_plan`),
  CONSTRAINT `venta_membresia_ibfk_3` FOREIGN KEY (`cod_tipo_membresia`) REFERENCES `tipo_membresia` (`cod_tipo_membresia`),
  CONSTRAINT `venta_membresia_ibfk_4` FOREIGN KEY (`cod_vendedor`) REFERENCES `personal` (`cod_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Table structure for `venta_membresia_detpag`
-- ----------------------------
DROP TABLE IF EXISTS `venta_membresia_detpag`;
CREATE TABLE `venta_membresia_detpag` (
  `cod_venta_membresia` decimal(10,0) NOT NULL,
  `fecha_pago` date NOT NULL,
  `cod_metpago` decimal(10,0) NOT NULL,
  `cod_comprobante` decimal(10,0) DEFAULT NULL,
  `serie_comprobante` decimal(10,0) DEFAULT NULL,
  `corr_comprobante` decimal(10,0) DEFAULT NULL,
  `monto` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`cod_venta_membresia`,`fecha_pago`,`cod_metpago`),
  KEY `cod_metpago` (`cod_metpago`),
  KEY `cod_comprobante` (`cod_comprobante`),
  CONSTRAINT `venta_membresia_detpag_ibfk_1` FOREIGN KEY (`cod_venta_membresia`) REFERENCES `venta_membresia` (`cod_venta_membresia`),
  CONSTRAINT `venta_membresia_detpag_ibfk_2` FOREIGN KEY (`cod_metpago`) REFERENCES `metodo_pago` (`cod_metpago`),
  CONSTRAINT `venta_membresia_detpag_ibfk_3` FOREIGN KEY (`cod_comprobante`) REFERENCES `comprobante` (`cod_comprobante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
--  Records 
-- ----------------------------
