<?php
    /*=======================================================================================================================
    ============================================         LOGIN PROMPT          ==============================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE */
    include("PHP/ForAllPages.php");                                                             //Dame todas las ventajas que tiene incluir

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Inicia Sesión';                                                              //Titulo de cada Pagina
    $UpdateDate = '23 de Julio del 2017';                                                       //Fecha de actualizacion de la pagina

    // ========== SPECIFIC FOR THIS SCRIPT ==========
    $ErrorAccountMessage  = "";                                                                 //Mensajes para el usuario final 
    $ErrorPasswordMessage = "";                                                                 //Mensajes para el usuario final

    $UserName = "";                                                                             //Email
    $Password = "";                                                                             //Contraseña            


    // ========== WAIT ... WE HAVE ALREADY STAR SESSION? ==========
    if (!empty($_SESSION)) {header("Location: MenuEmployeeOrManager.php"); exit();}             //Envia a la pagina correcta



    // ================================================================================
    // ======================  VERIFIQUEMOS EL USUARIO     ============================
    // ================================================================================
    if ( isset($_POST['CheckDataToEnterSystem']) ){                                             //Si quieres acceder a la info

        // ======================  TRY TO GET ACCOUNT NUMBER   ============================
        if (isset($_POST['UserName']) == true and isset($_POST['Password']) == true) {          //Hay informacion?
            $UserName = ClearSQLInyection(htmlspecialchars(trim($_POST['UserName'])));          //Limpia numero de cuenta
            $Password = ClearSQLInyection(htmlspecialchars(trim($_POST['Password'])));          //Limpia numero de cuenta

            $DataBase = @new mysqli("127.0.0.1", "root", "root", "Proyect");                    //Abrir una conexión
            if ((mysqli_connect_errno() != 0) or !$DataBase) {                                  //Si hubo problemas
                $TitleErrorPage      = "Error con la BD";                                       //Error variables
                $MessageErrorPage    = "No podemos acceder a la base de datos";                 //Error variables
                $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                           //Error variables
                $ButtonTextErrorPage = "Intenta otra vez";                                      //Error variables

                include("Error.php");                                                           //Llama a la pagina de error
                exit();                                                                         //Adios vaquero
            }

            $QueryResult = $DataBase->query('
                SELECT * FROM Empleado WHERE Correo = "'.$UserName.'";');                       //Haz la consulta

            if ($QueryResult->num_rows > 0) {                                                   //Si es de verdad el men existe
                $Row = $QueryResult->fetch_assoc();                                             //Entonces dame el resultado

                if (sha1($Password."ManageYourCinemaSalt") == $Row['Contrasena']) {             //Si es que contraseña correcta
                    session_start();                                                            //Inicia la Sesion :0
                    $_SESSION = array_merge($_SESSION, $Row);
                    $_SESSION["CompleteUserName"] = $Row['Nombre']." ".$Row['ApellidoPaterno'];//Dame su info
                    $_SESSION["CompleteUserName"].= " ".$Row['ApellidoMaterno'];               //Dame su info

                    $_SESSION["IAmAManager"] = ($_SESSION["IDGerente"] == $_SESSION["ID"]);     //Dice true si eres gerente

                    header("Location: MenuEmployeeOrManager.php");                              //Envia a link
                    exit();                                                                     //Y ahora sal!
                }
                else $ErrorPasswordMessage = "Contraseña Incorrecta";                           //Contraseña incorrecta
            }
            else $ErrorAccountMessage = "NO existe el Usuario en la Base de Datos";             //No existe usuario
        }
    }

    include("PHP/HTMLHeader.php");                                                              //Incluimos un Asombroso Encabezado
?>

<br>
<br>


<?php 
//==================================================================================
//=============================    SESION LOGIN       ==============================
//==================================================================================
?>
    <div class="container center-align row">

        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">

            <form action="Login.php" method="post">
                
                <h4 class="grey-text text-darken-2"><br>Iniciar Sesión</h4>

                <span class="grey-text">
                    Para acceder sesión usa tu ID o tu Correo para acceder al
                    Sistema
                    <br><br>
                </span>

                <div class='row'>
                    <div class='input-field col s10 m8 l8 offset-s1 offset-m2 offset-l2'>
                        <input class='validate' type='email' name='UserName' id='UserName' />
                        <label for='UserName'>Tu Correo Electrónico</label>
                    </div>
                </div>

                <div class='row'>
                    <div class='input-field col s10 m8 l8 offset-s1 offset-m2 offset-l2'>
                        <input class='validate' type='password' name='Password' id='Password' />
                        <label for='Password'>Tu contraseña</label>
                    </div>
                </div>

                <br />

                <div class='row'>
                    <button 
                        type='submit'
                        name='CheckDataToEnterSystem'
                        class='col s10 m6 l6 offset-s1 offset-m3 offset-l3 btn btn-large waves-effect indigo lighten-1'>
                        Iniciar Sesión
                    </button>
                </div>

                <br />

            </form>
        </div>
    </div>

    <br><br>


    <script>
        $(document).ready(function() {
            <?php 
                $ErrorSymbol = '<span class = "yellow-text"><b>Error: &nbsp; </b></span>';
                
                if ($ErrorAccountMessage != "")                                                         //Envia un mensaje si paso algo
                    echo "Materialize.toast('{$ErrorSymbol} {$ErrorAccountMessage}', 9000);";           //Envia esto

                if ($ErrorPasswordMessage != "")                                                        //Envia mensaje si paso algo
                    echo "Materialize.toast('{$ErrorSymbol} {$ErrorPasswordMessage}', 9000);";          //Esto
            ?>
        });
    </script>

<?php include("PHP/HTMLFooter.php"); ?>


