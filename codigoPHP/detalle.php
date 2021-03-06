<?php
session_start();
if (!isset($_SESSION['usuarioDAW205AppLoginLogoutTema5'])) {
    header('Location: login.php');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../webroot/css/estilo.css">
        <title>detalle</title>
    </head>
    <body>
        <?php
            /*
             * @author: David del Prado Losada
             * @version: v1.Realizacion del ejercicio
             * Created on: 29/11/2021
             * Ventana de detalle
             */
        
            echo '<h1>PROYECTO LOGIN LOGOFF - DETALLE</h1>';
            
            ?>
                <a href="programa.php">Aceptar</a>
            <?php 
            
            echo '<h3>Contenido de las variables superglobales: <h3>';
            
            echo '<h4>Variable $_SESSION: </h4>';
            echo '<pre>';
            print_r($_SESSION);
            echo '</pre>';
            
            echo '<h4>Variable $_COOKIE: </h4>';
            echo '<pre>';
            print_r($_COOKIE);
            echo '</pre>';
            
            echo '<h4>Variable $GLOBALS: </h4>';
            echo '<pre>';
            print_r($GLOBALS);
            echo '</pre>';
            
            echo '<h4>Variable $_SERVER: </h4>';
            echo '<pre>';
            print_r($_SERVER);
            echo '</pre>';
            
            echo '<h4>Variable $_FILES: </h4>';
            echo '<pre>';
            print_r($_FILES);
            echo '</pre>';
            
            echo '<h4>Variable $_ENV: </h4>';
            echo '<pre>';
            print_r($_ENV);
            echo '</pre>';
            
            echo '<h4>Variable $_REQUEST: </h4>';
            echo '<pre>';
            print_r($_REQUEST);
            echo '</pre>';
            
            echo '<h3>PHPInfo: <h3>';
            phpinfo();
        ?>
        
    </body>
</html>
