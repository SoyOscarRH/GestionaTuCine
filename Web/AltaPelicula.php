<?php

   include("PHP/ForAllPages.php");                                                           
    
    $HTMLTitle  = 'Pelicula';                                                           
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
    
    if ($_SESSION["IDGerente"] == $_SESSION["ID"]) {
    $IAmAManager = true;
}                
    include("PHP/HTMLHeader.php");     
      $InsertPelicula = isset($_POST['InsertPelicula']);
      
?>

<?php
 if ($InsertPelicula):                                                                    
            $Nombre = $_POST['nombre'];
            $Clasificacion = $_POST['clasificacion'];
            $Duracion = $_POST['duracion'];
            $Descripcion = $_POST['descripcion'];
            $DataBase = new mysqli("127.0.0.1", "root", "root", "Proyect");                     
            if (mysqli_connect_errno()) exit();                                                 
            
            $Query = 'SELECT max(ID)from pelicula;';
            $QueryResult = $DataBase->query($Query);
             $Row = $QueryResult->fetch_row();
             $idPelicula=$Row[0] + 1;
           
             $DataBase2 = new mysqli("127.0.0.1", "root", "root", "Proyect");
             $Query2='INSERT INTO pelicula (ID , Clasificacion , Nombre , Duracion , Descripcion ) values ("'.$idPelicula.'","'.$Clasificacion.'" , "'.$Nombre.'" , "'.$Duracion.'" , "'.$Descripcion.'" );';
             mysqli_query($DataBase2, $Query2);
   
             ?>

<h5><center> alta exitosa </center> </h5> 
             <?php
          
             endif;
            ?>

<br><br>

    <div class="container">


        <!-- ========  MATERIAL CARD  ================ -->
        <div class="card-panel center-align teal lighten-3">
               
            <h5 class="white-text">Anote los datos de la pelicula</h5>

            
            
            <br>
        </div>


        <form action="AltaPelicula.php" method="post"><center>
                     Nombre Pelicula<input class='validate' type='text' name='nombre' id='nombre' />
                     Clasificacion <input class='validate' type='text' name='clasificacion' id='nombre'/>
                     Duracion (horas) <input class='validate' type='text'name="duracion" id="duracion"/>
                     Descripcion <input class="validate" type="text" name="descripcion" id="descripcion"/>                        
    		<button class="btn waves-effect waves-light" type="submit" name="InsertPelicula">
    			Insertar Pelicula
    		</button>
        </center></form>

    </div>