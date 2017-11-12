<?php
   include("PHP/ForAllPages.php");                                                           
    
    $HTMLTitle  = 'Cartelera';                                                           
    $UpdateDate = '10 de Noviembre del 2017'; 
 if (empty($_SESSION)) {                                                                     
        $NewHTMLTitle        = "Error con Permisos";                                            
        $TitleErrorPage      = "Error con Permisos";                                            
        $MessageErrorPage    = "No iniciaste sesión en el Sistema";                             
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   
        $ButtonTextErrorPage = "Accede al Sistema";                                             
        include("Error.php");                                                                   
        exit();                                                                                 
    }
    
     $CompleteName = $_SESSION["CompleteUserName"];                                              
    $IAmAManager = false;                                                                       
    
    if ($_SESSION["IDGerente"] == $_SESSION["DataBaseID"]) {
    $IAmAManager = true;
}                
    include("PHP/HTMLHeader.php");     
      $ShowCartelera = isset($_POST['ShowCartelera']);
      $ShowPelicula = isset($_POST['ShowPelicula']);
?>

<br><br>

    <div class="container">


        <!-- ========  MATERIAL CARD  ================ -->
        <div class="card-panel center-align teal lighten-3">
               
            <h5 class="white-text">Ver información de los Horarios</h5>

            <span class="white-text">
        	  Informacion de las funciones
            </span>
            
            <br>
        </div>

 <?php 
        if ($ShowCartelera):                                                                    
            $fecha = $_POST['fecha'];  
            $DataBase = new mysqli("127.0.0.1", "root", "root", "Proyect");                     
            if (mysqli_connect_errno()) exit();                                                 
            
            $Query = 'SELECT f.* , p.Clasificacion , p.Nombre , p.Duracion, p.Descripcion FROM funcion f , pelicula p , sala s where p.ID=f.IDPelicula and f.NumeroSala=s.NumeroSala and f.Dia = "'.$fecha.'" ;';                                                 
            ?>

            <table class="centered hoverable striped responsive-table">
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
 Registros de la fecha :
                <?php
                echo $fecha ;
                if ($QueryResult = $DataBase->query($Query)) {
                    while ($Row = $QueryResult->fetch_row()) : ?>

                    <tr>
                    
                    <?php foreach ($Row as $Number => $Value): ?>

                        <td><?php echo $Value; ?></td>
                        
                    <?php endforeach; ?>

                    </tr>

                    <?php endwhile;
                    $QueryResult->close();
                }
                /* close connection */
                $DataBase->close(); 
                ?>

                </tbody>
            </table>

            <br>
    
            <?php endif;
        ?>
            
            <?php 
        if ($ShowPelicula):                                                                    
            $pelicula = $_POST['pelicula'];  
            $DataBase = new mysqli("127.0.0.1", "root", "root", "Proyect");                     
            if (mysqli_connect_errno()) exit();                                                 
            
            $Query = 'SELECT f.* , p.Clasificacion  , p.Duracion, p.Descripcion FROM funcion f , pelicula p , sala s where p.ID=f.IDPelicula and f.NumeroSala=s.NumeroSala and p.Nombre = "'.$pelicula.'" ;';                                                 
            ?>

            <table class="centered hoverable striped responsive-table">
                <thead>
                    <tr>
                          <th>Hora</th>
                          <th>Fecha</th>
                          <th>Sala</th>
                          <th>Precio</th>
                          <th>Tipo</th>
                          <th>IdPelicula</th>
                          <th>Clasificacion</th>
                          <th>Duracion (horas)</th>
                          <th>Descripcion </th>
                          
              
                    </tr>
                </thead>
<tbody>
 Registros de la pelicula :
                <?php
                echo $pelicula ;
                if ($QueryResult = $DataBase->query($Query)) {
                    while ($Row = $QueryResult->fetch_row()) : ?>

                    <tr>
                    
                    <?php foreach ($Row as $Number => $Value): ?>

                        <td><?php echo $Value; ?></td>
                        
                    <?php endforeach; ?>

                    </tr>

                    <?php endwhile;
                    $QueryResult->close();
                }
                /* close connection */
                $DataBase->close(); 
                ?>

                </tbody>
            </table>

            <br>
    
            <?php endif;
        ?>


            <form action="VerHorarios.php" method="post"><center>
                    Fecha de la cartelera <input class='validate' type='text' name='fecha' id='fecha' />
                        <label for='fecha'>Fecha de la cartelera </label>
    		<button class="btn waves-effect waves-light" type="submit" name="ShowCartelera">
    			Mostrar la cartelera
    		</button>
        </center></form>
            <form action="VerHorarios.php" method="post"><center> 
                
                Pelicula 
                <input class='validate' type='text' name='pelicula' id='pelicula' />
                        <label for='pelicula'> Horarios de pelicula </label>
    		<button class="btn waves-effect waves-light" type="submit" name="ShowPelicula">
    			Mostrar los horarios de la pelicula 
    		</button>
                </center> </form>

        <br><br><br><br>
        <br><br><br><br>

    </div>


<?php include("PHP/HTMLFooter.php"); ?>