<?php
    /*=======================================================================================================================
    ============================================         ADMINISTRATOR POWERS          ======================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE FOR THE ADMINISTRADOR TO SEE THINGS */
    include("PHP/ForAllPages.php");                                                             //Dame todas las ventajas

    // ================ VARIABLES =============================
    $HTMLTitle  = $Title = 'Administrador';                                                  	//Titulo de cada Pagina
    $UpdateDate = '23 de Julio del 2017';                                                       //Fecha de actualizacion de pagina

    $AlertMessages = array();                                                                   //Mensajes que mostramos 
    $InfoEmployees = array();                                                                   //Info de los empleados  


    /*===================================================================
    ============         WE CAN ACCESS TO THE PAGE    ===================
    ===================================================================*/

    // ============ YOU HAVE LOGIN?  =================
    if (empty($_SESSION)) {                                                                     //Si ya iniciaste sesión
        $TitleErrorPage      = "Error Permisos";                                                //Error variables
        $MessageErrorPage    = "No iniciaste sesión en el Sistema";                             //Error variables
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   //Error variables
        $ButtonTextErrorPage = "Accede al Sistema";                                             //Error variables

        include("Error.php");                                                                   //Llama a la pagina de error
        exit();                                                                                 //Adios vaquero
    }

    // ============ OPEN THE DATA BASE ==============
    $DataBase = @new mysqli("127.0.0.1", "root", "root", "Proyect");                            //Abrir una conexión
    if ((mysqli_connect_errno() != 0) or !$DataBase) {                                          //Si hubo problemas
        $TitleErrorPage      = "Error con la BD";                                               //Error variables
        $MessageErrorPage    = "No podemos acceder a la base de datos";                         //Error variables
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   //Error variables
        $ButtonTextErrorPage = "Intenta otra vez";                                              //Error variables

        include("Error.php");                                                                   //Llama a la pagina de error
        exit();                                                                                 //Adios vaquero
    }

    $IAmAManager = false;                                                                       //Pero ... ¿Eres gerente?
    if ($_SESSION["IDGerente"] == $_SESSION["DataBaseID"]) $IAmAManager = true;                 //Pues pregunta :v


    /*===================================================================
    ============         GET THE DATABASE      ==========================
    ===================================================================*/

    //=============  IF YOU WANT TO MODIFY THE DATA  =============
    if ($IAmAManager and isset($_POST['CheckDataToChangeValues'])) {                            //Si es que quieres actualizar datos

        do {                                                                                    //while para usar el break XD

            //=============  GET THE DATA =============
            $ToChangeID = ClearSQLInyection(htmlspecialchars(trim($_POST['ID'])));              //Dame la info
            if (!is_numeric($ToChangeID)) {array_push($AlertMessages, "ID Invalido"); break;}   //Envia mensajes

            $Salary = ClearSQLInyection(htmlspecialchars(trim($_POST['Salary'])));              //Dame la info
            if (!is_numeric($Salary)) {array_push($AlertMessages, "Salario Invalido"); break;}  //Envia mensajes

            $Turn = ClearSQLInyection(htmlspecialchars(trim($_POST['Turn'])));                  //Dame el turno
            if ($Turn != "Matutino" and $Turn != "Vespetirno") {                                //Eres valido
                array_push($AlertMessages, "Turno Invalido"); break;}                           //O no?

            $Rol = ClearSQLInyection(htmlspecialchars(trim($_POST['Rol'])));                    //Dame el turno
            if ($Rol != "Taquilla" and $Rol != "Dulceria") {                                    //Eres valido
                array_push($AlertMessages, "Rol Invalido"); break;}                             //O no?

            //=============  VERIFY THE ID =============
            $QueryID = $DataBase->query(" SELECT ID FROM Empleado WHERE 
                                            IDGerente = {$_SESSION['DataBaseID']} AND
                                            ID != {$_SESSION['DataBaseID']}");                  //Haz la consulta

            if ($QueryID->num_rows == 0)                                                        //Si es que no hay tuplas
                array_push($AlertMessages, "No se puede acceder a Info de tus Empleados");      //Envia mensajes

            $ValidID = false;                                                                   //No puedes usar ese ID
            while (($PossibleID = $QueryID->fetch_assoc()) and !$ValidID)                       //Para cada uno
                if ($PossibleID['ID'] == $ToChangeID) $ValidID = true;                          //Mira, si que podias XD

            if (!$ValidID) {array_push($AlertMessages, "ID Invalido"); break;}                  //Envia mensajes

            //=============  CHANGE THE DATA =============
            $ValidQuery = $DataBase->query("
                UPDATE Empleado
                SET Turno = '{$Turn}', Sueldo = {$Salary}, RolActual = '{$Rol}'
                WHERE ID = {$ToChangeID};");                                                    //AHORA SI PUEDES HACER ESTO

            if (!$ValidQuery){array_push($AlertMessages, "Error al Actualizar Datos"); break;}  //Envia mensaje si todo mal
            else {array_push($AlertMessages, "Datos Actualizados");break;}                      //Envia mensaje si todo bien

        } while (false);                                                                        //Solo eras para el break
    }

    //============= SEE EMPLOYESS  =============
    if ($IAmAManager) {                                                                         //Si es que eres un manager
        $QueryInfoEmployees = $DataBase->query("
            SELECT * FROM Empleado WHERE 
                IDGerente = {$_SESSION['DataBaseID']} AND ID != {$_SESSION['DataBaseID']}");    //Haz la consulta

        if ($QueryInfoEmployees->num_rows == 0)                                                 //Si es que no hay tuplas
            array_push($AlertMessages, "No se puede acceder a Info de tus Empleados");          //Envia mensajes
    }










    // *****************************************************************************************
    // *************************     PROCESS TO START THE SYSTEM   *****************************
    // *****************************************************************************************
    include("PHP/HTMLHeader.php");                                                              //Incluimos un Asombroso Encabezado
?>
    <br><br>
    <div class="container center-align">
        
        <!-- ================================================================== -->    
        <!-- =====================    SHOW EMPLOYEES      ===================== -->      
        <!-- ================================================================== -->    
        <?php if ($IAmAManager):?>
        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">

            <!-- ========  TITLE  ================ -->
            <h4 class="grey-text text-darken-2">
                <b>Ver Información </b> de Empleados
            </h4>

            <!-- ========  TEXT  ================ -->
            <span class="grey-text" style="font-size: 1.25rem;">
                Acceder a un registro con todos los empleados activos
                <br><br>
            </span>

            <!-- ========  MATERIAL TABLE CARD  ================ -->
            <table
                id="EmployeesTables" 
                style="<?php if (!isset($_POST['CheckDataToChangeValues'])) echo "display: none; "?>font-size: 0.9rem"
                class="centered hoverable striped responsive-table bordered">

                <!-- ========  TITLES ================ -->
                <thead>
                    <tr>
                    <?php foreach ($QueryInfoEmployees->fetch_fields() as $Column): 
                        if ($Column->name == 'Contrasena' or $Column->name == 'IDGerente') continue;?>
                        <th><?php echo $Column->name; ?></th>
                    <?php endforeach; ?>
                    </tr>
                </thead>

                <!-- ========  CELLS ================ -->
                <tbody>
                <?php
                    while ($Row = $QueryInfoEmployees->fetch_assoc()) : 
                        array_push($InfoEmployees, $Row);?>
                    <tr>
                    
                    <?php foreach ($Row as $Name => $Value): 
                        if ($Name == 'Contrasena' or $Name == 'IDGerente') continue;?>
                        <td><?php echo $Value; ?></td>
                    <?php endforeach; ?>

                    </tr>

                    <?php endwhile;

                    $QueryInfoEmployees->close();
                ?>

                </tbody>
            </table>

            <br>

            <!-- ========  BUTTON ============= -->
    		<button 
                id="EmployeesTablesButton"
                class="btn waves-effect waves-light"
                name="ShowEmployees">
    			Ve los Empleados
    		</button>
            <script>
                $("#EmployeesTablesButton").click( function() {$("#EmployeesTables").toggle();});
            </script>

        </div>


        <br><br><br>


        <!-- ================================================================== -->    
        <!-- =====================    ALTER EMPLOYEES     ===================== -->      
        <!-- ================================================================== -->    
        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">
            
            <!-- ========  TITLE  ================ -->
            <h4 class="grey-text text-darken-2">
                <b>Modifica </b> Información de tus Empleados
            </h4>

            <!-- ========  TEXT  ================ -->
            <span class="grey-text" style="font-size: 1.25rem;">
                Selecciona un empleado y elige sus nuevas características
                <br><br>
            </span>

            <!-- ========  MATERIAL FORM  ================ -->
            <form class="container" action="AdminAccounts.php" method="post">

                <!-- ========  EMPLOYEERS ID ============= -->
                <div class="input-field">                        
                    <select name="ID" id="ID" class="left-align">
                        <?php foreach ($InfoEmployees as $Row): 
                            $TemporalCompleteName = $Row['ApellidoPaterno']." ".$Row['ApellidoMaterno']." ".$Row['Nombre'];?>
                        <option value="<?php echo $Row["ID"];?>"><?php echo $Row['ID']." - ".$TemporalCompleteName;?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>ID del Empleado</label>
                </div>

                <!-- ========  WORK TIME ========== -->
                <div class="input-field">                        
                    <select name="Rol" id="Rol">
                        <option value="Dulceria">Dulceria</option>
                        <option value="Taquilla">Taquilla</option>
                    </select>
                    <label>Rol Actual</label>
                </div>

                <!-- ========  WORK TIME ========== -->
                <div class="input-field">                        
                    <select name="Turn" id="Turn">
                        <option value="Matutino">Matutino</option>
                        <option value="Vespetirno">Vespetirno</option>
                    </select>
                    <label>Turno</label>
                </div>

                <!-- ========  SALARY ============= -->
                <div class='input-field'>
                    <input class='validate' type='number' name='Salary' id='Salary' />
                    <label>Sueldo</label>
                </div>

                <!-- ========  BUTTON TO SEND ===== -->
                <button 
                    type='submit'
                    name='CheckDataToChangeValues'
                    class='col s10 m6 l6 offset-s1 offset-m3 offset-l3 btn btn-large waves-effect indigo lighten-1'>
                    Cambiar Valores
                </button>

            </form>

        </div>
        

        <?php endif; ?>

        <br><br><br><br>

    </div>



    <script>
        $(document).ready(function() {
            $('select').material_select();

            <?php 
                $TitleAlert = '<span class = "yellow-text"><b>Alerta: &nbsp; </b></span>';
                foreach ($AlertMessages as $Alert) echo "Materialize.toast('$TitleAlert $Alert', 9000);"; 
            ?>


            $("#ID").change(function() {
                let SelectedID = $('#ID').val();

                <?php foreach ($InfoEmployees as $Row): ?>
                
                    if (SelectedID == <?php echo $Row['ID'];?> ) {
                        $('#Rol').val("<?php echo $Row['RolActual'] ?>");
                        $('#Salary').val("<?php echo $Row['Sueldo'] ?>");
                        $('#Turn').val("<?php echo $Row['Turno'] ?>");
                    }

                <?php endforeach;?>

                Materialize.updateTextFields();
            });

            $('#ID').trigger('change');

        });
    </script>


<?php include("PHP/HTMLFooter.php"); $DataBase->close(); ?>