<?php 
    /*=======================================================================================================================
    ============================================         LOGIN PROMPT          ==============================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE */
    include("/Applications/XAMPP/xamppfiles/htdocs/ManageYourCinema/Web/PHP/ForAllPages.php");  //Dame todas las ventajas que tiene incluir

    session_start();

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Menu';                                                                       //Titulo de cada Pagina
    $Title      = 'Menu de Opciones';                                                           //Titulo de cada Pagina
    $UpdateDate = '8 de Noviembre del 2017';                                                    //Fecha de actualizacion de la pagina

    if (isset($_POST["CloseSession"])) {
        $NewHTMLTitle        = "Cerrar Sesión";
        $TitleErrorPage      = "Sesión Cerrado";                                                //Error variables
        $MessageErrorPage    = "La sesión se ha cerrado";                                       //Error variables
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   //Error variables
        $ButtonTextErrorPage = "Accede (otra vez) al Sistema";                                  //Error variables
        session_destroy();

        include($PHPDocumentRoot."Error.php");                                                  //Llama a la pagina de error
        exit();                                                                                 //Adios vaquero
    }


    if (empty($_SESSION)) {
        $TitleErrorPage      = "Error Permisos";                                                //Error variables
        $MessageErrorPage    = "No iniciaste sesión en el Sistema";                             //Error variables
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   //Error variables
        $ButtonTextErrorPage = "Accede al Sistema";                                             //Error variables

        include($PHPDocumentRoot."Error.php");                                                  //Llama a la pagina de error
        exit();                                                                                 //Adios vaquero
    }




    $CompleteName = $_SESSION["CompleteUserName"];





    include($PHPDocumentRoot."PHP/HTMLHeader.php");                                             //Incluimos un Asombroso Encabezado
?>



<?php 
//==================================================================================
//=============================    SESION START       ==============================
//==================================================================================
?>
    <div class="container center-align ">
    <br><br>
    
    <div class="row">
        <div class="card-panel light-green lighten-5 col s12 m8 l8 offset-m2 offset-l2">

            <form action="Login.php" method="post">
                
                <h4 class="grey-text text-darken-2"><br><b>Bienvenido(a)</b> de Nuevo</h4>

                <span class="grey-text flow-text">
                    Bienvenida al Sistema <?php echo $CompleteName; ?>
                    <br><br><br>
                </span>

            </form>
        </div>
    </div>

     <div class="row">
        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">

            <form action="Login.php" method="post">
                
                <h4 class="grey-text text-darken-2"><br><b>Menú</b> de Opciones</h4>

                <span class="grey-text flow-text">
                    Selecciona cual es la opción que necesites
                    <br><br>
                </span>

                <div class="row">
                    <div class="pink lighten-3 waves-effect  btn-large col s12 m8 l8 offset-m2 offset-l2 hoverable">
                        Horarios de Películas
                    </div>
                    <br><br><br>

                    <form action="">
                        <div class="deep-orange lighten-3 waves-effect btn-large col s12 m8 l8 offset-m2 offset-l2 hoverable">
                        Salarios
                        </div>
                        <br><br><br>
                    </form>

                    

                    <div class="teal lighten-3 waves-effect btn-large col s12 m8 l8 offset-m2 offset-l2 hoverable">
                        Dulcería
                    </div>
                    <br><br><br>
                    
                    <div class="blue lighten-3 waves-effect btn-large col s12 m8 l8 offset-m2 offset-l2 hoverable">
                        Vender Boletos
                    </div>
                    <br><br><br>

                    <br><br><br>
                    <form action="MenuEmployeeOrManager.php" method="post">
                        <button 
                            type='submit'
                            name='CloseSession'
                            class="red lighten-2 waves-effect btn-large col s12 m8 l8 offset-m2 offset-l2 hoverable">
                            Cerrar Sesión
                        </button> 
                    </form>
                    
                </div>

            </form>
        </div>
    </div>

    <br><br><br>

    </div>

<?php include($PHPDocumentRoot."PHP/HTMLFooter.php"); ?>

