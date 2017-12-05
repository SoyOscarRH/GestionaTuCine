<?php
    /*=======================================================================================================================
    ============================================        CHANGE MY PROFILE              ======================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE FOR THE PERSON TO CHANGE */
    include("PHP/ForAllPages.php");                                                             //Dame todas las ventajas

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Mi Perfil';                                                                  //Titulo de cada Pagina
    $UpdateDate = '10 de Noviembre del 2017';                                                   //Fecha de actualizacion de pagina

    $AlertMessages = array();                                                                   //Mensajes que mostramos 
    $InfoEmployees = array();                                                                   //Info de los empleados  

     

    StandardCheckForStartedSession();                                                           //Asegurate de que pueda estar aqui
    $DataBase = StandardCheckForCorrectDataBase();                                              //Asegurate de que pueda estar aqui


    if (isset($_POST['CheckDataToChangeValues'])) {
        do {                                                                                    //while para usar el break XD
            //=============  GET THE DATA =============
            $NewName     = ClearSQLInyection(htmlspecialchars(trim($_POST['Name'])));           //Dame la info
            $NewSurname1 = ClearSQLInyection(htmlspecialchars(trim($_POST['Surname1'])));       //Dame la info
            $NewSurname2 = ClearSQLInyection(htmlspecialchars(trim($_POST['Surname2'])));       //Dame la info
            $NewEmail    = ClearSQLInyection(htmlspecialchars(trim($_POST['Email'])));          //Dame la info

            $Password  = ClearSQLInyection(htmlspecialchars(trim($_POST['Password'])));         //Dame la info
            $NewPassword1 = ClearSQLInyection(htmlspecialchars(trim($_POST['NewPassword1'])));  //Dame la info
            $NewPassword2 = ClearSQLInyection(htmlspecialchars(trim($_POST['NewPassword2'])));  //Dame la info
            
            $Password = sha1($Password."ManageYourCinemaSalt");                                 //Esta es la de verdad
            if ($NewPassword1 != $NewPassword2) {                                               //Eres valido
                array_push($AlertMessages, "Las constraseñas no coinciden"); break;}            //O no?
            else if ($NewPassword1 == '') $NewPassword = $Password;                             //Si no pusieron nada dejalos en paz
            else $NewPassword = sha1($NewPassword1."ManageYourCinemaSalt");                     //Esta es la de verdad

            $NewSex = ClearSQLInyection(htmlspecialchars(trim($_POST['Sex'])));                 //Dame el turno
            if ($NewSex != "Masculino" and $NewSex != "Femenino") {                             //Eres valido
                array_push($AlertMessages, "Sexo no válido :/"); break;}                        //O no?

            //=============  CHANGE THE DATA =============
            $ValidQuery = $DataBase->query("
                UPDATE Empleado
                SET Nombre = '{$NewName}', ApellidoPaterno = '{$NewSurname1}', 
                    ApellidoMaterno = '{$NewSurname2}', Correo = '{$NewEmail}',
                    Genero = '{$NewSex}', Contrasena = '{$NewPassword}'
                WHERE ID = {$_SESSION['ID']} AND Contrasena = '{$Password}';");                 //AHORA SI PUEDES HACER ESTO

            $NewDataFromBase = $DataBase->query("
                SELECT * FROM Empleado WHERE ID = {$_SESSION['ID']};");                         //AHORA SI PUEDES HACER ESTO

            if (!$ValidQuery or !$NewDataFromBase)
                {array_push($AlertMessages, "Error al Actualizar Datos"); break;}               //Envia mensaje si todo mal
            else {                                                                              //Si es que todo bien :D
                array_push($AlertMessages, "Datos Actualizados");                               //Actualiza valores de Sesion
                $_SESSION = array_merge($_SESSION, $NewDataFromBase->fetch_assoc());            //Actualiza valores de Sesion
                $_SESSION["CompleteUserName"] = $_SESSION['Nombre'];                            //Actualiza valores de Sesion
                $_SESSION["CompleteUserName"].= " ".$_SESSION['ApellidoPaterno'];               //Dame su info
                $_SESSION["CompleteUserName"].= " ".$_SESSION['ApellidoMaterno'];               //Dame su info
                break;
            }                    

        } while (false);                                                                        //Solo eras para el break
    }
    
    // *****************************************************************************************
    // *************************     PROCESS TO START THE SYSTEM   *****************************
    // *****************************************************************************************
    $StandardGreyCard = "card grey lighten-4 col s12 m8 l8 offset-m2 offset-l2";                //Es una forma de que sea mas sencilla 


    include("PHP/HTMLHeader.php");                                                              //Incluimos un Asombroso Encabezado
?>

    <br><br>
    <div class="container center-align">


        <!-- ================================================================== -->    
        <!-- =====================    ALTER ME            ===================== -->      
        <!-- ================================================================== -->    
        <div class="<?php echo $StandardGreyCard;?>">

            <div class="card-image">
                <a id="Edit" name="Edit" class="btn-floating halfway-fab waves-effect waves-light btn-large red">
                    <i class="material-icons">edit</i>
                </a>
            </div>

            <div class="card-content">

                <!-- ========  TITLE  ================ -->
                <h4 class="grey-text text-darken-2"><b>Ve y Modifica </b> tu Información Personal</h4>
            
                <!-- ========  TEXT  ================ -->
                <span class="grey-text" style="font-size: 1.25rem;">
                    Ve tu información, oprime el botón superior para editar
                    <br><br>
                </span>

                <!-- ========  MATERIAL FORM  ================ -->
                <form class="container" action="MyProfile.php" method="post">

                    <!-- =========  TITLE ====-->
                    <h5 class="grey-text text-darken-2 left-align"><b>Información</b> Básica</h5><br>
        
                        <!-- ========  NAME ============= -->
                        <div class='input-field'>
                            <input
                                disabled
                                class = 'validate'
                                type  = 'text'
                                name  = 'Name'
                                id    = 'Name'
                                value = "<?php echo $_SESSION['Nombre'];?>" />
                            <label>Nombre</label>
                        </div>

                        <!-- ========  SURNAME 1 ============= -->
                        <div class='input-field'>
                            <input
                                disabled
                                class = 'validate'
                                type  = 'text'
                                name  = 'Surname1'
                                id    = 'Surname1'
                                value = "<?php echo $_SESSION['ApellidoPaterno'];?>" />
                            <label>Apellido Paterno</label>
                        </div>

                        <!-- ========  SURNAME 2 ============= -->
                        <div class='input-field'>
                            <input
                                disabled
                                class = 'validate'
                                type  = 'text'
                                name  = 'Surname2'
                                id    = 'Surname2'
                                value = "<?php echo $_SESSION['ApellidoMaterno'];?>" />
                            <label>Apellido Materno</label>
                        </div>

                        <!-- ========  EMAIL ============= -->
                        <div class='input-field'>
                            <input
                                disabled
                                class = 'validate'
                                type  = 'email'
                                name  = 'Email'
                                id    = 'Email'
                                value = "<?php echo $_SESSION['Correo'];?>" />
                            <label>Email</label>
                        </div>

                        <!-- ========  SEX ========== -->
                        <div class="input-field">                        
                            <select disabled name="Sex" id="Sex">
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                            <label>Sexo</label>
                        </div>

                    <br><br>

                    <div id="PasswordSection" name="PasswordSection" style="display: none;">

                        <!-- =========  TITLE ====-->
                        <h5 class="grey-text text-darken-2 left-align">
                            <b>Contraseña para Verificar tu Identidad</b>
                        </h5>

                        <br>

                        <!-- ========  EMAIL ============= -->
                        <div class='input-field'>
                            <input
                                required
                                class = 'validate'
                                type  = 'password'
                                name  = 'Password'
                                id    = 'Password' />
                            <label>Contraseña Actual</label>
                        </div>

                        <!-- ========  SWITCH ============= -->
                        <div id="ChangePassword" class="switch left-align">
                            <label>
                                Usa Contraseña Actual
                                <input required type="checkbox">
                                <span class="lever"></span>
                                Cambia Contraseña 
                            </label>
                        </div>

                        <div id="SectionNewPassword" style="display: none;">
                            
                            <!-- ========  EMAIL ============= -->
                            <div class='input-field'>
                                <input
                                    class = 'validate'
                                    type  = 'password'
                                    name  = 'NewPassword1'
                                    id    = 'NewPassword1' />
                                <label>Escribe la Nueva Contraseña</label>
                            </div>

                            <!-- ========  EMAIL ============= -->
                            <div class='input-field'>
                                <input
                                    class = 'validate'
                                    type  = 'password'
                                    name  = 'NewPassword2'
                                    id    = 'NewPassword2' />
                                <label>Confirma la Nueva Contraseña</label>
                            </div>

                        </div>

                        <script>
                            $('#ChangePassword').change (function() {                           //Cada vez que le piques
                                $('#SectionNewPassword').toggle();                              //Alterna la parte de la contraseña
                            });
                        </script>

                        <br><br>
                        
                    </div>

                    <!-- ========  BUTTON TO SEND ===== -->
                    <button
                        style='display: none;'
                        type='submit'
                        name='CheckDataToChangeValues'
                        id='CheckDataToChangeValues'
                        class='col btn-large waves-effect indigo lighten-1'>
                        Actualiza los datos
                    </button>

                </form>
            </div>
            
        </div>

        <br>
        <br>
        <br>

        <!-- ================================================================== -->    
        <!-- =====================    INFO ABOUT ME        ==================== -->      
        <!-- ================================================================== -->    
        <div class="<?php echo $StandardGreyCard;?>">

            <div class="card-content">

                <form class="container">
                
                    <!-- =========  TITLE ====-->
                    <h4 class="grey-text text-darken-2 left-align"><b>Información</b> de tu Usuario</h4><br>

                    <!-- ========  SURNAME 2 ============= -->
                    <div class='input-field'>
                        <input
                            disabled
                            class = 'validate'
                            type  = 'number'
                            name  = 'Money'
                            id    = 'Money'
                            value = "<?php echo $_SESSION['Sueldo'];?>" />
                        <label>Sueldo</label>
                    </div>

                    <!-- ========  WORK TIME ========== -->
                    <div class="input-field">                        
                        <select disabled name="Turn" id="Turn">
                            <option value="Matutino">Matutino</option>
                            <option value="Vespetirno">Vespertino</option>
                        </select>
                        <label>Turno</label>
                    </div>

                    <!-- ========  WORK  ========== -->
                    <div class="input-field">                        
                        <select disabled name="Rol" id="Rol">
                            <option value="1"><?php echo $_SESSION['RolActual'];?></option>
                        </select>
                        <label>Rol Actual</label>
                    </div>


                </form>
            </div>
            
        </div>


        <br><br>

    </div> 




    <!-- ================================================================= -->    
    <!-- =======================    CODE FOR THE PAGE   ================== -->    
    <!-- ================================================================= -->
    <script>
        $(document).ready(function() {
            $('select').material_select();                                                  //SIEMPRE ACTUALIZA LOS SELECTS!

            <?php 
                $TitleAlert = '<span class = "yellow-text"><b>Alerta: &nbsp; </b></span>';  //El mini titulo
                foreach ($AlertMessages as $Alert):?>                                       //Para cada Alert
                Materialize.toast(<?php echo "'{$TitleAlert} {$Alert}'";?>, 4000);          //Muestralo por 4 segundos
            <?php endforeach;?> 

            $('#Edit').click (function() {                                                  //Cada vez que le piques
                $("#Name").prop('disabled', ! $("#Name").prop('disabled'));                 //Alterna entre visto e invisible
                $("#Surname1").prop('disabled', ! $("#Surname1").prop('disabled'));         //Alterna entre visto e invisible
                $("#Surname2").prop('disabled', ! $("#Surname2").prop('disabled'));         //Alterna entre visto e invisible
                $("#Email").prop('disabled', ! $("#Email").prop('disabled'));               //Alterna entre visto e invisible
                $("#Sex").prop('disabled', ! $("#Sex").prop('disabled'));                   //Alterna entre visto e invisible
                $('#PasswordSection').toggle();                                             //Alterna la parte de la contraseña
                $('#CheckDataToChangeValues').toggle();                                     //Alterna el boton

                $('select').material_select();                                              //Actualiza el select
            });

        });
    </script>



<?php include("PHP/HTMLFooter.php"); if (isset($DataBase)) $DataBase->close(); ?>

