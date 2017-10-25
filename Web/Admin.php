<?php
    /*=======================================================================================================================
    ============================================         ADMINISTRATOR POWERS          ======================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE FOR THE ADMINISTRADOR TO SEE THINGS */
    include("/Applications/XAMPP/xamppfiles/htdocs/ManageYourCinema/Web/PHP/ForAllPages.php");  //Dame todas las ventajas que tiene incluir

    // ================ VARIABLES =============================
    $HTMLTitle  = $Title = 'Administrador';                                                  	//Titulo de cada Pagina
    $UpdateDate = '23 de Julio del 2017';                                                       //Fecha de actualizacion de la pagina




    // *****************************************************************************************
    // *************************     PROCESS TO START THE SYSTEM   *****************************
    // *****************************************************************************************
    include($PHPDocumentRoot."PHP/HTMLHeader.php");                                             //Incluimos un Asombroso Encabezado
?>
	
	<br>
	<br>
	<br>
	<br>
	<br>


	<div class="container">
        <div class="card-panel teal lighten-3">

        <center><h4 class="white-text">Ve información Empleados</h4>
        <br>
        <span class="white-text">
          	Aqui ira un texto que tenga sentido
        </span>
        </center>
        </div>
    </div>




	<!-- ===== BUTTON ===== -->
	<form action="Admin.php" method="post"><center>

		<button class="btn waves-effect waves-light" type="submit" name="ShowEmployees">
			Ve los Empleados
		</button>
        

    </center></form>


    <br>
    <br>
    <br>
    <br>


    <?php 
	    if ( isset($_POST['ShowEmployees']) ) : ?>

	<div class="container">
		
	    <table class="centered striped responsive-table">
        <thead>
          <tr>
              <th>ID</th>
              <th>Sueldo</th>
              <th>Turno</th>
              <th>Genero</th>
              <th>Nombre</th>
              <th>Apellido 1</th>
              <th>Apellido 2</th>
              <th>Correo</th>
              <th>Constraseña</th>
              <th>GerenteID</th>
          </tr>
        </thead>

        <tbody>

	        <?php
		    	
		    	$DataBase = new mysqli("127.0.0.1", "root", "hola", "Proyect");

		    	if (mysqli_connect_errno()) exit();
						
				$Consulta = "SELECT * FROM Empleado;";

				if ($result = $DataBase->query($Consulta)) {

				    while ($Row = $result->fetch_row()) : ?>

          				<tr>

				    		<?php foreach ($Row as $Number => $Value): ?>
						        <td><?php echo $Value; ?></td>

				    		<?php endforeach; ?>
				    	</tr>
				    <?php endwhile;

				    $result->close();
				}

				/* close connection */
				$DataBase->close();

		    endif; ?>

	    </tbody>
      	</table>

      </div>




	<br>
	<br>
	<br>
	<br>
	<br>


	<?php include($PHPDocumentRoot."PHP/HTMLFooter.php"); ?>
