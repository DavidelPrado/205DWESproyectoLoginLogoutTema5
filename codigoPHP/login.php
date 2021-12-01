<?php
    /*
     * @author: David del Prado Losada
     * @version: v1.Realizacion del ejercicio
     * Created on: 30/11/2021
     * Ventana de login
     */

    //Inicio de sesion
    session_start();

    echo '<h1>PROYECTO LOGIN LOGOFF - LOGIN</h1>';

    include '../core/libreriaValidacion.php';
    include "../config/confDBPDO.php";

    //Definir constantes
    define("OBLIGATORIO", 1);
    define("OPCIONAL", 0);
    define("MIN_TAMANIO", 0);

    //Definir array para almacenar errores
    $aErrores = [
        "usuario" => null,
        "contraseña" => null,
    ];

    //Definir array para almacenar respuestas correctas
    $aCorrecto = [
        "usuario" => null,
        "contraseña" => null,
    ];

    //Inicializar variable que controlara si los campos estan correctos
    $entradaOK = true;

    //Comprobar si se ha pulsado el boton de cancelar
    if (isset($_REQUEST['cancelar'])) {
        header("Location: ../index.php");
    }

    //Comprobar si se ha pulsado el boton de aceptar
    if (isset($_REQUEST['aceptar'])) {
        //Comprobar si los campos son correctos
        if (validacionFormularios::comprobarAlfaNumerico($_REQUEST["usuario"], 255, MIN_TAMANIO, OBLIGATORIO) || validacionFormularios::comprobarAlfaNumerico($_REQUEST["contraseña"], 255, MIN_TAMANIO, OBLIGATORIO)) {
            $entradaOK = true;
        }

        if ($entradaOK) {
            //Almacenar las respuestas correctas en el array $aCorrecto
            $aCorrecto = [
                "usuario" => $_REQUEST["usuario"],
                "contraseña" => $_REQUEST["contraseña"],
            ];

            try {
                //Conectar a la base de datos
                $DAW205DB = new PDO(HOST, USER, PASSWORD);
                //Configurar las excepciones
                $DAW205DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Query de seleccion
                $consulta = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario='{$aCorrecto['usuario']}'";
                $oResultado = $DAW205DB->prepare($consulta);
                $oResultado->execute();

                $resultado = $oResultado->fetchobject();
            } catch (PDOException $excepcion) {
                $errorExcepcion = $excepcion->getCode();
                $mensajeExcepcion = $excepcion->getMessage();

                //Mostrar el mensaje de la excepcion
                echo '<p>Error: ' . $mensajeExcepcion . '</p>';
                //Mostrar el codigo de la excepcion
                echo '<p>Codigo de error: ' . $errorExcepcion . '</p>';
            } finally {
                //Cerrar conexión
                unset($DAW205DB);
            }

            if (!$resultado || $resultado->T01_Password != hash('sha256', ($aCorrecto['usuario'] . $aCorrecto['contraseña']))) {
                $entradaOK = false;
            }
        }
    } else {
        //El formulario no se ha rellenado nunca
        $entradaOK = false;
    }

    if ($entradaOK) {
        $_SESSION['usuarioDAW205AppLoginLogout'] = $aCorrecto['usuario'];
        try {
            //Conectar a la base de datos
            $DAW205DB = new PDO(HOST, USER, PASSWORD);
            //Configurar las excepciones
            $DAW205DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $oDateTime = new DateTime();

            //Query de seleccion
            $consulta = <<<PDO
                    UPDATE T01_Usuario SET T01_NumConexiones=T01_NumConexiones+1,
                    T01_FechaHoraUltimaConexion = '{$oDateTime->format("y-m-d h:i:s")}'
                    WHERE T01_CodUsuario='{$aCorrecto['usuario']}'
                PDO;

            $oResultado = $DAW205DB->prepare($consulta);
            $oResultado->execute();
        } catch (PDOException $excepcion) {
            $errorExcepcion = $excepcion->getCode();
            $mensajeExcepcion = $excepcion->getMessage();

            //Mostrar el mensaje de la excepcion
            echo '<p>Error: ' . $mensajeExcepcion . '</p>';
            //Mostrar el codigo de la excepcion
            echo '<p>Codigo de error: ' . $errorExcepcion . '</p>';
        } finally {
            //Cerrar conexión
            unset($DAW205DB);
        }

        header("Location: ./programa.php");
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
            label{
                display: block;
            }
            form{
                text-align: center;
                position: absolute;
                right: 25%;
                width: 50vw;
                margin-top: 30px;
            }
            p{
                color: red;
            }
        </style>
        <title>Login</title>
    </head>
    <body>

        <form action="<?php $_SERVER['PHP_SELF'] ?>" method='post'>
            <fieldset>
                <legend>Login:</legend>

                <label>Usuario:</label>
                <input type='text' name='usuario'/>

                <label>Contraseña:</label>
                <input type='password' name='contraseña'/>
                <br><br>
                <input type='submit' name='aceptar' value='Aceptar'/>
                <input type='submit' name='cancelar' value='Cancelar'/>
            </fieldset>
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