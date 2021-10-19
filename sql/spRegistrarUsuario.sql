DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spRegistrarUsuario`(IN `uNombres` VARCHAR(100), IN `uPaterno` VARCHAR(100), IN `uMaterno` VARCHAR(100), IN `uTipoUsuario` INT, IN `uFechaNacimiento` DATE, IN `uEmail` VARCHAR(100), IN `uContrasena` VARCHAR(100))
    COMMENT 'Registra usuarios de tipo cliente o empleado'
BEGIN
     DECLARE UserID int(5) DEFAULT 0;
         INSERT INTO `usuario`(
             `Nombres`,
             `Paterno`,
             `Materno`,
             `TipoUsuario`,
             `EstadoUsuario`,
             `FechaNacimiento`,
             `Email`)
            VALUES (uNombres, uPaterno, uMaterno, uTipoUsuario, uFechaNacimiento, uEmail);
         SET UserID = LAST_INSERT_ID();
         INSERT INTO `sesionusuario`(
             `UsuarioId`,
             `Contrasena`,
             `Sesion`)
            VALUES (UserID, uContrasena, '');
         SELECT uNombres, uPaterno, uMaterno;
 END$$
DELIMITER ;