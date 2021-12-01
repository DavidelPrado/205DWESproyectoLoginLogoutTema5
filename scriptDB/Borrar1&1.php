<?php
    require_once '../config/confDBPDO.php';

    try {
        //Conectar a la base de datos
        $DB = new PDO(HOST, USER, PASSWORD);
        //Configurar las excepciones
        $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql=<<<PDO
            drop table if exists T01_Usuario;
            drop table if exists T02_Departamento;
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