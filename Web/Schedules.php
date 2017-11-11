<?php
    /*=======================================================================================================================
    ============================================         ADMINISTRATOR POWERS          ======================================
    =========================================================================================================================
    
    THIS IS THE GENERAL PAGE FOR THE EMPLOYEE TO SEE MOVIES */
    include("PHP/ForAllPages.php");                                                             //Dame todas las ventajas

    // ================ VARIABLES =============================
    $HTMLTitle  = $Title = 'Cartelera';                                                         //Titulo de cada Pagina
    $UpdateDate = '10 de Noviembre del 2017';                                                   //Fecha de actualizacion de pagina

    $AlertMessages = array();                                                                   //Mensajes que mostramos 

    // ================ CHECK FOR BAD PEOPLE =================
    if (empty($_SESSION)) {                                                                     //Titulo de la pagina
        $NewHTMLTitle        = "Error con Permisos";                                            //Error variables
        $TitleErrorPage      = "Error con Permisos";                                            //Error variables
        $MessageErrorPage    = "No iniciaste sesión en el Sistema";                             //Error variables
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   //Error variables
        $ButtonTextErrorPage = "Accede al Sistema";                                             //Error variables

        include("Error.php");                                                                   //Llama a la pagina de error
        exit();                                                                                 //Adios vaquero
    }

    // ================ CHECK FOR MANAGERS  =================
    $CompleteName = $_SESSION["CompleteUserName"];                                              //Dame informacion                                
    $IAmAManager = false;                                                                       //Dime si eres Gerente
    if ($_SESSION["IDGerente"] == $_SESSION["DataBaseID"]) $IAmAManager = true;                 //Eres Gerente ¿verdad?


    // ============ ABRAMOS LA BASE DE DATOS =================
    $DataBase = @new mysqli("127.0.0.1", "root", "root", "Proyect");                            //Abrir una conexión
    if ((mysqli_connect_errno() != 0) or !$DataBase) {                                          //Si hubo problemas
        $TitleErrorPage      = "Error con la BD";                                               //Error variables
        $MessageErrorPage    = "No podemos acceder a la base de datos";                         //Error variables
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   //Error variables
        $ButtonTextErrorPage = "Intenta otra vez";                                              //Error variables

        include("Error.php");                                                                   //Llama a la pagina de error
        exit();                                                                                 //Adios vaquero
    }

    $QuerySchedules = $DataBase->query('
        SELECT F.*, P.Clasificacion, P.Nombre, P.Duracion, P.Descripcion 
            FROM Funcion F, Pelicula P, Sala S 
            WHERE 
                P.ID = F.IDPelicula AND F.NumeroSala = S.NumeroSala;');                         //Haz la consulta

    if ($QuerySchedules->num_rows == 0)                                                         //Si es que no hay tuplas
        array_push($AlertMessages, "No se puede acceder a Info de los Horarios");               //Envia mensajes




    




    // *****************************************************************************************
    // *************************     PROCESS TO START THE SYSTEM   *****************************
    // *****************************************************************************************
    include("PHP/HTMLHeader.php");                                                              //Incluimos un Asombroso Encabezado
?>

    <br><br>

    <div class="container center-align">


        <!-- ========  MATERIAL CARD  ================ -->
        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">

            <h4 class="grey-text text-darken-2">
                <br><b>Información </b> de Horarios
            </h4>

            <span class="grey-text" style="font-size: 1.25rem;">
                Acceder a un registro con todas las peliculas
                <br><br>
            </span>

            <!-- ========  MATERIAL TABLE CARD  ================ -->
            <table
                id="SchedulesTable" 
                style="display: none;"
                class="centered hoverable striped responsive-table">

                <thead>
                    <tr>
                          <th>Hora</th>
                          <th>Fecha</th>
                          <th>Sala</th>
                          <th>Precio</th>
                          <th>Tipo</th>
                          <th>IdPelicula</th>
                          <th>Clasificacion</th>
                          <th>Nombre</th>
                          <th>Duracion (horas)</th>
                          <th>Descripcion </th>
              
                    </tr>
                </thead>
                
                <tbody>

                <?php while ($Row = $QuerySchedules->fetch_row()) : ?>

                    <tr>
                    
                    <?php foreach ($Row as $Number => $Value): ?>

                        <td><?php echo $Value; ?></td>
                        
                    <?php endforeach; ?>

                    </tr>

                    <?php endwhile;

                    $QuerySchedules->close();

                $DataBase->close(); ?>

                </tbody>
            </table>

            <br>
        
            <button 
                id="SchedulesTableButton"
                class="btn waves-effect waves-light"
                name="ShowEmployees">
                Ve los Horarios
            </button>
            <script>
                $("#SchedulesTableButton").click( function() {$("#SchedulesTable").toggle();});
            </script>


        </div>



        <br><br><br><br>
        <br><br><br><br>

    </div>


    <script>
        $(document).ready(function() {
            <?php 
                $ErrorSymbol = '<span class = "yellow-text"><b>Error: &nbsp; </b></span>';

                foreach ($AlertMessages as $Alert) {
                    echo "Materialize.toast('{$ErrorSymbol} {$Alert}', 9000);";           //Envia esto
                }
            ?>
        });
    </script>


	<?php include("PHP/HTMLFooter.php"); ?>
