<?php 
    /*=======================================================================================================================
    ============================================         LOGIN PROMPT          ==============================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE */
    include("PHP/ForAllPages.php");                                                             //Dame todas las ventajas que tiene incluir

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Menú de Opciones';                                                           //Titulo de cada Pagina
    $UpdateDate = '28 de Noviembre del 2017';                                                   //Fecha de actualizacion de pagina

    // ================ WE HAVE ACCESS AN ACCOUNT ============
    StandardCheckForStartedSession();                                                           //Asegurate de que pueda estar aqui
    $DataBase = StandardCheckForCorrectDataBase();                                              //Asegurate de que pueda estar aqui
    StandardUpdateSessionData($_SESSION['ID'], $DataBase);                                      //Asegurate que tdo este al dia

    if (isset($_POST["CloseSession"]) or isset($_GET["CloseSession"])) CallClosePage();         //Entraste e iniciaste sesion pero ya te vas :v

    if ($_SESSION['RolActual'] == 'Gerente') $RolText = "Estas asignado como <b>Gerente</b>";   //Dame su rol
    else $RolText = "Estas asignado en <b>{$_SESSION['RolActual']}</b>";                        //Ponlo como debe

    // ================ VARIABLES =============================
    $StandardCSSButton  = "waves-effect btn-large ";                                            //Porque soy flojo
    $StandardCSSButton .= "col s10 m8 l8 offset-s1 offset-m2 offset-l2 hoverable";              //Porque soy flojo
    $Greetings = ($_SESSION['Genero'] == 'Masculino'? "Bienvenido" : "Bienvenida");             //Dame el genero correcto

    include("PHP/HTMLHeader.php");                                                              //Incluimos un Asombroso Encabezado
?>


<?php 
//==================================================================================
//=============================    SESION START       ==============================
//==================================================================================
?>
    <br><br>
    
    <!-- =============================================== -->    
    <!-- =============  GREETINGS PANEL  =============== -->    
    <!-- =============================================== -->    
    <div class="row container center-align">
        <div class="card-panel light-green lighten-5 col s12 m8 l8 offset-m2 offset-l2">

            <h4 class="grey-text text-darken-2">
                <br> <b><?php echo $Greetings;?> </b>de Nuevo
            </h4>

            <span class="grey-text flow-text">
                <?php echo $Greetings;?> al Sistema <?php echo $_SESSION["CompleteUserName"]; ?>
                <br><br>
                <?php echo $RolText; ?>
                <br><br>
            </span>

        </div>
    </div>

    <!-- =============================================== -->    
    <!-- =============   OPTION   PANEL  =============== -->    
    <!-- =============================================== -->    
    <div class="row container center-align">
        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">

            
            <!-- =============   TEXT  =============== -->    
            <h4 class="grey-text text-darken-2">
                <br><b>Menú</b> de Opciones
            </h4>

            <span class="grey-text" style="font-size: 1.25rem;">
                Selecciona cual es la opción que necesites
                <br><br>
            </span>

            
            <!-- =============================================== -->    
            <!-- =============   OPTION ROW      =============== -->    
            <!-- =============================================== -->  
            <div class="row">

                <!-- =============================================== -->    
                <!-- =======       PROFILE AND USERS     =========== -->    
                <!-- =============================================== --> 
                <div id="ProfileAndUsers">
                    
                    <!-- =============   ADMIN OPTIONS    =============== -->    
                    <?php if ($_SESSION["IAmAManager"]):?>
                    <form action="AdminAccounts.php" method="post">
                        <button 
                            type='submit'
                            name='MoviesSchedules'
                            class="green lighten-2 <?php echo $StandardCSSButton;?>">
                            Administrar Empleados
                        </button> 
                    </form>
                    <br><br><br>
                    <?php endif;?>

                    <!-- =============   PROFILE OPTIONS    =============== -->    
                    <form action="MyProfile.php" method="post">
                        <button 
                            type='submit'
                            name='MyProfile'
                            class="green lighten-2 <?php echo $StandardCSSButton;?>">
                            Mi Perfil
                        </button> 
                    </form>
                    <br><br><br>

                </div>
                <br>

                <!-- =============================================== -->    
                <!-- =======          CINEMA STUFF       =========== -->    
                <!-- =============================================== --> 
                <div id="CinemaStuff">

                    <!-- =============   SELL TICKETS    =============== -->    
                    <form action="SellMovieTickets.php" method="post">
                        <button 
                            type='submit'
                            name='SellMovieTickets'
                            class="indigo lighten-2 <?php echo $StandardCSSButton;?>">
                            Vender Boletos
                        </button> 
                    </form>
                    <br><br><br>
                    
                    <!-- =============   ADD A MOVIE    =============== -->    
                    <?php if ($_SESSION["IAmAManager"]):?>
                    <form action="AdminMovies.php" method="post">
                        <button 
                            type='submit'
                            name='MoviesSchedules'
                            class="indigo lighten-2 <?php echo $StandardCSSButton;?>">
                            Buscar Pelicula
                        </button> 
                    </form>
                    <br><br><br>
                    <?php endif;?>
                    
                </div>
                <br>

                <!-- =============================================== -->    
                <!-- ==========       CANDY STORE        =========== -->    
                <!-- =============================================== --> 
                <div id="CandyStoreStuff">
                    
                    <!-- =============   CANDY STORE   =============== -->    
                    <form action="CandyStore.php" method="post">
                        <button 
                            type='submit'
                            name='SellMovieTickets'
                            class="blue lighten-2 <?php echo $StandardCSSButton;?>">
                            Dulcería
                        </button> 
                    </form>
                    <br><br><br>
                    
                    <!-- =============   ALTER PRODUCTS   =============== -->    
                    <?php if ($_SESSION["IAmAManager"]):?>
                    <form action="CandyStore.php#CardFindProduct" method="post">
                        <button 
                            type='submit'
                            name='MoviesSchedules'
                            class="blue lighten-2 <?php echo $StandardCSSButton;?>">
                            Administracion Productos
                        </button> 
                    </form>
                    <br><br><br>
                    <?php endif;?>

                </div>
                <br>


                <!-- =============================================== -->    
                <!-- ==========       SPECIAL STUFF      =========== -->    
                <!-- =============================================== --> 
                <div id="SpecialSuff">
                    
                    <!-- =============   CLOSE SESSION   =============== -->    
                    <form action="MenuEmployee.php" method="post">
                        <button 
                            type='submit'
                            name='CloseSession'
                            class="red lighten-2 <?php echo $StandardCSSButton;?>">
                            Cerrar Sesión
                        </button> 
                    </form>
                    <br>

                </div>
                <br>
                
            </div>

        </div>
    </div>

    <br><br><br>
    <br><br><br>


<?php include("PHP/HTMLFooter.php"); ?>

