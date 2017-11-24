<?php
include("PHP/ForAllPages.php");   



    
    $HTMLTitle  = 'Editar Cartelera';                                                           
    $UpdateDate = '11 de Noviembre del 2017'; 
 if (empty($_SESSION)) {                                                                     
        $NewHTMLTitle        = "Error con Permisos";                                            
        $TitleErrorPage      = "Error con Permisos";                                            
        $MessageErrorPage    = "No iniciaste sesión en el Sistema";                             
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   
        $ButtonTextErrorPage = "Accede al Sistema";                                             
        include("Error.php");                                                                   
        exit();                                                                                 
    }



    if ($_SESSION["IDGerente"] != $_SESSION["ID"]) {                                    //Si ya iniciaste sesión
        $TitleErrorPage      = "Error Permisos";                                                //Error variables
        $MessageErrorPage    = "No eres gerente, no puedes acceder a esto";                     //Error variables
        $ButtonLinkErrorPage = $HTMLDocumentRoot."MenuEmployeeOrManager.php";                   //Error variables
        $ButtonTextErrorPage = "Ve tus opciones";                                               //Error variables

        include("Error.php");                                                                   //Llama a la pagina de error
        exit();                                                                                 //Adios vaquero
    }

    
    
     $CompleteName = $_SESSION["CompleteUserName"];                                              
    $IAmAManager = false;                                                                       
    
    include("PHP/HTMLHeader.php");     
    $CambiarCartelera = isset($_POST['CambiarCartelera']);
    $n=0;
?>

<br><br>

    <div class="container">


        <!-- ========  MATERIAL CARD  ================ -->
        <div class="card-panel center-align teal lighten-3">
               
            <h5 class="white-text">Edite la  información de los Horarios</h5>

            <span class="white-text">
        	   informacion de la funciones 
            </span>
            
            <br>
        </div>

 <?php 
 
        if ($CambiarCartelera):  
            $array = array("Hora",  "Sala", "Precio", "Tipo",  "Clasificacion", "Nombre" , "Duracion" , "Descripcion" );
            $fecha = $_POST['fecha'];  
            $DataBase = new mysqli("127.0.0.1", "root", "root", "Proyect");                     
            if (mysqli_connect_errno()) exit();                                                 
            
            $Query = 'SELECT f.Hora, f.NumeroSala, f.precio, f.tipo , p.Clasificacion , p.Nombre , p.Duracion, p.Descripcion FROM funcion f , pelicula p , sala s where p.ID=f.IDPelicula and f.NumeroSala=s.NumeroSala and f.Dia = "'.$fecha.'" ;';                                                 
            ?>

            <table class="centered hoverable striped responsive-table">
                <thead>
                    <tr>
                          <th>Hora</th>
                          
                          <th>Sala</th>
                          <th>Precio</th>
                          <th>Tipo</th>
                       
                          <th>Clasificacion</th>
                          <th>Nombre</th>
                          <th>Duracion (horas)</th>
                          <th>Descripcion </th>
                          
              
                    </tr>
                </thead>
<tbody>
 Registros de la fecha :
                <?php
                $p=0;
                $k=0;
                echo $fecha ;
                ?>

                 <form action="UpdateCartelera.php" method="post">
                <?php
                if ($QueryResult = $DataBase->query($Query)) {
                    while ($Row = $QueryResult->fetch_row()) : ?>
                       
                    <tr>
                     
                   <?php  while ($k<8) : ?>
                         
                        <th> <input type="text" value="<?php echo $Row[$k]?>" name="<?php echo$array[$k]?>">  </th>
                        
                  <?php     $k=$k+1;   endwhile; ?>

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

            <form action="CambiarHorario.php" method="post"><center>
                    Fecha de la cartelera <input class='validate' type='text' name='fecha' id='fecha' />
                        <label for='fecha'>Fecha de la cartelera </label>
    		<button class="btn waves-effect waves-light" type="submit" name="CambiarCartelera">
    			Mostrar la cartelera
    		</button>
        </center></form>