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

    StandardCheckForStartedSession();                                                           //Asegurate de que pueda estar aqui
    $DataBase = StandardCheckForCorrectDataBase();                                              //Asegurate de que pueda estar aqui




    /*===================================================================
    ============         GET THE DATABASE      ==========================
    ===================================================================*/

    //=============  IF YOU WANT TO MODIFY THE DATA  =============
    if (isset($_POST['CheckDataToChangeValues'])) {                                             //Si es que quieres crear valores

        do {                                                                                    //While para usar el break XD
            //=============  GET AND CHECK THE DATA =============
            $NewName      = ClearSQLInyection($_POST['Name']);                                  //Dame la info
            $NewSurname1  = ClearSQLInyection($_POST['Surname1']);                              //Dame la info
            $NewSurname2  = ClearSQLInyection($_POST['Surname2']);                              //Dame la info
            $NewEmail     = ClearSQLInyection($_POST['Email']);                                 //Dame la info

            $Password     = ClearSQLInyection($_POST['Password']);                              //Dame la info
            $NewPassword1 = ClearSQLInyection($_POST['NewPassword1']);                          //Dame la info
            $NewPassword2 = ClearSQLInyection($_POST['NewPassword2']);                          //Dame la info
            $NewSex       = ClearSQLInyection($_POST['Sex']);                                   //Dame el turno
            
            if ($NewSex != "Masculino" and $NewSex != "Femenino")                               //Eres valido
                {array_push($AlertMessages, "Sexo no válido :/"); break;}                       //O no?

            //=============  THINGS ABOUT THE PASSWORD  ==========
            $CurrentPassword = sha1($Password."ManageYourCinemaSalt");                          //Ponle el pass de verdad :D

            if (isset($_POST['UserWantsNewPassword'])) {                                        //Si es que quieres nuevo pass
                if ($NewPassword1 != $NewPassword2)                                             //Eres valido
                    {array_push($AlertMessages, "Las contraseñas no coinciden"); break;}        //O no?
                else $NewPassword = sha1($NewPassword1."ManageYourCinemaSalt");                 //Esta es la de verdad
            }
            else $NewPassword = $CurrentPassword;                                               //Si no pusieron nada dejalos en paz


            //=============  CHANGE THE DATA =============
            $ValidQuery = $DataBase->query("
                UPDATE Empleado
                    SET Nombre = '{$NewName}', 
                        ApellidoPaterno = '{$NewSurname1}', 
                        ApellidoMaterno = '{$NewSurname2}', 
                        Correo = '{$NewEmail}',
                        Genero = '{$NewSex}',
                        Contrasena = '{$NewPassword}'
                    WHERE 
                        ID = {$_SESSION['ID']} AND
                        Contrasena = '{$CurrentPassword}'");                                    //AHORA SI PUEDES HACER ESTO

            if (!$ValidQuery)                                                                   //Si es que no era la contra
                {array_push($AlertMessages, "Contraseña Incorrecta"); break;}                   //Envia mensaje si todo mal

            $NewDataFromBase = $DataBase->query("
                SELECT * FROM Empleado WHERE ID = {$_SESSION['ID']};");                         //AHORA SI PUEDES HACER ESTO

            if (!$NewDataFromBase)
                {array_push($AlertMessages, "Error Desconocido. Cierre Sesión. Porfa"); break;} //Envia mensaje si todo mal


            //=============  CHANGE TO THE NEW DATA =============
            array_push($AlertMessages, "Datos Actualizados");                                   //Actualiza valores de Sesion
            
            $_SESSION = array_merge($_SESSION, $NewDataFromBase->fetch_assoc());                //Actualiza valores de Sesion
            
            $_SESSION["CompleteUserName"] = $_SESSION['Nombre'];                                //Actualiza valores de Sesion
            $_SESSION["CompleteUserName"].= " ".$_SESSION['ApellidoPaterno'];                   //Dame su info
            $_SESSION["CompleteUserName"].= " ".$_SESSION['ApellidoMaterno'];                   //Dame su info

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
                                id    = 'Name'
                                name  = 'Name'
                                value = "<?php echo $_SESSION['Nombre'];?>" />
                            <label>Nombre</label>
                        </div>

                        <!-- ========  SURNAME 1 ============= -->
                        <div class='input-field'>
                            <input
                                disabled
                                class = 'validate'
                                type  = 'text'
                                id    = 'Surname1'
                                name  = 'Surname1'
                                value = "<?php echo $_SESSION['ApellidoPaterno'];?>" />
                            <label>Apellido Paterno</label>
                        </div>

                        <!-- ========  SURNAME 2 ============= -->
                        <div class='input-field'>
                            <input
                                disabled
                                class = 'validate'
                                type  = 'text'
                                id    = 'Surname2'
                                name  = 'Surname2'
                                value = "<?php echo $_SESSION['ApellidoMaterno'];?>" />
                            <label>Apellido Materno</label>
                        </div>

                        <!-- ========  EMAIL ============= -->
                        <div class='input-field'>
                            <input
                                disabled
                                class = 'validate'
                                type  = 'email'
                                id    = 'Email'
                                name  = 'Email'
                                value = "<?php echo $_SESSION['Correo'];?>" />
                            <label>Email</label>
                        </div>

                        <!-- ========  SEX ========== -->
                        <div class="input-field">                        
                            <select disabled id="Sex" name="Sex">
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
                                id    = 'Password'
                                name  = 'Password' />
                            <label>Contraseña Actual</label>
                        </div>

                        <!-- ========  SWITCH ============= -->
                        <div id="ChangePassword" name="ChangePassword" class="switch left-align">
                            <label>
                                Usa Contraseña Actual
                                <input type="checkbox" id="UserWantsNewPassword" name="UserWantsNewPassword">
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
                                    id    = 'NewPassword1'
                                    name  = 'NewPassword1' />
                                <label>Escribe la Nueva Contraseña</label>
                            </div>

                            <!-- ========  EMAIL ============= -->
                            <div class='input-field'>
                                <input
                                    class = 'validate'
                                    type  = 'password'
                                    id    = 'NewPassword2'
                                    name  = 'NewPassword2' />
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
                        style = 'display: none;'
                        type  = 'submit'
                        id    = 'CheckDataToChangeValues'
                        name  = 'CheckDataToChangeValues'
                        class = 'col btn-large waves-effect indigo lighten-1'>
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

            <div class="card-content container">
                
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

            </div>
            
        </div>


        <br><br>

    </div> 




    <!-- ================================================================= -->    
    <!-- =======================    CODE FOR THE PAGE   ================== -->    
    <!-- ================================================================= -->
    <script>
        $(document).ready(function() {                                                      //Ahora al front-end
            $('select').material_select();                                                  //SIEMPRE ACTUALIZA LOS SELECTS!

            <?php 
                $TitleAlert = '<span class = "yellow-text"><b>Alerta: &nbsp; </b></span>';  //El mini titulo
                foreach ($AlertMessages as $Alert):?>                                       //Para cada Alert
                    Materialize.toast(<?php echo "'{$TitleAlert} {$Alert}'";?>, 4000);      //Muestralo por 4 segundos
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


<?php 
    /*===================================================================
    ============         CLOSE ALL DATABASE     =========================
    ===================================================================*/
    include("PHP/HTMLFooter.php");

    if (isset($DataBase)) $DataBase->close();
?>
