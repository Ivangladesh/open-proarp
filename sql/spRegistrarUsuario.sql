DROP PROCEDURE `spRegistrarUsuario`;
CREATE DEFINER=`id16866430_sa`@`%`
PROCEDURE `spRegistrarUsuario`
(IN `uNombreCompleto` VARCHAR(200) CHARSET utf8mb4,
IN `uPassword` VARCHAR(200) CHARSET utf8mb4)
NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN
     DECLARE UserID int(5) DEFAULT 0;
         INSERT INTO `usuarios`(`Nombre_usuario`, `Tipo_usuario`, `Password`)
            VALUES (uNombreCompleto,'C',uPassword);
         SET UserID = LAST_INSERT_ID();
         INSERT INTO `estado_sesion`(`ID_usuario`, `Estado`, `Cookie_sesion`)
            VALUES (UserID,1,'');
         SELECT UserID;
 END