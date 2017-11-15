<?php
   include("./PHP/ForAllPages.php");  
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
     $EditarP = isset($_POST['EditarP']);
      $CambiarPer= isset($_POST['CloseSession']);
       $MiPerfil = isset($_POST['MiPerfil']);
     $PasswordC="-1";
     if($EditarP)
         {
     $PasswordC=$_POST["PasswordC"];}
    
?>


<?php  
if ($CambiarPer):
    
     
      $NombreNew=$_POST['NombreC'];
     $ApPaternoNew=$_POST['Surname1'];
      $ApMaternoNew=$_POST['Surname2'];
       $CorreoNew=$_POST['Correo'];
        $PasswordNew=sha1($_POST['Password']."ManageYourCinemaSalt");
        $GeneroNew=$_POST['GeneroC'];
              
            $DataBase = new mysqli("127.0.0.1", "root", "root", "Proyect");                     
            if (mysqli_connect_errno()) exit();   
            
            
            $Query = 'UPDATE Empleado   set   Nombre="'.$NombreNew.'", ApellidoPaterno="'.$ApPaternoNew.'", ApellidoMaterno="'.$ApMaternoNew.'" ,Correo="'.$CorreoNew.'" , Contrasena ="'.$PasswordNew.'" , Genero="'.$GeneroNew.'" WHERE ID='.$_SESSION["DataBaseID"].' ;';  
             mysqli_query($DataBase, $Query);
           $NewHTMLTitle        = "Cerrar Sesión";                                                 //Cambia el titulo de pag error
        $TitleErrorPage      = "Sesión Cerrado";                                                //Error variables
        $MessageErrorPage    = "La sesión se ha cerrado";                                       //Error variables
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   //Error variables
        $ButtonTextErrorPage = "Accede (otra vez) al Sistema";                                  //Error variables
        session_destroy();


        include("Error.php");                                                                   //Llama a la pagina de error
     
                                                                          //Llama a la pagina de error
        exit();
    
?>

<?php  endif; ?>

<?php
if ($EditarP && ($PasswordC == $_SESSION["Password"])):
     $MiPerfil= isset($_POST["MiPerfil"]);
     $CambiarPer = isset($_POST["CambiarPer"]);
      $NombreC = $_SESSION["Name"];
      $Surname1=$_SESSION["Surname1"];
      $Surname2=$_SESSION["Surname2"];
      $Sueldo =$_SESSION["Sueldo"];
      $Genero =$_SESSION["Genero"];
      $Turno = $_SESSION["Turno"];
      $Correo = $_SESSION["Email"];
      $Rol=$_SESSION["Rol"];
        ?>
 <h5 class="white-text">Estos son los campos que puedes editar</h5>
 
 <table class="centered hoverable striped responsive-table">
     <form action="MiPerfil.php" method="Post">
                <tr> <th>Nombre </th> 
                    <th><input type="text" value="<?php echo $NombreC ?>" name="NombreC"</th>
                
                 </tr>
                 
                 <tr> <th>Apellido Paterno </th> 
                     <th><input type="text" value="<?php echo $Surname1 ?>" name="Surname1"> </th> </tr>
                   <tr> <th>Apellido Materno </th> 
                     <th><input type="text" value="<?php echo $Surname2 ?>" name="Surname2"> </th> </tr>
                    
                 
                 <tr> <th>Genero </th> 
                    <th><input type="text" value="<?php echo $Genero ?>" name="GeneroC"> </th>
                 </tr>
             
                    <tr> <th>Correo </th> 
                   <th><input type="text" value="<?php echo $Correo  ?>" name="Correo"> </th>
                
                 </tr>
                    <tr> <th>Contraseña </th> 
                   <th><input type="password" value="<?php echo $PasswordC ?>" name="Password"> </th>
                
                 </tr>
                                      
 </table><center>
     <h5 class="white-text">Su sesion sera cerrada para efectuar el cambio</h5>
 <button class="btn waves-effect waves-light" type="submit" name="CloseSession">
    			Cambiar
    		</button>
</form>
 </center>
    <?php
    
    
endif;
?>
 
 <?php if ($MiPerfil || ($PasswordC != $_SESSION["Password"]) ): ?>
<br><br>

    <div class="container">


        <!-- ========  MATERIAL CARD  ================ -->
        <div class="card-panel center-align teal lighten-3">
               
            <h5 class="white-text">Informacion del usuario</h5>

            <table class="centered hoverable striped responsive-table">
                <tr> <th>Nombre </th> 
                    <th><?php echo $_SESSION["CompleteUserName"] ?> </th>
                
                 </tr>
                 <tr> <th>Genero </th> 
                    <th><?php echo $_SESSION["Genero"] ?> </th>
                
                 </tr>
                  <tr> <th>Turno </th> 
                    <th><?php echo $_SESSION["Turno"]  ?> </th>
                
                 </tr>
                    <tr> <th>Correo </th> 
                    <th><?php echo $_SESSION["Email"]  ?> </th>
                
                 </tr>
                
                    <tr> <th>Rol Actual </th> 
                    <th><?php echo $_SESSION["Rol"]  ?> </th>
                
                 </tr>
                    <tr> <th>Sueldo </th> 
                    <th><?php echo $_SESSION["Sueldo"]  ?> </th>
                
                 </tr>
                                      
            </table>
            
            <h5 class="white-text">Para editar escriba su contraseña</h5>
            
            <form action="MiPerfil.php" method="Post">
             <center>
                    Contraseña <input class='validate' type='text' name='PasswordC' id='PasswordC' />
                        
    		<button class="btn waves-effect waves-light" type="submit" name="EditarP">
    			Editar
    		</button>
        </center>                
                
            </form>
            
            <br>
        </div>
        <?php        endif; ?>