<?php
    /*=======================================================================================================================
    ============================================         ADMINISTRATOR POWERS          ======================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE FOR THE ADMINISTRADOR TO SEE THINGS */
    include("/Applications/XAMPP/xamppfiles/htdocs/ManageYourCinema/Web/PHP/ForAllPages.php");  //Dame todas las ventajas que tiene incluir

    // ================ VARIABLES =============================
    $HTMLTitle  = $Title = 'Administrador';                                                  	//Titulo de cada Pagina
    $UpdateDate = '23 de Julio del 2017';                                                       //Fecha de actualizacion de la pagina


    $ShowEmployees = isset($_POST['ShowEmployees']);

    // *****************************************************************************************
    // *************************     PROCESS TO START THE SYSTEM   *****************************
    // *****************************************************************************************
    include($PHPDocumentRoot."PHP/HTMLHeader.php");                                             //Incluimos un Asombroso Encabezado
?>

    <br><br>

    <div class="container">


        <!-- ========  MATERIAL CARD  ================ -->
        <div class="card-panel center-align teal lighten-3">
               
            <h5 class="white-text">Ver información de los Empleados</h5>

            <span class="white-text">
        	   Acceder a un registro con todos los empleados activos
            </span>
            
            <br>
        </div>

        <?php 
        if ($ShowEmployees):                                                                    //Si quieres ver empleados
            
            $DataBase = new mysqli("127.0.0.1", "root", "hola", "Proyect");                     //Abrimos una conexión
            if (mysqli_connect_errno()) exit();                                                 //Si es que no hay problemas
            
            $Consulta = "SELECT * FROM Empleado;";                                              //Nuestra consulta
            ?>

            <table class="centered hoverable striped responsive-table">
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
                          <th>ID del Gerente</th>
                    </tr>
                </thead>

                <tbody>

                <?php
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
                ?>

                </tbody>
            </table>

            <br>
    
            <?php endif;
        ?>


    	<form action="Admin.php" method="post"><center>
    		<button class="btn waves-effect waves-light" type="submit" name="ShowEmployees">
    			Ve los Empleados
    		</button>
        </center></form>

        <br><br><br><br>
        <br><br><br><br>

    </div>


	<?php include($PHPDocumentRoot."PHP/HTMLFooter.php"); ?>
