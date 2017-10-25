<?php
    /*=======================================================================================================================
    ============================================         LOGIN PROMPT          ==============================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE */
    include("/Applications/XAMPP/xamppfiles/htdocs/ManageYourCinema/Web/PHP/ForAllPages.php");  //Dame todas las ventajas que tiene incluir

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Inicia Sesión';                                                      		//Titulo de cada Pagina
    $Title      = 'Inicia Sesión en el Sistema';                                                //Titulo de cada Pagina
    $UpdateDate = '23 de Julio del 2017';                                                       //Fecha de actualizacion de la pagina



	// ========== SPECIFIC FOR THIS SCRIPT ==========
	$WarningMessage 	  = " ";
	$ErrorAccountMessage  = " ";
	$ErrorPasswordMessage = " ";

    $AccountNumber = " ";
    $Password      = " ";
    $IDSesion      = " ";    

    $ErrorCode = 0;                                                                         


    // *****************************************************************************************
    // *************************     PROCESS TO START THE SYSTEM   *****************************
    // *****************************************************************************************





    if ( isset($_POST['CheckDataToEnterSystem']) ){

        // ================================================================================
        // ======================  TRY TO GET ACCOUNT NUMBER   ============================
        // ================================================================================
        if (isset($_POST['AccountNumber'])) {                                                                     
            
            $AccountNumber = ClearSQLInyection(htmlspecialchars(trim($_POST['NumeroDeCuenta'])));  	//Recoge el numero de cuenta y le quita lo feo


	        // ================================================================================
	        // ===========    REVISA CONTRASEÑA, DATOS E INICIA DE SESION   ===================
	        // ================================================================================
	        if ($ErrorCode == 0) {                                                                    	//Entremos y veamos la informacion necesaria   
	            

	            /*
	            $BasesDeDatos = pg_connect("host='127.0.0.1'  port='5432'  user='postgres' password='cp&sA2785' dbname='datos'");                                                    	//Se establece conexion
	          
	            $Consulta = "SELECT apellidop||' '||apellidom||' '||nombre,md5(fechanac),fechanac
	                            FROM dgae_dp
	                            WHERE cuenta='".$NumeroDeCuenta."'";

	            if ($InfoBasesDatosMini = pg_fetch_row(pg_query($BasesDeDatos, $Consulta))){        //Si existe ese alumno
	                $NombreAlumno     = htmlentities(utf8_decode($InfoBasesDatosMini[0]));          //Regresa el nombre del Alumno
	                $Contraseña       = $InfoBasesDatosMini[1];                                     //Dame su contraseña 
	                $FechaNacimiento  = $InfoBasesDatosMini[2];                                     //Y su fecha de nacimiento
	            }

	            */
	        }

    	}
   	}



            











    include($PHPDocumentRoot."PHP/HTMLHeader.php");                                             //Incluimos un Asombroso Encabezado
?>


	
	<?php 

    	$mysqli = new mysqli("127.0.0.1", "root", "hola", "Proyect");


    	if (mysqli_connect_errno()) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}
				

		$Consulta = "SELECT * FROM Empleado;";


		if ($result = $mysqli->query($Consulta)) {

		    /* fetch object array */
		    while ($row = $result->fetch_row()) {
		    	foreach ($row as $key => $value) {
		    		echo $key." ".$value;	
		    		echo "<br>";
		    	};
		    	echo "<br><br>";
		    }

		    /* free result set */
		    $result->close();
		}

		/* close connection */
		$mysqli->close();




	?>

	<?php include($PHPDocumentRoot."PHP/HTMLFooter.php"); ?>

