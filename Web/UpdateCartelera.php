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
     $array = array("Hora",  "Sala", "Precio", "Tipo",  "Clasificacion", "Nombre" , "Duracion" , "Descripcion" );
$Hora = $_POST['Hora']; 
$Sala= $_POST['Sala'];
$Precio=$_POST['Precio'];
$Tipo=$_POST['Tipo'];
$Clasificacion=$_POST['Clasificacion'];
$Nombre=$_POST['Nombre'];
$Duracion= $_POST['Duracion'];
$Descripcion=$_POST['Descripcion']; 

?>
