<?php
   include("PHP/ForAllPages.php");                                                           
    
    $HTMLTitle  = 'Funcion';                                                           
    $UpdateDate = '21 de Noviembre del 2017'; 
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
      $InsertFuncion = isset($_POST['InsertFuncion']);
      
?>

<?php                        
 if ($InsertFuncion):   
            $NumeroF = $_POST['Numero'];  
            $Hora= $_POST['Hora'];
            $Dia = $_POST['Dia'];
            $Sala= $_POST['Sala'];
            $Precio = $_POST['Precio'];
            $Tipo = $_POST['Tipo'];
            $Pelicula = $_POST['Pelicula'];
            $DataBase = new mysqli("127.0.0.1", "root", "root", "Proyect");                     
            if (mysqli_connect_errno()) exit();                                                 
            
            $Query = 'SELECT max(numeroF)from funcion;';
            $QueryResult = $DataBase->query($Query);
             $Row = $QueryResult->fetch_row();
             $NumeroF=$Row[0] + 1;
           
             $DataBase2 = new mysqli("127.0.0.1", "root", "root", "Proyect");
             $Query2='INSERT INTO pelicula (numeroF,hora, dia, numSala, precio, tipo, idPeli ) values ("'.$numeroF.'","'.$Hora.'","'.$Dia.'" , "'.$Sala.'" , "'.$Precio.'" , "'.$Tipo.'" , "'.$Pelicula.'" );';
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
               
            <h5 class="white-text">Ingrese los datos de la funcion</h5>

            
            
            <br>
        </div>
 
        <form action="AltaFuncion.php" method="post"><center>
                     Numero Funcion<input class='validate' type='text' name='numeroF' id='numeroF' />
                     Hora <input class='validate' type='text' name='clasificacion' id='hora'/>
                     Dia <input class='validate' type='text' name='clasificacion' id='dia'/>
                     Sala <input class='validate' type='text' name='clasificacion' id='sala'/>
                     Precio (horas) <input class='validate' type='text'name="duracion" id="precio"/>
                     Tipo <input class="validate" type="text" name="descripcion" id="tipo"/>   
                     Pelicula <input class='validate' type='text' name='clasificacion' id='pelicula'/>
    		<button class="btn waves-effect waves-light" type="submit" name="InsertFuncion">
    			Agregar Funcion
    		</button>
        </center></form>

    </div>