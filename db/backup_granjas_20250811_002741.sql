-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: granjas
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `compra`
--

DROP TABLE IF EXISTS `compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compra` (
  `idGranja` int(11) NOT NULL,
  `idCompuesto` int(11) NOT NULL,
  PRIMARY KEY (`idGranja`,`idCompuesto`),
  KEY `idCompuesto` (`idCompuesto`),
  CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`idGranja`) REFERENCES `granja` (`idGranja`),
  CONSTRAINT `compra_ibfk_2` FOREIGN KEY (`idCompuesto`) REFERENCES `compuesto` (`idCompuesto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compra`
--

LOCK TABLES `compra` WRITE;
/*!40000 ALTER TABLE `compra` DISABLE KEYS */;
/*!40000 ALTER TABLE `compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compuesto`
--

DROP TABLE IF EXISTS `compuesto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compuesto` (
  `idCompuesto` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `proveedor` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`idCompuesto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compuesto`
--

LOCK TABLES `compuesto` WRITE;
/*!40000 ALTER TABLE `compuesto` DISABLE KEYS */;
/*!40000 ALTER TABLE `compuesto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galpon`
--

DROP TABLE IF EXISTS `galpon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `galpon` (
  `idGalpon` int(11) NOT NULL,
  `identificacion` varchar(80) NOT NULL,
  `idTipoAve` int(11) DEFAULT NULL,
  `capacidad` int(11) DEFAULT NULL,
  `idGranja` int(11) DEFAULT NULL,
  PRIMARY KEY (`idGalpon`),
  KEY `idTipoAve` (`idTipoAve`),
  KEY `idGranja` (`idGranja`),
  CONSTRAINT `galpon_ibfk_1` FOREIGN KEY (`idTipoAve`) REFERENCES `tipoave` (`idTipoAve`),
  CONSTRAINT `galpon_ibfk_2` FOREIGN KEY (`idGranja`) REFERENCES `granja` (`idGranja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galpon`
--

LOCK TABLES `galpon` WRITE;
/*!40000 ALTER TABLE `galpon` DISABLE KEYS */;
INSERT INTO `galpon` VALUES (0,'001-Frente',0,60000,1),(1,'002-Fondo',1,120000,1),(2,'003-Medio',1,40000,1);
/*!40000 ALTER TABLE `galpon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galpon_loteaves`
--

DROP TABLE IF EXISTS `galpon_loteaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `galpon_loteaves` (
  `idGalpon_loteAve` int(11) NOT NULL,
  `idLoteAves` int(11) DEFAULT NULL,
  `idGalpon` int(11) DEFAULT NULL,
  `fechaInicio` date DEFAULT NULL,
  `fechaFin` date DEFAULT NULL,
  PRIMARY KEY (`idGalpon_loteAve`),
  KEY `idLoteAves` (`idLoteAves`),
  KEY `idGalpon` (`idGalpon`),
  CONSTRAINT `galpon_loteaves_ibfk_1` FOREIGN KEY (`idLoteAves`) REFERENCES `loteaves` (`idLoteAves`),
  CONSTRAINT `galpon_loteaves_ibfk_2` FOREIGN KEY (`idGalpon`) REFERENCES `galpon` (`idGalpon`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galpon_loteaves`
--

LOCK TABLES `galpon_loteaves` WRITE;
/*!40000 ALTER TABLE `galpon_loteaves` DISABLE KEYS */;
/*!40000 ALTER TABLE `galpon_loteaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `granja`
--

DROP TABLE IF EXISTS `granja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `granja` (
  `idGranja` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `habilitacionSenasa` varchar(80) DEFAULT NULL,
  `metrosCuadrados` int(11) DEFAULT NULL,
  `ubicacion` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`idGranja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `granja`
--

LOCK TABLES `granja` WRITE;
/*!40000 ALTER TABLE `granja` DISABLE KEYS */;
INSERT INTO `granja` VALUES (0,'Granja la chorlita','07-892-0467',80,'Aldea San Rafael'),(1,'Granja el nieto','10-015-8905',120,'Aldea San Juan'),(2,'Avicola Maria Clara','07-012-0405',120,'Aldea Santa Rosa');
/*!40000 ALTER TABLE `granja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loteaves`
--

DROP TABLE IF EXISTS `loteaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loteaves` (
  `idLoteAves` int(11) NOT NULL,
  `identificador` varchar(20) DEFAULT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `fechaCompra` date DEFAULT NULL,
  `cantidadAves` int(11) DEFAULT NULL,
  `idTipoAve` int(11) DEFAULT NULL,
  PRIMARY KEY (`idLoteAves`),
  KEY `idTipoAve` (`idTipoAve`),
  CONSTRAINT `loteaves_ibfk_1` FOREIGN KEY (`idTipoAve`) REFERENCES `tipoave` (`idTipoAve`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loteaves`
--

LOCK TABLES `loteaves` WRITE;
/*!40000 ALTER TABLE `loteaves` DISABLE KEYS */;
/*!40000 ALTER TABLE `loteaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lotevacuna`
--

DROP TABLE IF EXISTS `lotevacuna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lotevacuna` (
  `idLoteVacuna` int(11) NOT NULL,
  `numeroLote` varchar(20) DEFAULT NULL,
  `fechaCompra` date DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `vencimiento` date DEFAULT NULL,
  `idVacuna` int(11) DEFAULT NULL,
  PRIMARY KEY (`idLoteVacuna`),
  KEY `idVacuna` (`idVacuna`),
  CONSTRAINT `lotevacuna_ibfk_1` FOREIGN KEY (`idVacuna`) REFERENCES `vacuna` (`idVacuna`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lotevacuna`
--

LOCK TABLES `lotevacuna` WRITE;
/*!40000 ALTER TABLE `lotevacuna` DISABLE KEYS */;
INSERT INTO `lotevacuna` VALUES (0,'00123-5482','2025-03-01',5200,'2028-01-01',2),(1,'129-2025','2025-01-25',3560,'2028-02-01',1),(2,'A12025','2024-09-11',20000,'2028-03-01',0);
/*!40000 ALTER TABLE `lotevacuna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lotevacuna_loteave`
--

DROP TABLE IF EXISTS `lotevacuna_loteave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lotevacuna_loteave` (
  `idloteVacuna_loteAve` int(11) NOT NULL,
  `idLoteAves` int(11) DEFAULT NULL,
  `idLoteVacuna` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`idloteVacuna_loteAve`),
  KEY `idLoteAves` (`idLoteAves`),
  KEY `idLoteVacuna` (`idLoteVacuna`),
  CONSTRAINT `lotevacuna_loteave_ibfk_1` FOREIGN KEY (`idLoteAves`) REFERENCES `loteaves` (`idLoteAves`),
  CONSTRAINT `lotevacuna_loteave_ibfk_2` FOREIGN KEY (`idLoteVacuna`) REFERENCES `lotevacuna` (`idLoteVacuna`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lotevacuna_loteave`
--

LOCK TABLES `lotevacuna_loteave` WRITE;
/*!40000 ALTER TABLE `lotevacuna_loteave` DISABLE KEYS */;
/*!40000 ALTER TABLE `lotevacuna_loteave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mantenimientogalpon`
--

DROP TABLE IF EXISTS `mantenimientogalpon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mantenimientogalpon` (
  `idMantenimientoGalpon` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `idGalpon` int(11) DEFAULT NULL,
  `idTipoMantenimiento` int(11) DEFAULT NULL,
  PRIMARY KEY (`idMantenimientoGalpon`),
  KEY `idGalpon` (`idGalpon`),
  KEY `idTipoMantenimiento` (`idTipoMantenimiento`),
  CONSTRAINT `mantenimientogalpon_ibfk_1` FOREIGN KEY (`idGalpon`) REFERENCES `galpon` (`idGalpon`),
  CONSTRAINT `mantenimientogalpon_ibfk_2` FOREIGN KEY (`idTipoMantenimiento`) REFERENCES `tipomantenimiento` (`idTipoMantenimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mantenimientogalpon`
--

LOCK TABLES `mantenimientogalpon` WRITE;
/*!40000 ALTER TABLE `mantenimientogalpon` DISABLE KEYS */;
INSERT INTO `mantenimientogalpon` VALUES (0,'2025-01-22 00:00:00',0,0),(1,'2025-01-01 00:00:00',0,1),(2,'2025-01-20 00:00:00',1,2);
/*!40000 ALTER TABLE `mantenimientogalpon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mantenimientogranja`
--

DROP TABLE IF EXISTS `mantenimientogranja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mantenimientogranja` (
  `idMantenimientoGranja` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `idGranja` int(11) DEFAULT NULL,
  `idTipoMantenimiento` int(11) DEFAULT NULL,
  PRIMARY KEY (`idMantenimientoGranja`),
  KEY `idGranja` (`idGranja`),
  KEY `idTipoMantenimiento` (`idTipoMantenimiento`),
  CONSTRAINT `mantenimientogranja_ibfk_1` FOREIGN KEY (`idGranja`) REFERENCES `granja` (`idGranja`),
  CONSTRAINT `mantenimientogranja_ibfk_2` FOREIGN KEY (`idTipoMantenimiento`) REFERENCES `tipomantenimiento` (`idTipoMantenimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mantenimientogranja`
--

LOCK TABLES `mantenimientogranja` WRITE;
/*!40000 ALTER TABLE `mantenimientogranja` DISABLE KEYS */;
INSERT INTO `mantenimientogranja` VALUES (1,'2025-01-01 00:00:00',1,1),(2,'2025-01-20 00:00:00',1,2);
/*!40000 ALTER TABLE `mantenimientogranja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mortandadaves`
--

DROP TABLE IF EXISTS `mortandadaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mortandadaves` (
  `idMortandad` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `causa` varchar(100) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `idLoteAves` int(11) DEFAULT NULL,
  PRIMARY KEY (`idMortandad`),
  KEY `idLoteAves` (`idLoteAves`),
  CONSTRAINT `mortandadaves_ibfk_1` FOREIGN KEY (`idLoteAves`) REFERENCES `loteaves` (`idLoteAves`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mortandadaves`
--

LOCK TABLES `mortandadaves` WRITE;
/*!40000 ALTER TABLE `mortandadaves` DISABLE KEYS */;
/*!40000 ALTER TABLE `mortandadaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesajeloteaves`
--

DROP TABLE IF EXISTS `pesajeloteaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pesajeloteaves` (
  `idPesaje` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `idLoteAves` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPesaje`),
  KEY `idLoteAves` (`idLoteAves`),
  CONSTRAINT `pesajeloteaves_ibfk_1` FOREIGN KEY (`idLoteAves`) REFERENCES `loteaves` (`idLoteAves`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesajeloteaves`
--

LOCK TABLES `pesajeloteaves` WRITE;
/*!40000 ALTER TABLE `pesajeloteaves` DISABLE KEYS */;
/*!40000 ALTER TABLE `pesajeloteaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipoave`
--

DROP TABLE IF EXISTS `tipoave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipoave` (
  `idTipoAve` int(11) NOT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`idTipoAve`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipoave`
--

LOCK TABLES `tipoave` WRITE;
/*!40000 ALTER TABLE `tipoave` DISABLE KEYS */;
INSERT INTO `tipoave` VALUES (0,'Ponedora ligera'),(1,'Ponedora pesada'),(2,'Ponedora semipesada');
/*!40000 ALTER TABLE `tipoave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipomantenimiento`
--

DROP TABLE IF EXISTS `tipomantenimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipomantenimiento` (
  `idTipoMantenimiento` int(11) NOT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`idTipoMantenimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipomantenimiento`
--

LOCK TABLES `tipomantenimiento` WRITE;
/*!40000 ALTER TABLE `tipomantenimiento` DISABLE KEYS */;
INSERT INTO `tipomantenimiento` VALUES (0,'Corte de cesped'),(1,'Fumigacien contra plagas'),(2,'Colocacien de cebos para roedores');
/*!40000 ALTER TABLE `tipomantenimiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(500) NOT NULL,
  `direccion` varchar(500) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `fechaNac` date NOT NULL,
  `tipoUsuario` enum('dueno','encargado') NOT NULL DEFAULT 'dueno',
  `user_token` varchar(64) DEFAULT NULL,
  `user_token_expir` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (0,'Brian','bngbrian@gmail.com','Los Talas 180','+54934345555','123456','2000-03-01','encargado',NULL,NULL),(1,'Nahuel','nahuel@gmail.com','Los Colibries 1130','+54933345555','123456','1998-03-01','dueno','f5cff78c54ea301a1df26e87fb6e84eb642e86beb8d43bec84b68a9bb86ad403','2025-08-11 06:37:25');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacuna`
--

DROP TABLE IF EXISTS `vacuna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vacuna` (
  `idVacuna` int(11) NOT NULL,
  `nombre` varchar(40) DEFAULT NULL,
  `idViaAplicacion` int(11) DEFAULT NULL,
  `marca` varchar(40) DEFAULT NULL,
  `enfermedad` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`idVacuna`),
  KEY `idViaAplicacion` (`idViaAplicacion`),
  CONSTRAINT `vacuna_ibfk_1` FOREIGN KEY (`idViaAplicacion`) REFERENCES `viaaplicacion` (`idViaAplicacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacuna`
--

LOCK TABLES `vacuna` WRITE;
/*!40000 ALTER TABLE `vacuna` DISABLE KEYS */;
INSERT INTO `vacuna` VALUES (0,'Pfizer',0,'Sun Microvirus','Gripe aviar'),(1,'Covid-19',1,'Avast','Influenza aviar'),(2,'Antitetanica',1,'AVG','Viruela aviar');
/*!40000 ALTER TABLE `vacuna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventaloteaves`
--

DROP TABLE IF EXISTS `ventaloteaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventaloteaves` (
  `idVentaLoteAves` int(11) NOT NULL,
  `fechaVenta` date DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `idLoteAves` int(11) DEFAULT NULL,
  PRIMARY KEY (`idVentaLoteAves`),
  KEY `idLoteAves` (`idLoteAves`),
  CONSTRAINT `ventaloteaves_ibfk_1` FOREIGN KEY (`idLoteAves`) REFERENCES `loteaves` (`idLoteAves`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventaloteaves`
--

LOCK TABLES `ventaloteaves` WRITE;
/*!40000 ALTER TABLE `ventaloteaves` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventaloteaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `viaaplicacion`
--

DROP TABLE IF EXISTS `viaaplicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `viaaplicacion` (
  `idViaAplicacion` int(11) NOT NULL,
  `via` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idViaAplicacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `viaaplicacion`
--

LOCK TABLES `viaaplicacion` WRITE;
/*!40000 ALTER TABLE `viaaplicacion` DISABLE KEYS */;
INSERT INTO `viaaplicacion` VALUES (0,'Subcutánea'),(1,'Intramuscular'),(2,'Alas'),(3,'Spray'),(4,'En agua'),(5,'En alimento'),(6,'Ocular'),(7,'Nasal');
/*!40000 ALTER TABLE `viaaplicacion` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-10 19:27:42
