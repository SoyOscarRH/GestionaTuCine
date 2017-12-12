<?php
    /*=======================================================================================================================
    ============================================         ADMINISTRATE MOVIES          ======================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE FOR THE ADMINISTRADOR TO SEE THINGS */
    include("PHP/ForAllPages.php");                                                             //Dame todas las ventajas

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Admin Peliculas';                                                            //Titulo de cada Pagina
    $UpdateDate = '10 de Diciembre del 2017';                                                   //Fecha de actualizacion de pagina

    // ========== SPECIFIC FOR THIS SCRIPT ==========
    $AlertMessages = array();                                                                   //Mensajes que mostramos 
    $MovieInfo = array();                                                                       //Mensajes que mostramos 

    StandardCheckForStartedSession();                                                           //Asegurate de que pueda estar aqui
    $DataBase = StandardCheckForCorrectDataBase();                                              //Asegurate de que pueda estar aqui
    StandardUpdateSessionData($_SESSION['ID'], $DataBase);                                      //Asegurate que tdo este al dia

    if (StandardCheckForAdminStatus($_SESSION['ID'], $DataBase) == false)                       //Dime en este instante si tiene permisos
        CallErrorPageOnlyForAdmins();                                                           //Si no tienes permiso de estar aqui


    /*===================================================================
    ============         GET THE DATABASE      ==========================
    ===================================================================*/


    //============= SEE MOVIES  =============
    $QueryMovieInfo = $DataBase->query("SELECT * FROM Pelicula");                               //Haz la consulta

    if ($QueryMovieInfo->num_rows == 0)                                                         //Si es que no hay tuplas
        array_push($AlertMessages, "No se puede acceder a Info de Peliculas");                  //Envia mensajes



    // ======================================================================
    // =================  IF YOU WANT TO SEARCH THE DATA  ===================
    // ======================================================================
    if (isset($_POST['SearchForMovie'])) {                                                      //Si es que quieres buscar pelicula
        
        do {                                                                                    //Solo por le break
            
            // =================  GET THE INFO  ===================
            if (isset($_POST['PossibleMovieName'])) {
                $PossibleMovieName = ClearSQLInyection($_POST['PossibleMovieName']);            //Dame la info
                $PossibleMovieName = str_replace(' ', '%', $PossibleMovieName);                 //Esto ayuda con el reconocimiento   
            }
            else $PossibleMovieName = "";                                                       //Sino vacio

            if (isset($_POST['PossibleGenres'])) {                                              //SI EXISTE ESTO:
                $PossibleGenres = $_POST['PossibleGenres'];                                     //Lo tenemos
                foreach ($PossibleGenres as $Genre) {$Genre = ClearSQLInyection($Genre);}       //Lo limpiamos

                if (in_array("All", $PossibleGenres)) $AllPossibleGenresText = "";              //Si es que todo
                else {
                    $AllPossibleGenresText = "AND Genero IN (";                                 //Sino entonces empezamos a armarlo
                    foreach ($PossibleGenres as $Genre)                                         //Lo agrego
                        $AllPossibleGenresText .= "'{$Genre}', ";                               //Por cada elemento
                    $AllPossibleGenresText = substr($AllPossibleGenresText, 0, -2);             //Quito la ultima coma
                    $AllPossibleGenresText .= ")";                                              //Pongo parentesis
                }
            }
            else $AllPossibleGenresText = "";                                                   //Sino es vacio :v

            if (isset($_POST['PossibleClassifications'])) {                                     //SI EXISTE ESTO:
                $PossibleClassifications = $_POST['PossibleClassifications'];                   //Lo tenemos
                foreach ($PossibleClassifications as $Classification)                           //Lo limpiamos
                    {$Classification = ClearSQLInyection($Classification);}

                if (in_array("All", $PossibleClassifications)) $AllClassificationsText = "";    //Si es que era todo
                else {
                    $AllClassificationsText = "AND Clasificacion IN (";                         //Sino entonces empezamos a armarlo
                    foreach ($PossibleClassifications as $Classification)                       //Lo agrego            
                        $AllClassificationsText .= "'{$Classification}', ";                     //Por cada elemento            
                    $AllClassificationsText = substr($AllClassificationsText, 0, -2);           //Quito la ultima coma
                    $AllClassificationsText .= ")";                                             //Pongo parentesis
                }
            }
            else $AllClassificationsText = "";                                                  //Sino es vacio :v
           
            // =================  TALK TO THE BASE  ===================
            $QueryPossibleMovies = $DataBase->query("
                        SELECT * FROM Pelicula
                            WHERE
                                Nombre LIKE '%{$PossibleMovieName}%' AND
                                Exhibicion = 'En Exhibición'
                                {$AllPossibleGenresText}
                                {$AllClassificationsText}");                                    //Ahora hacemos el query

            if (!$QueryPossibleMovies) {                                                        //Si error
                array_push($AlertMessages, "Error con la Base de Datos");                       //Envia mensajes
                unset($_POST['SearchForProduct']);                                              //Adios al texto y todo
                break;                                                                          //Adios proceso
            }

            if ($QueryPossibleMovies->num_rows == 0) {                                          //Si es que no hay tuplas
                array_push($AlertMessages, "No tenemos esa clase de películas :(");             //Envia mensajes
                unset($_POST['SearchForProduct']);                                              //Adios al texto y todo
            }
        }
        while (false);                                                                          //Solo queria el break :v

    }


    // *****************************************************************************************
    // *************************     PROCESS TO START THE SYSTEM   *****************************
    // *****************************************************************************************
    $StandardGreyCard = "card grey lighten-4 col s12 m8 l8 offset-m2 offset-l2";                //Es una forma de que sea mas sencilla 
    $StandardButton = "col s10 m6 l6 offset-s1 offset-m3 offset-l3 btn btn-large waves-effect"; //Es una forma de que sea mas sencilla 

    include("PHP/HTMLHeader.php");                                                              //Incluimos un Asombroso Encabezado
?>
    <br><br>
    <div class="container center-align">
        
        <!-- ================================================================== -->    
        <!-- =====================   SEARCH FOR STUFF        ================== -->      
        <!-- ================================================================== -->    
        <div class="<?php echo $StandardGreyCard;?>">

            <!-- =================   CARD CONTENT  ================ -->      
            <div id="CardFindMovie" name="CardFindMovie" class="card-content">

                <!-- ========  TITLE  ================ -->
                <h4 class="grey-text text-darken-2"><b>Busqueda</b> de Películas</h4>
            
                <!-- ========  TEXT  ================ -->
                <span class="grey-text" style="font-size: 1.15rem;">
                    Busca el nombre (o fragmentos) de lo que quieras ver :D
                    <br><br>
                </span>


                <!-- ========  MATERIAL FORM FOR SEACH  =============== -->
                <form class="container" action="AdminMovies.php" method="post">

                    <!-- ========  TYPES ============= -->
                    <div class='input-field center-align'>
                        <input
                            <?php if (isset($_POST['SearchForProduct'])) echo "autofocus"; ?>
                            class = 'validate'
                            type  = 'text'
                            id    = 'PossibleMovieName'
                            name  = 'PossibleMovieName'/>
                        <label>Busca Películas</label>
                    </div>


                    <!-- ========  GENRES ============= -->
                    <div class='input-field'>
                        <select name="PossibleGenres[]" id="PossibleGenres" class="left-align" multiple>
                            <option value="All">Todos los Géneros</option>
                            <?php 
                                $AllGenres = array('Accion y Aventura', 'Familiar', 'Comedia', 'Documental', 'Drama', 'Terror', 
                                                    'Fantasia', 'Romantica', 'CienciaFiccion', 'Deportes', 'Suspenso');
                                
                                foreach ($AllGenres as $Genre): ?>
                                <option value="<?php echo $Genre;?>"><?php echo $Genre;?></option>
                            <?php endforeach; ?>

                        </select>
                        <label>Generos Buscados</label>
                    </div>

                    <!-- ========  CLASSIFICATIONS ============= -->
                    <div class='input-field'>
                        <select name="PossibleClassifications[]" id="PossibleClassifications" class="left-align" multiple>
                            <option value="All">Todos las Clasificaciones</option>
                            <?php 
                                $AllClassifications = array('AA', 'A', 'B', 'B15', 'C', 'D');
                                
                                foreach ($AllClassifications as $Classification): ?>
                                <option value="<?php echo $Classification;?>"><?php echo $Classification;?></option>
                            <?php endforeach; ?>

                        </select>
                        <label>Clasificaciones Buscadas</label>
                    </div>


                    <br>
                    <br>

                    <!-- ========  BUTTON TO SEND ===== -->
                    <button
                        type  = 'submit'
                        id    = 'SearchForMovie'
                        name  = 'SearchForMovie'
                        class = 'col s8 btn-large waves-effect indigo lighten-1'>
                        Buscar Película
                    </button>

                </form>

                <!-- ========  LIST OF POSSIBLE PRODUCTS  =============== -->
                <?php if (isset($_POST['SearchForMovie'])) :?>
                    <br><br>
                    
                    <div class="<?php if (!WeAreAtMobile()) echo "container"; ?>">
                    <ul class="collapsible" data-collapsible="accordion">
                    
                        <!-- ========  FOR EACH POSSIBLE PRODUCT ================ -->
                        <?php while ($Movie = $QueryPossibleMovies->fetch_assoc()) : ?>
                        <!-- ========  MOVIE ITME ================ -->
                        <li>
                           
                            <!-- ========  MOVIE TITLE  ======== -->
                            <div class="collapsible-header">
                                
                                <!-- ========  MOVIE ICON  ======== -->
                                <i class="material-icons grey-text text-darken-3">local_movies</i> 
                                
                                <!-- ========  MOVIE TEXT  ======== -->
                                <span class="grey-text text-darken-3 left-align" style="font-size: 1.2rem;">
                                    <?php echo $Movie['Nombre']; ?>
                                </span>
                            </div>
                          
                            <!-- ========  MOVIE BODY  ======== -->
                            <div class="collapsible-body container">

                                <!-- ========  CLASSIFICATION TEXT  ======== -->
                                <div class="row">
                                    <form action="SellMovieTickets.php" method="post">

                                        <input type="hidden" name="PossibleMovieName" value="<?php echo $Movie['Nombre'];?>">
                                        <button
                                            type  = 'submit'
                                            id    = 'SearchForMovie'
                                            name  = 'SearchForMovie'
                                            class = 'col left btn waves-effect green lighten-1'>
                                            Ver Funciones
                                        </button>
                                    </form>
                                </div>

                                

                                <!-- ========  DESCRIPTION TEXT  ======== -->
                                <div class="row">
                                    <div class="col s12 left-align">
                                        
                                        <!-- ========  TITLE TEXT  ======== -->
                                        <span 
                                            class = "left grey-text text-darken-2"
                                            style = "font-size: <?php if (WeAreAtMobile()) echo "0.95rem"; else "1.1rem;" ?>">
                                            <b>Descripción:</b>
                                        </span>
                                        
                                        <br>

                                        <!-- ========  TEXT  ======== -->
                                        <span 
                                            class = "grey-text text-darken-1" 
                                            style = "font-size: <?php if (WeAreAtMobile()) echo "0.90rem"; else "1.1rem;" ?>">
                                            <?php echo $Movie['Descripcion']; ?>
                                        </span>
                                    </div>
    
                                </div>

                                <!-- ========  GENRES TEXT  ======== -->
                                <div class="row">
                                    <div 
                                        class = "chip right indigo-text text-darken-1" 
                                        style = "font-size: <?php echo (WeAreAtMobile())? "0.7rem" : "0.9rem;"; ?>">
                                        <b><?php echo (str_replace(',', ', ', $Movie['Genero']));?></b>
                                    </div>

                                    <!-- ========  CLASSIFICATION TEXT  ======== -->
                                    <div 
                                        class = "chip right indigo-text text-darken-1" 
                                        style = "font-size: <?php echo (WeAreAtMobile())? "0.7rem" : "0.9rem;"; ?>">
                                        <b>Clasificación: <?php echo $Movie['Clasificacion']; ?></b>
                                    </div>
                                </div>

                            </div>
                        </li>
                        <?php endwhile;?>

                    </ul>
                    </div>

                <?php endif; ?>

            </div>

        </div>
        <br><br><br>


        <!-- ================================================================== -->    
        <!-- =====================    SHOW OLD MOVIES      ==================== -->      
        <!-- ================================================================== -->  
        <?php if ($_SESSION['IAmAManager']): ?>  
        <div class="<?php echo $StandardGreyCard;?>">

            <!-- =================   CARD CONTENT  ================ -->      
            <div id="CardSeeOldMoviesInfo" name="CardSeeOldMoviesInfo" class="card-content center-align">

                <!-- ========  TITLE  ================ -->
                <h4 class="grey-text text-darken-2">
                    <b>Ver Información </b> de Películas Antiguas
                </h4>

                <!-- ========  TEXT  ================ -->
                <span class="grey-text" style="font-size: 1.25rem;">
                    Accede a la información de todas las antiguas que ya no se desplegan en el cine
                    <br><br>
                </span>

                <br>

                <!-- ========  SEE OLD MOVIES  ================ -->
                <div  class="<?php if (!WeAreAtMobile()) echo "container"; ?>">
                    <ul 
                        id    = "CollpasibleOldMovies"
                        name  = "CollpasibleOldMovies"
                        class = "collapsible" 
                        style = "<?php if (!isset($_POST['ShowOldMovies'])) echo "display: none"; ?>"
                        data-collapsible = "accordion">
                    
                        <!-- ========  FOR EACH POSSIBLE PRODUCT ================ -->
                        <?php while ($Movie = $QueryMovieInfo->fetch_assoc()) :?>

                        <?php if ($Movie['Exhibicion'] == 'En Exhibición') continue; ?>
                        

                        <!-- ========  MOVIE ITME ================ -->
                        <li>
                           
                            <!-- ========  MOVIE TITLE  ======== -->
                            <div class="collapsible-header">
                                
                                <!-- ========  MOVIE ICON  ======== -->
                                <i class="material-icons grey-text text-darken-3">local_movies</i> 
                                
                                <!-- ========  MOVIE TEXT  ======== -->
                                <span class="grey-text text-darken-3 left-align" style="font-size: 1.2rem;">
                                    <?php echo $Movie['Nombre']; ?>
                                </span>
                            </div>
                          
                            <!-- ========  MOVIE BODY  ======== -->
                            <div class="collapsible-body">

                                <!-- ========  CLASSIFICATION TEXT  ======== -->
                                <div class="row">
                                    <div class="chip col s7 l5 m5 right indigo-text text-darken-1">
                                        <b>Clasificación: <?php echo $Movie['Clasificacion']; ?></b>
                                    </div>
                                </div>

                                <!-- ========  DESCRIPTION TEXT  ======== -->
                                <div class="row">
                                    <div class="col s12 left-align">
                                        
                                        <!-- ========  TITLE TEXT  ======== -->
                                        <span 
                                            class = "left grey-text text-darken-2"
                                            style = "font-size: <?php if (WeAreAtMobile()) echo "0.95rem"; else "1.1rem;" ?>">
                                            <b>Descripción:</b>
                                        </span>
                                        
                                        <br>

                                        <!-- ========  TEXT  ======== -->
                                        <span 
                                            class = "grey-text text-darken-1" 
                                            style = "font-size: <?php if (WeAreAtMobile()) echo "0.90rem"; else "1.1rem;" ?>">
                                            <?php echo $Movie['Descripcion']; ?>
                                        </span>
                                    </div>
    
                                </div>

                                <!-- ========  GENRES TEXT  ======== -->
                                <div class="row">
                                    <span 
                                        class = "col s8 offset-s4 right teal-text text-darken-1" 
                                        style = "font-size: <?php if (WeAreAtMobile()) echo "0.85rem"; else "1rem;" ?>">
                                        <b><?php echo (str_replace(',', ', ', $Movie['Genero']));?></b>
                                    </span>
                                </div>

                            </div>
                        </li>

                        <?php endwhile;?>

                    </ul>
                
                    <br><br>

                </div>

                <button
                    type  = 'submit'
                    id    = 'ShowOldMovies'
                    name  = 'ShowOldMovies'
                    class = 'col s8 offset-s2 l6 offset-l3 btn-large waves-effect pink darken-1'>
                    Ver Películas Antiguas
                </button>
                <br>

            </div>

            <!-- ========  CARD SCRIPT  ===== -->
            <script>
                $("#ShowOldMovies").click( function() {
                    $("#CollpasibleOldMovies").toggle(400);

                    if ($.trim($(this).text()) === 'Ver Películas Actuales')
                        $(this).text('Oculta Películas');
                    else $(this).text('Ver Películas Actuales');        
                });
            </script>

        </div>
        <br><br><br>
        <?php endif; ?>



    </div>




    <br><br>


    <!-- ================================================================= -->    
    <!-- ===============         FAB FOR THE PAGE       ================== -->    
    <!-- ================================================================= -->
    <div class="fixed-action-btn <?php if (WeAreAtMobile()) echo "click-to-toggle"; ?>">
        
        <a class="btn-floating btn-large cyan darken-3">
            <i class="unselectable large material-icons">view_list</i>
        </a>
        
        <ul>

            <!-- =======  TO SEE MOVIES INFO  ========== -->    
            <li>
                <a href    = "#CardFindMovie"
                   onclick = "$('.fixed-action-btn').closeFAB();" 
                   class   = "btn-floating indigo">
                    <i class="unselectable material-icons">find_in_page</i>
                </a>
            </li>

            <!-- =======  TO SEE MOVIES INFO  ========== -->    
            <li>
                <a href    = "#CardSeeOldMoviesInfo"
                   onclick = "$('.fixed-action-btn').closeFAB();" 
                   class   = "btn-floating pink">
                    <i class="unselectable material-icons">search</i>
                </a>
            </li>

        </ul>
    </div>


    <!-- ================================================================= -->    
    <!-- =======================    CODE FOR THE PAGE   ================== -->    
    <!-- ================================================================= -->
    <script>
        $(document).ready(function() {

            // Start a Select
            $('select').material_select();
            Materialize.updateTextFields();

            // Create all the Toast
            <?php 
                $TitleAlert = '<span class = "yellow-text"><b>Alerta: &nbsp; </b></span>';
                foreach ($AlertMessages as $Alert) echo "Materialize.toast('$TitleAlert $Alert', 9000);"; 
            ?>

        });
    </script>



<?php 
    /*===================================================================
    ============         CLOSE ALL DATABASE     =========================
    ===================================================================*/
    include("PHP/HTMLFooter.php");

    if (isset($DataBase)) $DataBase->close();
?>