<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="webroot/css/estilo.css">
        <style>
            .main{
                margin-left: 35%;
                width: 30%;
                height: 50px;
                background-color: rgba(165, 42, 42 ,0.9);
                text-align: center;
                line-height: 50px;
            }
            .btn{
                font-size: 20px;
                color: white;
                font-weight: bold;
            }
        </style>
        <title>Index proyecto Login Logout</title>
    </head>
    <body>
        <?php
            
            //Incluir el archivo de conexión con la base de datos
            require_once "config/confDBPDO.php";

            try{
                //Conectar a la base de datos
                $DAW2105DBDepartamentos = new PDO(HOST, USER, PASSWORD);
                //Configurar las excepciones
                $DAW2105DBDepartamentos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                //Query de seleccion del contenido de la tabla Departamento
                $consulta="SELECT * FROM T01_Usuario";
                $oResultado=$DAW2105DBDepartamentos->prepare($consulta);
                $oResultado->execute();
                
                $oDepartamento=$oResultado->fetchObject();
                
                echo '<table>';
                while($oDepartamento){
                    echo '<tr>';
                    foreach ($oDepartamento as $valor) {
                        echo "<td>$valor</td>";
                    }
                    echo '</tr>';
                    $oDepartamento=$oResultado->fetchObject();
                }
                echo '</table>';
            }catch(PDOException $excepcion){
                $errorExcepcion=$excepcion->getCode();
                $mensajeExcepcion=$excepcion->getMessage();
                
                //Mostrar el mensaje de la excepcion
                echo '<p>Error: '.$mensajeExcepcion.'</p>';
                //Mostrar el codigo de la excepcion
                echo '<p>Codigo de error: '.$errorExcepcion.'</p>';
            }finally{
                //Cerrar conexión
                unset($DAW2105DBDepartamentos);
            }
        ?>
        <h1>Index proyecto Login Logout</h1>
        <div class="main">
            <a href="./codigoPHP/login.php" class="btn">Login</a>
        </div>
        
        <footer>
            <table>
                <tr>
                    <td><p>David del Prado Losada - 03/12/2021 V2.0 - DAW2</p></td>
                    <td><a href="https://github.com/DavidelPrado/205DWESproyectoLoginLogoutTema5" target="_blank"><img src="../img/git.png" width="50px" height="50px"></img></a></td>
                </tr>
            </table>
        </footer>
    </body>
</html>
