CREATE DATABASE  IF NOT EXISTS `expedientes` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `expedientes`;
-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: localhost    Database: expedientes
-- ------------------------------------------------------
-- Server version	5.5.24-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `formas_aprobacion`
--

DROP TABLE IF EXISTS `formas_aprobacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `formas_aprobacion` (
  `id_aprobacion` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `aprobacion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_aprobacion`),
  KEY `formas_aprobacion_ix1` (`aprobacion`)
) ENGINE=MyISAM AUTO_INCREMENT=14283 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lista_preferencias`
--

DROP TABLE IF EXISTS `lista_preferencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lista_preferencias` (
  `id_lista` int(11) NOT NULL DEFAULT '0',
  `desc_lista` varchar(100) NOT NULL DEFAULT '',
  `orden_lista` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_lista`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `numero_expediente` int(11) unsigned NOT NULL,
  `tags` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`numero_expediente`),
  FULLTEXT KEY `tags` (`tags`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tags_normas`
--

DROP TABLE IF EXISTS `tags_normas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags_normas` (
  `nro_norma` int(11) unsigned NOT NULL,
  `tags` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`nro_norma`),
  FULLTEXT KEY `tags` (`tags`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `movimientos`
--

DROP TABLE IF EXISTS `movimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movimientos` (
  `id_movimiento` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `numero` int(11) unsigned DEFAULT NULL,
  `id_ubicacion_actual` int(11) unsigned DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `comentario` text,
  PRIMARY KEY (`id_movimiento`),
  KEY `movimientos_ix1` (`id_ubicacion_actual`),
  KEY `movimientos_ix2` (`fecha`),
  KEY `movimientos_ix3` (`numero`)
) ENGINE=MyISAM AUTO_INCREMENT=213707 DEFAULT CHARSET=latin1 COMMENT='InnoDB free: 9216 kB; InnoDB free: 9216 kB; InnoDB free: 921';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auditoria`
--

DROP TABLE IF EXISTS `auditoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auditoria` (
  `id_auditoria` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) unsigned NOT NULL DEFAULT '0',
  `fecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `numero_exp` int(11) unsigned DEFAULT '0',
  `accion` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_auditoria`),
  KEY `auditoria_ix1` (`id_usuario`),
  KEY `auditoria_ix2` (`numero_exp`),
  KEY `auditoria_ix3` (`fecha`)
) ENGINE=MyISAM AUTO_INCREMENT=420170 DEFAULT CHARSET=latin1 COMMENT='InnoDB free: 3072 kB; InnoDB free: 3072 kB; (`id_usuario`) R';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `normas`
--

DROP TABLE IF EXISTS `normas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `normas` (
  `nro_norma` varchar(20) NOT NULL,
  `tipo_norma` char(3) DEFAULT NULL,
  `fec_aprob` date DEFAULT NULL,
  `dsc_norma` varchar(128) DEFAULT NULL,
  `modifica` text,
  `id_usuario_alta` int(10) unsigned DEFAULT NULL,
  `id_usuario_mod` int(10) unsigned DEFAULT NULL,
  `fec_mod` datetime DEFAULT NULL,
  `fec_alta` datetime DEFAULT NULL,
  PRIMARY KEY (`nro_norma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de Normas';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id_usuario` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) DEFAULT NULL,
  `nick` varchar(50) DEFAULT NULL,
  `clave` varchar(50) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `telefono` varchar(250) DEFAULT NULL,
  `celular` varchar(250) DEFAULT NULL,
  `direccion` varchar(250) DEFAULT NULL,
  `dni` varchar(100) DEFAULT NULL,
  `perfil` char(1) DEFAULT NULL,
  `sector` varchar(250) DEFAULT NULL,
  `intentos` smallint(6) DEFAULT '0',
  `estado` varchar(255) DEFAULT 'H',
  `habilitado` char(1) DEFAULT 'S',
  `fec_alta` date DEFAULT NULL,
  `fec_baja` date DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `ix_usuarios2` (`nick`),
  KEY `ix_usuarios1` (`nick`,`clave`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1 COMMENT='InnoDB free: 4096 kB; InnoDB free: 4096 kB; InnoDB free: 409';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lista_preferencia_temp`
--

DROP TABLE IF EXISTS `lista_preferencia_temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lista_preferencia_temp` (
  `expediente` int(11) NOT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`expediente`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `id_categoria` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `categoria` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_categoria`),
  KEY `categorias_ix1` (`categoria`)
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=latin1 COMMENT='InnoDB free: 9216 kB; InnoDB free: 9216 kB; InnoDB free: 921';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expedientes`
--

DROP TABLE IF EXISTS `expedientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expedientes` (
  `numero` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` char(1) NOT NULL DEFAULT '',
  `letra` char(1) DEFAULT NULL,
  `anio` varchar(4) DEFAULT NULL,
  `nro_municipal` varchar(100) DEFAULT NULL,
  `tipo_proy` varchar(35) DEFAULT NULL,
  `num_mensaje` varchar(11) DEFAULT NULL,
  `caratula` varchar(250) DEFAULT NULL,
  `fec_presentacion` date DEFAULT NULL,
  `fec_ingreso` date DEFAULT NULL,
  `fec_sesion` date DEFAULT NULL,
  `com_destino` int(11) unsigned DEFAULT NULL,
  `id_ubicacion_actual` int(11) unsigned DEFAULT NULL,
  `id_destino` int(11) unsigned DEFAULT NULL,
  `fec_aprobacion` date DEFAULT NULL,
  `id_aprobacion` int(11) unsigned DEFAULT NULL,
  `tipo_aprobacion` varchar(40) DEFAULT NULL,
  `id_categoria` int(11) unsigned DEFAULT NULL,
  `agregados` text,
  `id_usuario_alta` int(11) unsigned DEFAULT NULL,
  `id_usuario_mod` int(11) unsigned DEFAULT NULL,
  `fec_alta` datetime DEFAULT NULL,
  `fec_mod` datetime DEFAULT NULL,
  `impreso` char(1) DEFAULT NULL,
  `id_grupo` smallint(6) DEFAULT NULL,
  `id_causante` int(11) unsigned DEFAULT NULL,
  `decretos` varchar(128) DEFAULT NULL,
  `declaraciones` varchar(128) DEFAULT NULL,
  `minutas` varchar(128) DEFAULT NULL,
  `ordenanzas_y_resoluciones` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`numero`),
  KEY `ix_expedientes1` (`id_destino`),
  KEY `ix_expedientes2` (`id_ubicacion_actual`),
  KEY `ix_expedientes3` (`id_usuario_alta`),
  KEY `ix_expedientes4` (`com_destino`),
  KEY `ix_expedientes6` (`id_categoria`),
  KEY `ix_expedientes5` (`id_aprobacion`),
  KEY `ix_expedientes7` (`nro_municipal`),
  KEY `ix_expedientes8` (`impreso`),
  KEY `ix_expedientes9` (`id_grupo`),
  KEY `ix_expedientes10` (`id_causante`),
  FULLTEXT KEY `caratula` (`caratula`,`tipo_aprobacion`,`num_mensaje`)
) ENGINE=MyISAM AUTO_INCREMENT=197555 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `destinos`
--

DROP TABLE IF EXISTS `destinos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destinos` (
  `id_destino` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `destino` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_destino`),
  KEY `ix_destinos1` (`destino`)
) ENGINE=MyISAM AUTO_INCREMENT=53563 DEFAULT CHARSET=latin1 COMMENT='InnoDB free: 4096 kB; InnoDB free: 4096 kB; InnoDB free: 409';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expedientes_temporal`
--

DROP TABLE IF EXISTS `expedientes_temporal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expedientes_temporal` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `numero` int(11) unsigned NOT NULL,
  `letra` char(1) DEFAULT NULL,
  `anio` varchar(4) DEFAULT NULL,
  `num_mensaje` varchar(11) DEFAULT NULL,
  `tipo_proy` varchar(35) DEFAULT NULL,
  `fec_presentacion` date DEFAULT NULL,
  `fec_sesion` date DEFAULT NULL,
  `fec_aprobacion` date DEFAULT NULL,
  `caratula` varchar(250) DEFAULT NULL,
  `tipo_aprobacion` varchar(40) DEFAULT NULL,
  `id_causante` int(11) unsigned DEFAULT NULL,
  `id_causante_txt` varchar(150) DEFAULT NULL,
  `com_destino` int(11) unsigned DEFAULT NULL,
  `com_destino_txt` varchar(150) DEFAULT NULL,
  `id_aprobacion` int(11) unsigned DEFAULT NULL,
  `id_aprobacion_txt` varchar(150) DEFAULT NULL,
  `id_ubicacion_actual` int(11) unsigned DEFAULT NULL,
  `id_ubicacion_actual_txt` varchar(150) DEFAULT NULL,
  `id_grupo` smallint(6) DEFAULT NULL,
  `grupo` varchar(150) DEFAULT NULL,
  `registro_nuevo` int(1) NOT NULL DEFAULT '1',
  `fec_alta` datetime DEFAULT NULL,
  `decretos` varchar(128) DEFAULT NULL,
  `declaraciones` varchar(128) DEFAULT NULL,
  `minutas` varchar(128) DEFAULT NULL,
  `ordenanzas_y_resoluciones` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expedientes_historico`
--

DROP TABLE IF EXISTS `expedientes_historico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expedientes_historico` (
  `id_historico` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `numero` int(11) unsigned NOT NULL DEFAULT '0',
  `tipo` char(1) NOT NULL DEFAULT '',
  `letra` char(1) DEFAULT NULL,
  `anio` varchar(4) DEFAULT NULL,
  `nro_municipal` varchar(100) DEFAULT NULL,
  `tipo_proy` varchar(35) DEFAULT NULL,
  `num_mensaje` varchar(11) DEFAULT NULL,
  `caratula` varchar(250) DEFAULT NULL,
  `fec_presentacion` date DEFAULT NULL,
  `fec_ingreso` date DEFAULT NULL,
  `fec_sesion` date DEFAULT NULL,
  `com_destino` int(11) unsigned DEFAULT NULL,
  `id_ubicacion_actual` int(11) unsigned DEFAULT NULL,
  `id_destino` int(11) unsigned DEFAULT NULL,
  `fec_aprobacion` date DEFAULT NULL,
  `id_aprobacion` int(11) unsigned DEFAULT NULL,
  `tipo_aprobacion` varchar(40) DEFAULT NULL,
  `id_categoria` int(11) unsigned DEFAULT NULL,
  `agregados` text,
  `id_usuario_alta` int(11) unsigned DEFAULT NULL,
  `id_usuario_mod` int(11) unsigned DEFAULT NULL,
  `fec_alta` datetime DEFAULT NULL,
  `fec_mod` datetime DEFAULT NULL,
  `impreso` char(1) DEFAULT NULL,
  `id_grupo` smallint(6) DEFAULT NULL,
  `id_causante` int(11) unsigned DEFAULT NULL,
  `decretos` varchar(128) DEFAULT NULL,
  `declaraciones` varchar(128) DEFAULT NULL,
  `minutas` varchar(128) DEFAULT NULL,
  `ordenanzas_y_resoluciones` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id_historico`),
  KEY `ix_expedientes_historico1` (`id_destino`),
  KEY `ix_expedientes_historico2` (`id_ubicacion_actual`),
  KEY `ix_expedientes_historico3` (`id_usuario_alta`),
  KEY `ix_expedientes_historico4` (`com_destino`),
  KEY `ix_expedientes_historico5` (`id_categoria`),
  KEY `ix_expedientes_historico6` (`id_aprobacion`),
  KEY `ix_expedientes_historico7` (`nro_municipal`),
  KEY `ix_expedientes_historico8` (`impreso`),
  KEY `ix_expedientes_historico9` (`id_grupo`),
  KEY `ix_expedientes_historico10` (`id_causante`)
) ENGINE=MyISAM AUTO_INCREMENT=32412 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grupos_impresion`
--

DROP TABLE IF EXISTS `grupos_impresion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupos_impresion` (
  `id_grupo` smallint(6) NOT NULL AUTO_INCREMENT,
  `grupo` varchar(50) DEFAULT NULL,
  `orden` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id_grupo`),
  KEY `grupos_impresion_ix1` (`grupo`),
  KEY `grupos_impresion_ix2` (`orden`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COMMENT='InnoDB free: 11264 kB; InnoDB free: 11264 kB; InnoDB free: 1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `causantes`
--

DROP TABLE IF EXISTS `causantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `causantes` (
  `id_causante` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `causante` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_causante`),
  KEY `causantes_ix1` (`causante`)
) ENGINE=MyISAM AUTO_INCREMENT=30316 DEFAULT CHARSET=latin1 COMMENT='InnoDB free: 11264 kB';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `normas_mod`
--

DROP TABLE IF EXISTS `normas_mod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `normas_mod` (
  `nro_norma` int(11) NOT NULL,
  `normas_modifica` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`nro_norma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'expedientes'
--

--
-- Dumping routines for database 'expedientes'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-11-09 17:06:26
CREATE DATABASE  IF NOT EXISTS `expedientesweb` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `expedientesweb`;
-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: localhost    Database: expedientesweb
-- ------------------------------------------------------
-- Server version	5.5.24-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `normas`
--

DROP TABLE IF EXISTS `normas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `normas` (
  `nro_norma` varchar(20) NOT NULL,
  `tipo_norma` char(3) DEFAULT NULL,
  `fec_aprob` date DEFAULT NULL,
  `dsc_norma` varchar(128) DEFAULT NULL,
  `modifica` text,
  `id_usuario_alta` int(10) unsigned DEFAULT NULL,
  `id_usuario_mod` int(10) unsigned DEFAULT NULL,
  `fec_mod` datetime DEFAULT NULL,
  `fec_alta` datetime DEFAULT NULL,
  PRIMARY KEY (`nro_norma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de Normas';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expedientes_legislativos`
--

DROP TABLE IF EXISTS `expedientes_legislativos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expedientes_legislativos` (
  `numero` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` char(1) NOT NULL DEFAULT '',
  `letra` char(1) DEFAULT NULL,
  `anio` varchar(4) DEFAULT NULL,
  `nro_municipal` varchar(100) DEFAULT NULL,
  `tipo_proy` varchar(35) DEFAULT NULL,
  `num_mensaje` varchar(11) DEFAULT NULL,
  `caratula` varchar(250) DEFAULT NULL,
  `fec_presentacion` date DEFAULT NULL,
  `fec_ingreso` date DEFAULT NULL,
  `fec_sesion` date DEFAULT NULL,
  `com_destino` int(11) unsigned DEFAULT NULL,
  `id_ubicacion_actual` int(11) unsigned DEFAULT NULL,
  `id_destino` int(11) unsigned DEFAULT NULL,
  `fec_aprobacion` date DEFAULT NULL,
  `id_aprobacion` int(11) unsigned DEFAULT NULL,
  `tipo_aprobacion` varchar(40) DEFAULT NULL,
  `id_categoria` int(11) unsigned DEFAULT NULL,
  `agregados` text,
  `id_usuario_alta` int(11) unsigned DEFAULT NULL,
  `id_usuario_mod` int(11) unsigned DEFAULT NULL,
  `fec_alta` datetime DEFAULT NULL,
  `fec_mod` datetime DEFAULT NULL,
  `impreso` char(1) DEFAULT NULL,
  `id_grupo` smallint(6) DEFAULT NULL,
  `id_causante` int(11) unsigned DEFAULT NULL,
  `decretos` varchar(128) DEFAULT NULL,
  `declaraciones` varchar(128) DEFAULT NULL,
  `minutas` varchar(128) DEFAULT NULL,
  `ordenanzas_y_resoluciones` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`numero`),
  KEY `ix_expedientes1` (`id_destino`),
  KEY `ix_expedientes2` (`id_ubicacion_actual`),
  KEY `ix_expedientes3` (`id_usuario_alta`),
  KEY `ix_expedientes4` (`com_destino`),
  KEY `ix_expedientes6` (`id_categoria`),
  KEY `ix_expedientes5` (`id_aprobacion`),
  KEY `ix_expedientes7` (`nro_municipal`),
  KEY `ix_expedientes8` (`impreso`),
  KEY `ix_expedientes9` (`id_grupo`),
  KEY `ix_expedientes10` (`id_causante`),
  FULLTEXT KEY `caratula` (`caratula`,`tipo_aprobacion`,`num_mensaje`)
) ENGINE=MyISAM AUTO_INCREMENT=197555 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'expedientesweb'
--

--
-- Dumping routines for database 'expedientesweb'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-11-09 17:06:27
