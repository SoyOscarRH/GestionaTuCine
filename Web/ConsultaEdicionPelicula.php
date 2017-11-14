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
      $ShowPelicula = isset($_POST['ShowPelicula']);
      $UpdatePelicula = isset($_POST['UpdatePelicula']);
      $MoviesSchedules=isset($_POST['MoviesSchedules']);
              $Cambios= isset($_POST['Cambios']);
      ?>

<br><br>

    <div class="container">


        <!-- ========  MATERIAL CARD  ================ -->
        <div class="card-panel center-align teal lighten-3">
               
            <h5 class="white-text">Ver información de las peliculas</h5>

            
            
            <br>
        </div>
         <?php 
  if ($Cambios): 
      $ClasificacionNew=$_POST['Clasificacion'];
     $NombreNew=$_POST['Nombre'];
      $DuracionNew=$_POST['Duracion'];
       $DescripcionNew=$_POST['Descripcion'];
        $idPeli=$_POST['indice'];
              
            $DataBase = new mysqli("127.0.0.1", "root", "root", "Proyect");                     
            if (mysqli_connect_errno()) exit();   
            
            
            $Query = 'UPDATE pelicula   set   Clasificacion="'.$ClasificacionNew.'", Nombre="'.$NombreNew.'", Duracion="'.$DuracionNew.'" ,Descripcion="'.$DescripcionNew.'" WHERE ID='.$idPeli.' ;';  
             mysqli_query($DataBase, $Query);


             echo $Query;
      
            ?>
      
   
            <form action="ConsultaEdicionPelicula.php" method="post"><center>
                    Nombre de la pelicula <input class='validate' type='text' name='nombre' id='fecha' />
                        
    		<button class="btn waves-effect waves-light" type="submit" name="ShowPelicula">
    			Mostrar la cartelera
    		</button>
        </center></form>
<?php        endif; ?>      

 <?php 
        if ($UpdatePelicula):  
            $peliculae=$_POST['peliculae'];
              $array = array("Clasificacion", "Nombre" , "Duracion" , "Descripcion" , "id" );
            $DataBase = new mysqli("127.0.0.1", "root", "root", "Proyect");                     
            if (mysqli_connect_errno()) exit();   
            
            
            $Query = 'SELECT clasificacion, nombre , duracion , descripcion, id from pelicula where nombre = "'.$peliculae.'" ;';                                                 
            ?>

            <table class="centered hoverable striped responsive-table">
                <thead>
                    <tr>
                         
                          <th>Clasificacion</th>
                          <th>Nombre</th>
                          <th>Duracion</th>
                          <th>Descripcion</th>
                                                  
              
                    
                          
              
                    </tr>
                </thead>
<tbody>
   <?php
                $p=0;
                $k=0;
            
                ?>

<form action="ConsultaEdicionPelicula.php" method="post">
                <?php
                if ($QueryResult = $DataBase->query($Query)) {
                    while ($Row = $QueryResult->fetch_row()) : ?>
                       
                    <tr>
                     
                   <?php  while ($k<4) : ?>
                         
                        <th> <input type="text" value="<?php echo $Row[$k]?>" name="<?php echo$array[$k]?>">  </th>
                        
                  <?php     $k=$k+1;    endwhile;  ?>
                    <input type="hidden" value="<?php echo $Row[$k]?>" name="indice" > 
                    </tr>
         
                    <?php
                $k=0;
                    endwhile;
                    $QueryResult->close();
                }
                /* close connection */
                $DataBase->close(); 
                ?>

                </tbody>
            </table>

            <br>
            <button class="btn waves-effect waves-light" type="submit" name="Cambios">
    			Cambiar
    		</button>
</form>
            <?php endif;
        ?>
            
            <?php 
        if ($ShowPelicula):                                                                    
            $pelicula = $_POST['nombre'];
           
            $DataBase = new mysqli("127.0.0.1", "root", "root", "Proyect");                     
            if (mysqli_connect_errno()) exit();                                                 
            
            $Query = 'SELECT * from pelicula where Nombre = "'.$pelicula.'" ;';                                                 
            ?>

            <table class="centered hoverable striped responsive-table">
                <thead>
                    <tr>
                          <th>Id</th>
                          <th>Clasificacion</th>
                          <th>Nombre</th>
                          <th>Duracion</th>
                          <th>Descripcion</th>
                                                  
              
                    </tr>
                </thead>
<tbody>
 Registros de la pelicula :
                <?php
               
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
                    <form action="ConsultaEdicionPelicula.php" method="post"> <center>
                <button class="btn waves-effect waves-light" type="submit" name="MoviesSchedules">
    			Buscar otra pelicula
    		</button>
                </center>
                
            </form> 
               <form action="ConsultaEdicionPelicula.php" method="post"> <center>
                       <input type="hidden" value="<?php echo $pelicula?>" name="peliculae"> 
                <button class="btn waves-effect waves-light" type="submit" name="UpdatePelicula">
    			Editar
    		</button>
                </center>
                
            </form>
            
            <?php endif;
        ?>
<?php 
        if ($MoviesSchedules): ?>
            <form action="ConsultaEdicionPelicula.php" method="post"><center>
                    Nombre de la pelicula <input class='validate' type='text' name='nombre' id='fecha' />
                        
    		<button class="btn waves-effect waves-light" type="submit" name="ShowPelicula">
    			Mostrar la pelicula
    		</button>
        </center></form>
<?php        endif; ?>            

        <br><br><br><br>
        <br><br><br><br>

    </div>


<?php include("PHP/HTMLFooter.php"); ?>

