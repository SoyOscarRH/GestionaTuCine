<?php
    /*=======================================================================================================================
    ============================================         LOGIN PROMPT          ==============================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE */
    include("/Applications/XAMPP/xamppfiles/htdocs/ManageYourCinema/Web/PHP/ForAllPages.php");  //Dame todas las ventajas que tiene incluir

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Inicia Sesión';                                                      		//Titulo de cada Pagina
    $Title      = 'Inicia Sesión en el Sistema';                                                //Titulo de cada Pagina
    $UpdateDate = '23 de Julio del 2017';                                                       //Fecha de actualizacion de la pagina



	// ========== SPECIFIC FOR THIS SCRIPT ==========
	$WarningMessage 	  = "";
	$ErrorAccountMessage  = "";
	$ErrorPasswordMessage = "";

    $AccountNumber = "";
    $Password      = "";
    $IDSesion      = "";
    $CompleteName       = ""; 

    $SayHello = false;   

    $ErrorCode = 0;                                                                         


    // *****************************************************************************************
    // *************************     PROCESS TO START THE SYSTEM   *****************************
    // *****************************************************************************************
    if ( isset($_POST['CheckDataToEnterSystem']) ){


        // ================================================================================
        // ======================  TRY TO GET ACCOUNT NUMBER   ============================
        // ================================================================================
        $AreAllDataValid = true;                                                                //Mundo feliz donde todo salio bien

        if (isset($_POST['UserName']) == false) $AreAllDataValid = false;                       //...De verdad salio todo bien
        if (isset($_POST['Password']) == false) $AreAllDataValid = false;                       //...De verdad salio todo bien

        $UserName = ClearSQLInyection(htmlspecialchars(trim($_POST['UserName'])));              //Recoge el numero de cuenta y le quita lo feo
        $Password = ClearSQLInyection(htmlspecialchars(trim($_POST['Password'])));              //Recoge el numero de cuenta y le quita lo feo

        if ($UserName == "") $AreAllDataValid = false;                                          //...De verdad salio todo bien
        if ($Password == "") $AreAllDataValid = false;                                          //...De verdad salio todo bien

        if ($AreAllDataValid) {                                                                 //Si todo si salio bien

            $DataBase = new mysqli("127.0.0.1", "root", "hola", "Proyect");                     //Abrimos una conexión
            if (mysqli_connect_errno()) exit();                                                 //Si es que no hay problemas

            $Query = 'SELECT ID, Correo, Contraseña, Nombre, ApellidoPaterno, ApellidoMaterno
                            FROM Empleado
                            WHERE Correo = "'.$UserName.'";'; 

            if ($QueryResult = $DataBase->query($Query)) {                                      //Si es que de verdad el men existe
                $Row = $QueryResult->fetch_row();                                               //Entonces dame el resultado

                $CompleteName = "{$Row[3]} {$Row[4]} {$Row[5]}";                                //Dame su nombre
                $SayHello = true;                                                               //Dile hola :D
            }
    	}
   	}



    include($PHPDocumentRoot."PHP/HTMLHeader.php");                                             //Incluimos un Asombroso Encabezado
?>

<br>
<br>


<?php 
//==================================================================================
//=============================    SESION LOGIN       ==============================
//==================================================================================
?>
<?php if ($SayHello == false): ?>
    
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



<?php endif; ?>


<?php 
//==================================================================================
//=============================    SESION START       ==============================
//==================================================================================
?>
<?php if ($SayHello == true): ?>
    
    <br><br>

    <div class="container center-align row">

        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">

            <form action="Login.php" method="post">
                
                <h4 class="grey-text text-darken-2"><br>Bienvenida de Nuevo :D</h4>

                <span class="grey-text">
                    Bienvenida al Sistema <?php echo $CompleteName; ?>
                    <br><br>
                </span>

                <br />

            </form>


        </div>


        <br><br><br><br><br>
        <br><br><br><br><br>
        <br><br><br><br><br>
        <br><br><br><br><br>
        <br><br><br><br><br>

    </div>

<?php endif; ?>









<?php include($PHPDocumentRoot."PHP/HTMLFooter.php"); ?>

