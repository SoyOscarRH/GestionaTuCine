<?php 
    /*=======================================================================================================================
    ============================================         LOGIN PROMPT          ==============================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE */
    include("./PHP/ForAllPages.php");                                                           //Dame todas las ventajas

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Menú de Opciones';                                                           //Titulo de cada Pagina
    $UpdateDate = '8 de Noviembre del 2017';                                                    //Fecha de actualizacion de pagina


    // ================ WE HAVE ACCESS AN ACCOUNT ============
    if (isset($_POST["CloseSession"]) or isset($_GET["CloseSession"])) {                        //Entraste e iniciaste sesion
        $NewHTMLTitle        = "Cerrar Sesión";                                                 //Cambia el titulo de pag error
        $TitleErrorPage      = "Sesión Cerrado";                                                //Error variables
        $MessageErrorPage    = "La sesión se ha cerrado";                                       //Error variables
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   //Error variables
        $ButtonTextErrorPage = "Accede (otra vez) al Sistema";                                  //Error variables
        session_destroy();

        include("Error.php");                                                                   //Llama a la pagina de error
        exit();                                                                                 //Adios vaquero
    }

    if (empty($_SESSION)) {                                                                     //Titulo de la pagina
        $NewHTMLTitle        = "Error con Permisos";                                            //Error variables
        $TitleErrorPage      = "Error con Permisos";                                            //Error variables
        $MessageErrorPage    = "No iniciaste sesión en el Sistema";                             //Error variables
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   //Error variables
        $ButtonTextErrorPage = "Accede al Sistema";                                             //Error variables

        include("Error.php");                                                                   //Llama a la pagina de error
        exit();                                                                                 //Adios vaquero
    }

    // ================ VARIABLES =============================
    $CompleteName = $_SESSION["CompleteUserName"];                                              //Dame el nombre completo
    $IAmAManager = false;                                                                       //Pero ... ¿Eres gerente?
    
    if ($_SESSION["IDGerente"] == $_SESSION["ID"]) $IAmAManager = true;                         //Pues pregunta :v

    include("PHP/HTMLHeader.php");                                                              //Incluimos un Asombroso Encabezado
?>



<?php 
//==================================================================================
//=============================    SESION START       ==============================
//==================================================================================
?>
    <br><br>
    
    <div class="row container center-align">
        <div class="card-panel light-green lighten-5 col s12 m8 l8 offset-m2 offset-l2">

            <h4 class="grey-text text-darken-2">
                <br><b>Bienvenid<?php if ($_SESSION['Genero'] == 'Masculino') echo "o"; else echo "a";?> </b> de Nuevo
            </h4>

            <span class="grey-text flow-text">
                Bienvenid<?php if ($_SESSION['Genero'] == 'Masculino') echo "o"; else echo "a";?>
                al Sistema <?php echo $CompleteName; ?>
                <br><br><br>
            </span>

        </div>
    </div>

    <div class="row container center-align">
        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">

            <h4 class="grey-text text-darken-2">
                <br><b>Menú</b> de Opciones
            </h4>

            <span class="grey-text" style="font-size: 1.25rem;">
                Selecciona cual es la opción que necesites
                <br><br>
            </span>

            <div class="row">

                <?php if ($IAmAManager):?>
                <form action="AdminAccounts.php" method="post">
                    <button 
                        type='submit'
                        name='MoviesSchedules'
                        class="green lighten-2 waves-effect btn-large col s10 m8 l8 offset-s1 offset-m2 offset-l2 hoverable">
                        Administrador
                    </button> 
                </form>
                <br><br><br>

                <?php endif;?>

                <form action="MyProfile.php" method="post">
                    <button 
                        type='submit'
                        name='MyProfile'
                        class="green lighten-2 waves-effect btn-large col s10 m8 l8 offset-s1 offset-m2 offset-l2 hoverable">
                        Mi Perfil
                    </button> 
                </form>

                <br><br><br>

                <br>

                <form action="VerHorarios.php" method="post">
                    <button 
                        type='submit'
                        name='MoviesSchedules'
                        class="indigo lighten-2 waves-effect btn-large col s10 m8 l8 offset-s1 offset-m2 offset-l2 hoverable">
                        Checa Horarios
                    </button> 
                </form>
                <br><br><br>



                <?php if ($IAmAManager):?>
                <form action="MenuEmployeeOrManager.php" method="post">
                    <button 
                        type='submit'
                        name='MoviesSchedules'
                        class="indigo lighten-2 waves-effect btn-large col s10 m8 l8 offset-s1 offset-m2 offset-l2 hoverable">
                        Cambiar Horarios
                    </button> 
                </form>
                <br><br><br>
                <?php endif;?>

                <form action="MenuEmployeeOrManager.php" method="post">
                    <button 
                        type='submit'
                        name='SellMovieTickets'
                        class="indigo lighten-2 waves-effect btn-large col s10 m8 l8 offset-s1 offset-m2 offset-l2 hoverable">
                        Vender Boletos
                    </button> 
                </form>
                <br><br><br>
                
                  <?php if ($IAmAManager):?>
                <form action="AltaPelicula.php" method="post">
                    <button 
                        type='submit'
                        name='MoviesSchedules'
                        class="indigo lighten-2 waves-effect btn-large col s10 m8 l8 offset-s1 offset-m2 offset-l2 hoverable">
                        Registrar Pelicula
                    </button> 
                </form>
                <br><br><br>
                <?php endif;?>
                
                <?php if ($IAmAManager):?>
                <form action="ConsultaEdicionPelicula.php" method="post">
                    <button 
                        type='submit'
                        name='MoviesSchedules'
                        class="indigo lighten-2 waves-effect btn-large col s10 m8 l8 offset-s1 offset-m2 offset-l2 hoverable">
                        Administar Peliculas
                    </button> 
                </form>
                <br><br><br>
                <?php endif;?>

                <br>

                <form action="MenuEmployeeOrManager.php" method="post">
                    <button 
                        type='submit'
                        name='SellMovieTickets'
                        class="blue lighten-2 waves-effect btn-large col s10 m8 l8 offset-s1 offset-m2 offset-l2 hoverable">
                        Dulcería
                    </button> 
                </form>
                <br><br><br>
                
              


                <?php if ($IAmAManager):?>
                <form action="MenuEmployeeOrManager.php" method="post">
                    <button 
                        type='submit'
                        name='MoviesSchedules'
                        class="blue lighten-2 waves-effect btn-large col s10 m8 l8 offset-s1 offset-m2 offset-l2 hoverable">
                        Cambiar Precios Dulcería
                    </button> 
                </form>
                <br><br><br>
                <?php endif;?>

                <br>
                
                <form action="MenuEmployeeOrManager.php" method="post">
                    <button 
                        type='submit'
                        name='CloseSession'
                        class="red lighten-2 waves-effect btn-large col s10 m8 l8 offset-s1 offset-m2 offset-l2 hoverable">
                        Cerrar Sesión
                    </button> 
                </form>

                <br>
                
                </div>

        </div>
    </div>

    <br><br><br>
    <br><br><br>


<?php include("PHP/HTMLFooter.php"); ?>

