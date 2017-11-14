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
      $NombreC = $_SESSION["CompleteUserName"];
      $Sueldo =$_SESSION["Sueldo"];
      $Genero =$_SESSION["Genero"];
      $Turno = $_SESSION["Turno"];
      $Correo = $_SESSION["Email"];
?>
<br><br>

    <div class="container">


        <!-- ========  MATERIAL CARD  ================ -->
        <div class="card-panel center-align teal lighten-3">
               
            <h5 class="white-text">Informacion del usuario</h5>

            <table class="centered hoverable striped responsive-table">
                <tr> <th>Nombre </th> 
                    <th><?php echo $NombreC ?> </th>
                
                 </tr>
                 <tr> <th>Genero </th> 
                    <th><?php echo $Genero ?> </th>
                
                 </tr>
                  <tr> <th>Turno </th> 
                    <th><?php echo $Turno ?> </th>
                
                 </tr>
                    <tr> <th>Correo </th> 
                    <th><?php echo $Correo ?> </th>
                
                 </tr>
                    <tr> <th>Sueldo </th> 
                    <th><?php echo $Sueldo ?> </th>
                
                 </tr>
                 
                 
                
                
            </table>
            
            
            <br>
        </div>