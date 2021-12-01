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
        <?php
            /*
             * @author: David del Prado Losada
             * @version: v1.Realizacion del ejercicio
             * Created on: 30/11/2021
             * Ventana de login
             */
        
            echo '<h1>PROYECTO LOGIN LOGOFF - LOGIN</h1>';
            
            
            include '../core/libreriaValidacion.php';
            include "../config/confDBPDO.php";
        
            //Definir constantes
            define("OBLIGATORIO", 1);
            define("OPCIONAL", 0);
            define("MIN_TAMANIO", 0);
            
            //Definir array para almacenar errores
            $aErrores=[
                "usuario"=>null,
                "contraseña"=>null,
            ];
            
            //Definir array para almacenar respuestas correctas
            $aCorrecto=[
                "usuario"=>null,
                "contraseña"=>null,
            ];
            
            //Inicializar variable que controlara si los campos estan correctos
            $entradaOK=true;
            
            //Comprobar si se ha pulsado el boton de cancelar
            if(isset($_REQUEST['cancelar'])){
                header("Location: ../index.php");
            }
            
            //Comprobar si se ha pulsado el boton de aceptar
            if(isset($_REQUEST['aceptar'])){
                //Comprobar si los campos son correctos
                $aErrores["usuario"]=validacionFormularios::comprobarAlfaNumerico($_REQUEST["usuario"], 255, MIN_TAMANIO, OBLIGATORIO);
                $aErrores["constraseña"]=validacionFormularios::comprobarAlfaNumerico($_REQUEST["contraseña"], 255, MIN_TAMANIO, OBLIGATORIO);
                    
                //Recorrer el array de errores para comprobar si hay algun error en el formulario
                foreach($aErrores as $nombreCampo=>$valor){
                    if($valor!=null){
                        $_REQUEST[$nombreCampo]="";//Si encuentra un error vacia el campo
                        $entradaOK=false;//Si se encuentra algun error se cambia la variable entradaOK a false
                    }
                }
                
            }else{
                //El formulario no se ha rellenado nunca
                $entradaOK=false;
            }
            
            
            //Comprobar si la entrada es correcta
            if($entradaOK){
                try{
                    //Almacenar las respuestas correctas en el array $aCorrecto
                    $aCorrecto=[
                        "usuario"=>$_REQUEST["usuario"],
                        "contraseña"=>$_REQUEST["contraseña"],
                    ];
                
                    //Conectar a la base de datos
                    $DAW205DB = new PDO(HOST, USER, PASSWORD);
                    //Configurar las excepciones
                    $DAW205DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    //Inicializar variables donde guardo el usuario y la contraseña
                    $usuario=$_SERVER['PHP_AUTH_USER'];
                    $password=$_SERVER['PHP_AUTH_PW'];
                    
                    //Query de seleccion
                    $consulta="SELECT * FROM T01_Usuario WHERE T01_CodUsuario='{$usuario}' AND T01_Password='{$password}'";
                    $resultado=$DAW205DBDepartamentos->prepare($consulta);
                    $resultado->execute();
                    
                    if($resultado->rowCount() > 0){
                        $oUsuario = $resultado->fetchObject();
                        //Encripto la contraseña
                        $passwordEncriptada = hash('sha256', ($usuario.$password));
                        if(($oUsuario->T01_CodUsuario!=$usuario)&&($oUsuario->T01_Password!=$passwordEncriptada)){
                            header('WWW-Authenticate: Basic realm="Contenido restringido"');
                            header("HTTP/1.0 401 Unauthorized");
                            exit;
                        }else{
                            echo "<p>Usuario: {$_SERVER['PHP_AUTH_USER']}</p>";
                            echo "<p>Contraseña: {$_SERVER['PHP_AUTH_PW']}</p>";
                        }
                    }
                    
                    header("Location: ./programa.php");
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
                
            }else{
                //Mostrar el fomulario
        ?>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method='post'>
                <fieldset>
                    <legend>Login:</legend>
                    
                    <label>Usuario:</label>
                    <input type='text' name='usuario' value="<?php
                        //Mostrar los datos correctos introducidos en un intento anterior
                        echo isset($_REQUEST["usuario"]) ? $_REQUEST["usuario"] : "";
                    ?>"/><p><?php
                        //Mostrar los errores en el volumen, si los hay
                        echo $aErrores["contraseña"]!=null ? $aErrores["contraseña"] : "";
                    ?></p>
                    
                    <label>Contraseña:</label>
                    <input type='password' name='contraseña' value="<?php
                        //Mostrar los datos correctos introducidos en un intento anterior
                        echo isset($_REQUEST["constraseña"]) ? $_REQUEST["contraseña"] : "";
                    ?>"/><p><?php
                        //Mostrar los errores en el volumen, si los hay
                        echo $aErrores["contraseña"]!=null ? $aErrores["contraseña"] : "";
                    ?></p>
                    <br>
                    <input type='submit' name='aceptar' value='Aceptar'/>
                    <input type='submit' name='cancelar' value='Cancelar'/>
                </fieldset>
            </form>
        <?php    
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
