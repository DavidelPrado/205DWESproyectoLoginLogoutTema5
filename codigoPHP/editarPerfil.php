<?php
    /*
     * @author: David del Prado Losada
     * @version: v1.Realizacion del ejercicio
     * Created on: 30/11/2021
     * Ventana de login
     */

    session_start();
    if (!isset($_SESSION['usuarioDAW205AppLoginLogoutTema5'])) {
        header('Location: login.php');
    }
    
    //Comprobar si se ha pulsado el boton de cancelar
    if(isset($_REQUEST['cancelar'])){
        header("Location: programa.php");
    }

    //Comprobar si se ha pulsado el boton de registrarse
    if(isset($_REQUEST['cambiar'])){
        header("Location: cambiarPassword.php");
    }
    
    include "../core/libreriaValidacion.php";
    include "../config/confDBPDO.php";

    //Definir constantes
    define("OBLIGATORIO", 1);
    define("OPCIONAL", 0);
    define("MIN_TAMANIO", 0);
    
    //Definir array para almacenar errores
    $aErrores=[
        "DescUsuario"=>null
    ];
    
    //Definir array para almacenar respuestas correctas
    $aCorrecto=[
        "DescUsuario"=>null
    ];
    
    //Inicializar variable que controlara si los campos estan correctos
    $entradaOK=true;
    
    try {
        //Conectar a la base de datos
        $DAW205DB = new PDO(HOST, USER, PASSWORD);
        //Configurar las excepciones
        $DAW205DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Query de seleccion
        $consulta = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario='".$_SESSION['usuarioDAW205AppLoginLogoutTema5']."'";
        $oResultado = $DAW205DB->prepare($consulta);
        $oResultado->execute();
        $resultado=$oResultado->fetchobject();

        $DescActual=$resultado->T01_DescUsuario;
    } catch (PDOException $excepcion) {
        $errorExcepcion = $excepcion->getCode();
        $mensajeExcepcion = $excepcion->getMessage();

        echo '<p>Error: ' . $mensajeExcepcion . '</p>';
        echo '<p>Codigo de error: ' . $errorExcepcion . '</p>';
    } finally {
        unset($DAW205DB);
    }

    if(isset($_REQUEST['eliminar'])){
        try{
            //Conectar a la base de datos
            $DAW205DB = new PDO(HOST, USER, PASSWORD);
            //Configurar las excepciones
            $DAW205DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //Query de actualizacion
            $consulta="DELETE FROM T01_Usuario WHERE T01_CodUsuario='".$_SESSION['usuarioDAW205AppLoginLogoutTema5']."'";
            $oResultado = $DAW205DB->prepare($consulta);
            $oResultado->execute();
            
            //Eliminar la sesion
            session_destroy();
            
            header("Location: login.php");
            exit;
        } catch (PDOException $excepcion) {
            $errorExcepcion = $excepcion->getCode();
            $mensajeExcepcion = $excepcion->getMessage();

            echo '<p>Error: ' . $mensajeExcepcion . '</p>';
            echo '<p>Codigo de error: ' . $errorExcepcion . '</p>';
        } finally {
            unset($DAW205DB);
        }
    }
    
    if(isset($_REQUEST['aceptar'])){
        $aErrores["DescUsuario"]=validacionFormularios::comprobarAlfaNumerico($_REQUEST["descripcion"], 255, MIN_TAMANIO, OBLIGATORIO);
    
        //Recorrer el array de errores para comprobar si hay algun error en el formulario
        foreach($aErrores as $nombreCampo=>$valor){
            if($valor!=null){
                $_REQUEST[$nombreCampo]="";//Si encuentra un error vacia el campo
                $entradaOK=false;//Si se encuentra algun error se cambia la variable entradaOK a false
            }
        }
    }else{
        $entradaOK=false;
    }
    
    if($entradaOK){
        //Almacenar las respuestas correctas en el array $aCorrecto
        $aCorrecto = [
            "DescUsuario"=>$_REQUEST["descripcion"],
        ];
        try {
            //Conectar a la base de datos
            $DAW205DB = new PDO(HOST, USER, PASSWORD);
            //Configurar las excepciones
            $DAW205DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $oDateTime = new DateTime();

            //Query de actualizacion
            $consulta="UPDATE T01_Usuario SET T01_DescUsuario='{$aCorrecto["DescUsuario"]}' WHERE T01_CodUsuario='".$_SESSION['usuarioDAW205AppLoginLogoutTema5']."'";
            $oResultado = $DAW205DB->prepare($consulta);
            $oResultado->execute();
            
            header("Location: programa.php");
            exit;
        } catch (PDOException $excepcion) {
            $errorExcepcion = $excepcion->getCode();
            $mensajeExcepcion = $excepcion->getMessage();

            echo '<p>Error: ' . $mensajeExcepcion . '</p>';
            echo '<p>Codigo de error: ' . $errorExcepcion . '</p>';
        } finally {
            unset($DAW205DB);
        }
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
            label{
                display: block;
                font-weight: bold;
            }
            form{
                text-align: center;
                position: absolute;
                right: 25%;
                width: 50vw;
                margin-top: 30px;
            }
            p{
                color: white;
            }
        </style>
        <title>Login</title>
    </head>
    <body>
        <h1>PROYECTO LOGIN LOGOFF - LOGIN</h1>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method='post'>
            <legend><h2>Editar perfil:</h2></legend>

                <label>Usuario:</label><br>
                <input type='text' name='usuario' disabled value="<?php
                    echo $_SESSION['usuarioDAW205AppLoginLogoutTema5']
                ?>"/><br><br>

                <label>Descripción:</label><br>
                <input type='text' name='descripcion' value="<?php
                    echo $DescActual;
                ?>"/><p ><?php
                    //Mostrar los errores en la descripcion, si los hay
                    echo $aErrores["DescUsuario"]!=null ? $aErrores["DescUsuario"] : "";
                ?></p><br><br>
                
                <input type='submit' name='aceptar' value='Aceptar'/>
                <input type='submit' name='cancelar' value='Cancelar'/>
                <input type='submit' name='eliminar' value='Eliminar'/>
                <input type='submit' name='cambiar' value='Cambiar Contraseña'/>
        </form>

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