SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

USE `proarp` ;

CREATE TABLE IF NOT EXISTS `proarp`.`TipoUsuario` (
  `TipoUsuarioId` INT NOT NULL,
  `Tipo` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`TipoUsuarioId`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `proarp`.`EstadoUsuario` (
  `EstadoUsuarioId` INT NOT NULL,
  `EstadoUsuario` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`EstadoUsuarioId`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `proarp`.`Usuario` (
  `UsuarioId` INT NOT NULL AUTO_INCREMENT,
  `Nombres` VARCHAR(100) NOT NULL,
  `Paterno` VARCHAR(100) NULL,
  `Materno` VARCHAR(100) NULL,
  `TipoUsuario` INT NOT NULL,
  `EstadoUsuario` INT NOT NULL,
  `FechaNacimiento` DATE NOT NULL,
  `FechaRegistro` DATETIME NOT NULL,
  `Email` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`UsuarioId`),
  INDEX `FK_TipoUsuario_TipoUsuarioId_idx` (`TipoUsuario` ASC),
  INDEX `FK_EstadoUsuario_EstadoUsuarioId_idx` (`EstadoUsuario` ASC),
  CONSTRAINT `FK_TipoUsuario_TipoUsuarioId`
    FOREIGN KEY (`TipoUsuario`)
    REFERENCES `proarp`.`TipoUsuario` (`TipoUsuarioId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_EstadoUsuario_EstadoUsuarioId`
    FOREIGN KEY (`EstadoUsuario`)
    REFERENCES `proarp`.`EstadoUsuario` (`EstadoUsuarioId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `proarp`.`SesionUsuario` (
  `UsuarioId` INT NOT NULL,
  `Contrasena` VARCHAR(100) NOT NULL,
  `Sesion` NVARCHAR(100) NOT NULL,
  `IntentosFallidos` INT NOT NULL DEFAULT 0,
  INDEX `FK_SesionUsuario_Usuario_idx` (`UsuarioId` ASC),
  CONSTRAINT `FK_SesionUsuario_Usuario`
    FOREIGN KEY (`UsuarioId`)
    REFERENCES `proarp`.`Usuario` (`UsuarioId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `proarp`.`EstadoInventario` (
  `EstadoInventarioId` INT NOT NULL,
  `EstadoInventario` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`EstadoInventarioId`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `proarp`.`Proveedor` (
  `ProveedorId` INT NOT NULL,
  `RazonSocial` VARCHAR(150) NOT NULL,
  `MarcaComercial` VARCHAR(200) NULL,
  `RFC` VARCHAR(13) NULL,
  `Direccion` VARCHAR(150) NULL,
  `Telefono` VARCHAR(45) NOT NULL,
  `TelefonoAlternativo` VARCHAR(45) NULL,
  `NombreContacto` VARCHAR(100) NULL,
  `Descripcion` VARCHAR(200) NULL,
  `Notas` VARCHAR(500) NULL,
  PRIMARY KEY (`ProveedorId`))
ENGINE = InnoDB;



CREATE TABLE IF NOT EXISTS `proarp`.`Inventario` (
  `InventarioId` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(45) NOT NULL,
  `Descripcion` VARCHAR(45) NOT NULL,
  `Costo` DOUBLE NOT NULL,
  `PrecioVenta` DOUBLE NOT NULL,
  `Existencias` INT NOT NULL,
  `Estado` INT NOT NULL,
  `Proveedor` INT NOT NULL,
  `FechaCompra` DATETIME NOT NULL,
  PRIMARY KEY (`InventarioId`),
  INDEX `FK_EstadoInventario_EstadoInventarioID_idx` (`Estado` ASC),
  INDEX `FK_ProveedorId_Proveedor_idx` (`Proveedor` ASC),
  CONSTRAINT `FK_EstadoInventario_EstadoInventarioID`
    FOREIGN KEY (`Estado`)
    REFERENCES `proarp`.`EstadoInventario` (`EstadoInventarioId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_ProveedorId_Proveedor`
    FOREIGN KEY (`Proveedor`)
    REFERENCES `proarp`.`Proveedor` (`ProveedorId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `proarp`.`Imagenes` (
  `ImagenId` INT NOT NULL,
  `Descripcion` VARCHAR(45) NOT NULL,
  `RutaFisica` VARCHAR(45) NOT NULL,
  `InventarioId` INT NOT NULL,
  PRIMARY KEY (`ImagenId`),
  INDEX `FK_Inventario_InventarioId_idx` (`InventarioId` ASC),
  CONSTRAINT `FK_Inventario_InventarioId`
    FOREIGN KEY (`InventarioId`)
    REFERENCES `proarp`.`Inventario` (`InventarioId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `proarp`.`TipoAlerta` (
  `TipoAlertaId` INT NOT NULL,
  `DescripcionAlerta` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`TipoAlertaId`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `proarp`.`EstadoAlerta` (
  `EstadoAlertaId` INT NOT NULL,
  `EstadoAlerta` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`EstadoAlertaId`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `proarp`.`Alerta` (
  `AlertaId` INT NOT NULL,
  `Mensaje` VARCHAR(200) NOT NULL,
  `EstadoId` INT NOT NULL,
  `TipoAlertaId` INT NOT NULL,
  `FechaAlerta` DATETIME NOT NULL,
  `FechaCambioEstado` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`AlertaId`),
  INDEX `FK_TipoAlerta_TipoAlertaId_idx` (`TipoAlertaId` ASC),
  INDEX `FK_EstadoAlerta_EstadoAlertaId_idx` (`EstadoId` ASC),
  CONSTRAINT `FK_TipoAlerta_TipoAlertaId`
    FOREIGN KEY (`TipoAlertaId`)
    REFERENCES `proarp`.`TipoAlerta` (`TipoAlertaId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_EstadoAlerta_EstadoAlertaId`
    FOREIGN KEY (`EstadoId`)
    REFERENCES `proarp`.`EstadoAlerta` (`EstadoAlertaId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


DROP PROCEDURE `spTest`;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spTest`(IN `uFecha` DATE)
NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER SELECT uFecha


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;