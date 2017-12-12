<?php
    /*=====================================================================================================================================
    ============================================         INDEX FOR THE COMPLETE SYSTEM         ============================================
    =======================================================================================================================================

    This works like the index for the complete system */
    include("PHP/ForAllPages.php");                                                         //Dame todas las ventajas que tiene incluir

    // ================ VARIABLES =============================
    $HTMLTitle = 'Maneja tu Cine';                                                          //Titulo de cada Pagina
    $UpdateDate = '23 de Julio del 2017';                                                   //Fecha de actualizacion de la pagina

    include("./PHP/HTMLHeader.php");                                                        //Incluimos un Asombroso Encabezado
?>


    <!--  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
    <!--  ++++++++++++++         CSS CHANGES        +++++++++++++++++++++ -->
    <!--  ++++++++++++++++++++++++*++++++++++++++++++++++++++++++++++++++ -->
    <style>
        .parallax-container {
          height: 350px;
        }
    </style>


    <!--  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
    <!--  ++++++++++++++   PARALLAX CONTAINER       +++++++++++++++++++++ -->
    <!--  ++++++++++++++++++++++++*++++++++++++++++++++++++++++++++++++++ -->
    <div class="parallax-container valign-wrapper">
        
        <!-- ===== THE IMAGE ===== -->
        <div class="parallax"><img src="Media/General/Film.jpg"></div>

        <!-- ===== CONTAINER ===== -->
        <div class="container center">
            
            <h1 class="header teal-text text-lighten-4">Maneja tu Cine</h1>

            <h5 class="header col s12 teal-text text-lighten-3">
                Administrador de Cinemas
            </h5>

            <!-- ===== BUTTON ===== -->
            <center>
            <a  href  = "Login.php"
                id    = "download-button"
                class = "btn-large waves-effect waves-light teal lighten-1">

                <?php echo (empty($_SESSION)? "Iniciar Sesión" : "Ve al Menú");?>
            </a>
            </center>

        </div>

    </div>


    <!--  ++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
    <!--  ++++++++++++++   INFORMATION   +++++++++++++++++++++ -->
    <!--  ++++++++++++++++++++++++*+++++++++++++++++++++++++++ -->
    <div class="container section">
    <div class="row">
           
        <!--  ============================ -->
        <!--  =========  COLUMN   ======== -->
        <!--  ============================ -->
        <div class="col s12 m4">
        <div class="icon-block">

            <!--  =========  Icon   ======== -->
            <h2 class="center brown-text"><i class="material-icons">flash_on</i></h2>

            <!--  =========  Title   ======== -->
            <h5 class="center">MiniTitulo1</h5>

            <!--  =========  Text   ======== -->
            <p class="light" align="justify">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Nullam scelerisque id nunc nec volutpat. Etiam pellentesque
                tristique arcu, non consequat magna fermentum ac. Cras ut ultricies
                eros. Maecenas eros justo, ullamcorper a sapien id, viverra ultrices
                eros. Morbi sem neque, posuere et pretium eget, bibendum
            </p>

        </div>
        </div>

        <!--  ============================ -->
        <!--  =========  COLUMN   ======== -->
        <!--  ============================ -->
        <div class="col s12 m4">
        <div class="icon-block">

            <!--  =========  Icon   ======== -->
            <h2 class="center brown-text"><i class="material-icons">settings</i></h2>

            <!--  =========  Title   ======== -->
            <h5 class="center">MiniTitulo1</h5>

            <!--  =========  Text   ======== -->
            <p class="light" align="justify">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Nullam scelerisque id nunc nec volutpat. Etiam pellentesque
                tristique arcu, non consequat magna fermentum ac. Cras ut ultricies
                eros. Maecenas eros justo, ullamcorper a sapien id, viverra ultrices
                eros. Morbi sem neque, posuere et pretium eget, bibendum
            </p>

        </div>
        </div>

        <!--  ============================ -->
        <!--  =========  COLUMN   ======== -->
        <!--  ============================ -->
        <div class="col s12 m4">
        <div class="icon-block">

            <!--  =========  Icon   ======== -->
            <h2 class="center brown-text"><i class="material-icons">group</i></h2>

            <!--  =========  Title   ======== -->
            <h5 class="center">MiniTitulo1</h5>

            <!--  =========  Text   ======== -->
            <p class="light" align="justify">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Nullam scelerisque id nunc nec volutpat. Etiam pellentesque
                tristique arcu, non consequat magna fermentum ac. Cras ut ultricies
                eros. Maecenas eros justo, ullamcorper a sapien id, viverra ultrices
                eros. Morbi sem neque, posuere et pretium eget, bibendum
            </p>

        </div>
        </div>

    </div>
    </div>


    <!--  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
    <!--  ++++++++++++++   PARALLAX CONTAINER       +++++++++++++++++++++ -->
    <!--  ++++++++++++++++++++++++*++++++++++++++++++++++++++++++++++++++ -->
    <div class="parallax-container valign-wrapper">
        
        <!-- ===== THE IMAGE ===== -->
        <div class="parallax"><img src="Media/General/Movies.jpg"></div>

        <!-- ===== CONTAINER ===== -->
        <div class="container center">
            
            <h2 class="header col s12 white-text ">
                Ver las Películas
            </h2>

            <!-- ===== BUTTON ===== -->
            <center>
            <?php if (!(empty($_SESSION))): ?>    
            <a  href  = "AdminMovies.php"
                id    = "download-button"
                class = "btn-large waves-effect waves-light blue lighten-1">
                Buscar Película
            </a>
            <?php endif; ?>
            </center>

        </div>

    </div>






    <?php include("./PHP/HTMLFooter.php"); ?>

