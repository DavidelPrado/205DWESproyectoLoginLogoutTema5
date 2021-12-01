<?php
    require_once '../config/confDBPDO.php';

    try {
        //Conectar a la base de datos
        $DB = new PDO(HOST, USER, PASSWORD);
        //Configurar las excepciones
        $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql=<<<PDO
            create table if not exists DB205DWESProyectoTema5.T01_Usuario(
                T01_CodUsuario varchar(8),
                T01_Password varchar(64) NOT NULL,
                T01_DescUsuario varchar(255) NOT NULL,
                T01_FechaHoraUltimaConexion timestamp,
                T01_NumConexiones int default 0,
                T01_Perfil enum("administrador", "usuario") default "usuario",
                T01_ImagenUsuario mediumblob NULL,
                primary key(T01_CodUsuario)
            )ENGINE=INNODB;
                
            create table DB205DWESProyectoTema5.T02_Departamento(
                T02_CodDepartamento varchar(3),
                T02_DescDepartamento varchar(255) NOT NULL,
                T02_FechaCreacionDepartamento timestamp,
                T02_VolumenDeNegocio float NULL,
                T02_FechaBajaDepartamento date NULL,
                primary key(T02_CodDepartamento)
            )ENGINE=INNODB; 
        PDO;
        
        $DB->exec($sql);
        
    } catch (PDOException $excepcion) {
        $errorExcepcion = $excepcion->getCode();
        $mensajeExcepcion = $excepcion->getMessage();

        //Mostrar el mensaje de la excepcion
        echo '<p>Error: ' . $mensajeExcepcion . '</p>';
        //Mostrar el codigo de la excepcion
        echo '<p>Codigo de error: ' . $errorExcepcion . '</p>';
    } finally {
        //Cerrar conexión
        unset($DB);
    }
?>