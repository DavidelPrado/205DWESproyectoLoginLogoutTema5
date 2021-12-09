<?php
    /*
     * @author: David del Prado Losada
     * @version: v1.Realizacion del ejercicio
     * Created on: 08/12/2021
     * Ventana de registro de un nuevo usuario
     */

    //Comprobar si se ha pulsado el boton de cancelar
    if(isset($_REQUEST['cancelar'])){
        header("Location: login.php");
    }
    
    include "../core/libreriaValidacion.php";
    include "../config/confDBPDO.php";

    //Definir constantes
    define("OBLIGATORIO", 1);
    define("OPCIONAL", 0);
    define("MIN_TAMANIO", 0);
    
    //Definir array para almacenar errores
    $aErrores=[
        "CodUsuario"=>null,
        "Password"=>null,
        "RepetirPassword"=>null,
        "DescUsuario"=>null
    ];
    
    //Definir array para almacenar respuestas correctas
    $aCorrecto=[
        "CodUsuario"=>null,
        "Password"=>null,
        "RepetirPassword"=>null,
        "DescUsuario"=>null
    ];
    
    //Inicializar variable que controlara si los campos estan correctos
    $entradaOK=true;

    //Comprobar si se ha pulsado el boton de crear
    if(isset($_REQUEST['crear'])){
        $aErrores["CodUsuario"]=validacionFormularios::comprobarAlfaNumerico($_REQUEST["CodUsuario"], 8, MIN_TAMANIO, OBLIGATORIO);
        $aErrores["Password"]=validacionFormularios::validarPassword($_REQUEST["Password"], 8, MIN_TAMANIO, 1, OBLIGATORIO);
        $aErrores["RepetirPassword"]=validacionFormularios::validarPassword($_REQUEST["RepetirPassword"], 8, MIN_TAMANIO, 1, OBLIGATORIO);
        $aErrores["DescUsuario"]=validacionFormularios::comprobarAlfaNumerico($_REQUEST["DescUsuario"], 255, MIN_TAMANIO, OBLIGATORIO);
        
        if($aErrores["CodUsuario"]==null && $aErrores["Password"]==null && $aErrores["RepetirPassword"]==null && $aErrores["DescUsuario"]==null){
            //Almacenar las respuestas correctas en el array $aCorrecto
            $aCorrecto=[
                "CodUsuario" => $_REQUEST["CodUsuario"],
                "Password" => $_REQUEST["Password"],
                "RepetirPassword" => $_REQUEST["RepetirPassword"],
                "DescUsuario" => $_REQUEST["DescUsuario"]
            ];

            try {
                //Conectar a la base de datos
                $DAW205DB = new PDO(HOST, USER, PASSWORD);
                //Configurar las excepciones
                $DAW205DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Query de seleccion
                $consulta = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario='{$aCorrecto['CodUsuario']}'";
                $oResultado = $DAW205DB->prepare($consulta);
                $oResultado->execute();
                $resultado=$oResultado->fetchobject();
                
                //Si la consulta es mayor que 0, el usuario ya existe en la DB
                if($oResultado->rowCount()>0){ 
                    $aErrores['CodUsuario'] = "El usuario ya existe.";
                }
                
                //Los dos campos deben ser iguales
                if($aCorrecto['Password']!=$aCorrecto['RepetirPassword']){ 
                    $aErrores['RepetirPassword']="Las contraseñas no coinciden.";
                }
            } catch (PDOException $excepcion) {
                $errorExcepcion = $excepcion->getCode();
                $mensajeExcepcion = $excepcion->getMessage();

                echo '<p>Error: ' . $mensajeExcepcion . '</p>';
                echo '<p>Codigo de error: ' . $errorExcepcion . '</p>';
            } finally {
                unset($DAW205DB);
            }
        }
    } else {
        //El formulario no se ha rellenado nunca
        $entradaOK = false;
    }

    if ($entradaOK) {
        try {
            //Conectar a la base de datos
            $DAW205DB = new PDO(HOST, USER, PASSWORD);
            //Configurar las excepciones
            $DAW205DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $aCorrecto['Password']=hash('sha256', ($aCorrecto['CodUsuario'].$aCorrecto['Password']));
            //Query de inserccion
            $consulta=<<<SQL
                    INSERT INTO T01_Usuario(T01_CodUsuario, T01_Password, T01_DescUsuario) 
                    VALUES ('{$aCorrecto['CodUsuario']}', '{$aCorrecto['Password']}', '{$aCorrecto['DescUsuario']}');
                    SQL;
            $oResultado = $DAW205DB->prepare($consulta);
            $oResultado->execute();
            
            $oDateTime = new DateTime();
            
            //Query de actualizacion
            $consulta="UPDATE T01_Usuario SET T01_FechaHoraUltimaConexion='{$oDateTime->format("y-m-d h:i:s")}', T01_NumConexiones=T01_NumConexiones+1 WHERE T01_CodUsuario='{$aCorrecto['CodUsuario']}'";
            $oResultado = $DAW205DB->prepare($consulta);
            $oResultado->execute();

            //Inicar la session
            session_start();
            //Almacenar el nombre del usuario y la ultima conexion en $_SESSION
            $_SESSION['usuarioDAW205AppLoginLogoutTema5']=$aCorrecto['CodUsuario'];
            $_SESSION['FechaHoraUltimaConexionAnterior']=$FechaHoraUltimaConexionAnterior;

            header("Location: programa.php");
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
            td>p{
                color: white;
            }
        </style>
        <title>Registro</title>
    </head>
    <body>
        <h1>PROYECTO LOGIN LOGOFF - REGISTRO</h1>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method='post'>
            <legend><h2>Registro de un nuevo usuario:</h2></legend>

                <label>Usuario:</label><br>
                <input type='text' name='CodUsuario' value="<?php
                    //Mostrar los datos correctos introducidos en un intento anterior
                    echo isset($_REQUEST["CodUsuario"]) ? $_REQUEST["CodUsuario"] : "";
                ?>"/><p ><?php
                    //Mostrar los errores en el CodUsuario, si los hay
                    echo $aErrores["CodUsuario"]!=null ? $aErrores["CodUsuario"] : "";
                ?></p>

                <label>Contraseña:</label><br>
                <input type='password' name="Password" value="<?php
                    //Mostrar los datos correctos introducidos en un intento anterior
                    echo isset($_REQUEST["Password"]) ? $_REQUEST["Password"] : "";
                ?>"/><p ><?php
                    //Mostrar los errores en la contraseña, si los hay
                    echo $aErrores["Password"]!=null ? $aErrores["Password"] : "";
                ?></p>
                      
                <label>Repetir contraseña:</label><br>
                <input type='password' name="RepetirPassword" value="<?php
                    //Mostrar los datos correctos introducidos en un intento anterior
                    echo isset($_REQUEST["RepetirPassword"]) ? $_REQUEST["RepetirPassword"] : "";
                ?>"/><p ><?php
                    //Mostrar los errores en la contraseña, si los hay
                    echo $aErrores["RepetirPassword"]!=null ? $aErrores["RepetirPassword"] : "";
                ?></p>
                            
                <label>Descripcion:</label>
                <input type="text" name="DescUsuario" value="<?php
                    //Mostrar los datos correctos introducidos en un intento anterior
                    echo isset($_REQUEST["DescUsuario"]) ? $_REQUEST["DescUsuario"] : "";
                ?>"/><p ><?php
                    //Mostrar los errores en la DescUsuario, si los hay
                    echo $aErrores["DescUsuario"]!=null ? $aErrores["DescUsuario"] : "";
                ?></p>


                <input type='submit' name='crear' value='Crear'/>
                <input type='submit' name='cancelar' value='Cancelar'/>
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