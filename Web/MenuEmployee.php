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
    StandardCheckForStartedSession();                                                           //Dime que iniciaste sesión
    if (isset($_POST["CloseSession"]) or isset($_GET["CloseSession"])) CallClosePage();         //Entraste e iniciaste sesion pero ya te vas :v


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
    
    <div class="row container center-align">
        <div class="card-panel light-green lighten-5 col s12 m8 l8 offset-m2 offset-l2">

            <h4 class="grey-text text-darken-2">
                <br> <b><?php echo $Greetings;?> </b>de Nuevo
            </h4>

            <span class="grey-text flow-text">
                <?php echo $Greetings;?> al Sistema <?php echo $_SESSION["CompleteUserName"]; ?>
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

                <?php if ($_SESSION["IAmAManager"]):?>
                <form action="AdminAccounts.php" method="post">
                    <button 
                        type='submit'
                        name='MoviesSchedules'
                        class="green lighten-2 <?php echo $StandardCSSButton;?>">
                        Administrador
                    </button> 
                </form>
                <br><br><br>

                <?php endif;?>

                <form action="MyProfile.php" method="post">
                    <button 
                        type='submit'
                        name='MyProfile'
                        class="green lighten-2 <?php echo $StandardCSSButton;?>">
                        Mi Perfil
                    </button> 
                </form>

                <br><br><br>

                <br>

                <form action="VerHorarios.php" method="post">
                    <button 
                        type='submit'
                        name='MoviesSchedules'
                        class="indigo lighten-2 <?php echo $StandardCSSButton;?>">
                        Checa Horarios
                    </button> 
                </form>
                <br><br><br>



                <?php if ($_SESSION["IAmAManager"]):?>
                <form action="MenuEmployee.php" method="post">
                    <button 
                        type='submit'
                        name='MoviesSchedules'
                        class="indigo lighten-2 <?php echo $StandardCSSButton;?>">
                        Cambiar Horarios
                    </button> 
                </form>
                <br><br><br>
                <?php endif;?>

                <form action="MenuEmployee.php" method="post">
                    <button 
                        type='submit'
                        name='SellMovieTickets'
                        class="indigo lighten-2 <?php echo $StandardCSSButton;?>">
                        Vender Boletos
                    </button> 
                </form>
                <br><br><br>
                
                  <?php if ($_SESSION["IAmAManager"]):?>
                <form action="AltaPelicula.php" method="post">
                    <button 
                        type='submit'
                        name='MoviesSchedules'
                        class="indigo lighten-2 <?php echo $StandardCSSButton;?>">
                        Registrar Pelicula
                    </button> 
                </form>
                <br><br><br>
                <?php endif;?>
                
                <?php if ($_SESSION["IAmAManager"]):?>
                <form action="ConsultaEdicionPelicula.php" method="post">
                    <button 
                        type='submit'
                        name='MoviesSchedules'
                        class="indigo lighten-2 <?php echo $StandardCSSButton;?>">
                        Administar Peliculas
                    </button> 
                </form>
                <br><br><br>
                <?php endif;?>

                <br>

                <form action="CandyStore.php" method="post">
                    <button 
                        type='submit'
                        name='SellMovieTickets'
                        class="blue lighten-2 <?php echo $StandardCSSButton;?>">
                        Dulcería
                    </button> 
                </form>
                <br><br><br>
                
              


                <?php if ($_SESSION["IAmAManager"]):?>
                <form action="MenuEmployee.php" method="post">
                    <button 
                        type='submit'
                        name='MoviesSchedules'
                        class="blue lighten-2 <?php echo $StandardCSSButton;?>">
                        Cambiar Precios Dulcería
                    </button> 
                </form>
                <br><br><br>
                <?php endif;?>

                <br>
                
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

        </div>
    </div>

    <br><br><br>
    <br><br><br>


<?php include("PHP/HTMLFooter.php"); ?>

