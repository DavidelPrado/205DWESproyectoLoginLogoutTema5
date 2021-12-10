<?php
session_start();
if (!isset($_SESSION['usuarioDAW205AppLoginLogoutTema5'])) {
    header('Location: ../codigoPHP/login.php');
}

if(isset($_REQUEST['logout'])){
    session_unset();
    session_destroy();
    header('Location: ../codigoPHP/login.php');
    exit;
}

if(isset($_REQUEST['editar'])){
    header('Location: ../codigoPHP/editarPerfil.php');
    exit;
}

if(isset($_REQUEST['detalle'])){
    header('Location: ../codigoPHP/detalle.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../webroot/css/estilo.css">
        <style>
            input{
                font-size: 20px;
                font-weight: bold;
            }
        </style>
        <title>programa</title>
    </head>
    <body>
        <?php
            /*
             * @author: David del Prado Losada
             * @version: v1.Realizacion del ejercicio
             * Created on: 29/11/2021
             * Ventana de programa
             */
        
            echo '<h1>PROYECTO LOGIN LOGOFF - PROGRAMA</h1>';
        ?>
        
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method='post'>
            <input type='submit' name='logout' value='Logout'/>
            <input type='submit' name='detalle' value='Detalle'/>
            <input type='submit' name='editar' value='Editar Perfil'/>
        </form>
        
         <?php
        //Incluir el archivo de conexión con la base de datos
        require_once "../config/confDBPDO.php";
        try{
            //Conectar a la base de datos
            $DAW205DB = new PDO(HOST, USER, PASSWORD);
            //Cambiar el atributo ERRMODE para que muestre la excepcion en caso de error
            $DAW205DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //Query de seleccion
            $consulta="SELECT * FROM T01_Usuario WHERE T01_CodUsuario='".$_SESSION['usuarioDAW205AppLoginLogoutTema5']."'";
            $oResultado=$DAW205DB->prepare($consulta);
            $oResultado->execute();

            $resultado=$oResultado->fetchobject();

            /* Si existe este usuario alamacenamos en la session un variable user para recuperala enPrograma.php */
            if($resultado->T01_NumConexiones!=1){
                echo 'Bienvenido '.$resultado->T01_DescUsuario.' es la '.$resultado->T01_NumConexiones.' vez que se conecta y su ultima conexion fue '.$_SESSION['FechaHoraUltimaConexionAnterior'].'';
                exit;
            } else {
                echo 'Bienvenido '.$resultado->T01_DescUsuario.' esta es la primera vez que se conecta.';
            }
        }catch(PDOException $excepcion){
            $errorExcepcion=$excepcion->getCode();
            $mensajeExcepcion=$excepcion->getMessage();

            //Mostrar el mensaje de la excepcion
            echo '<p>Error: '.$mensajeExcepcion.'</p>';
            //Mostrar el codigo de la excepcion
            echo '<p>Codigo de error: '.$errorExcepcion.'</p>';
        }finally{
            //Cerrar conexión
            unset($DAW205DB);
        }
        ?>
        <footer>
            <table>
                <tr>
                    <td><p>David del Prado Losada - DAW2</p></td>
                    <td><a href="https://github.com/DavidelPrado" target="_blank"><img src="../../img/git.png" width="50px" height="50px"></img></a></td>
                </tr>
            </table>
        </footer>
    </body>
</html>
