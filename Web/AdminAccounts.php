<?php
    /*=======================================================================================================================
    ============================================         ADMINISTRATOR POWERS          ======================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE FOR THE ADMINISTRADOR TO SEE THINGS */
    include("PHP/ForAllPages.php");                                                             //Dame todas las ventajas

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Administrador';                                                              //Titulo de cada Pagina
    $UpdateDate = '23 de Julio del 2017';                                                       //Fecha de actualizacion de pagina

    $AlertMessages = array();                                                                   //Mensajes que mostramos 
    $InfoEmployees = array();                                                                   //Info de los empleados  

    StandardCheckForStartedSession();                                                           //Asegurate de que pueda estar aqui
    $DataBase = StandardCheckForCorrectDataBase();                                              //Asegurate de que pueda estar aqui

    if ($_SESSION["IAmAManager"] == false) CallErrorPageOnlyForAdmins();                        //Si no tienes permiso de estar aqui

    /*===================================================================
    ============         GET THE DATABASE      ==========================
    ===================================================================*/

    //=============  IF YOU WANT TO MODIFY THE DATA  =============
    if ($_SESSION["IAmAManager"] and isset($_POST['CheckDataToChangeValues'])) {                //Si es que quieres actualizar datos

        do {                                                                                    //while para usar el break XD

            //=============  GET THE DATA =============
            $ToChangeID = ClearSQLInyection($_POST['ID']);                                      //Dame la info
            if (!is_numeric($ToChangeID)) {array_push($AlertMessages, "ID Invalido"); break;}   //Envia mensajes

            $Salary = ClearSQLInyection($_POST['Salary']);                                      //Dame la info
            if (!is_numeric($Salary)) {array_push($AlertMessages, "Salario Invalido"); break;}  //Envia mensajes

            $Turn = ClearSQLInyection($_POST['Turn']);                                          //Dame el turno
            if ($Turn != "Matutino" and $Turn != "Vespetirno") {                                //Eres valido
                array_push($AlertMessages, "Turno Invalido"); break;}                           //O no?

            $Rol = ClearSQLInyection($_POST['Rol']);                                            //Dame el turno
            if ($Rol != "Taquilla" and $Rol != "Dulceria") {                                    //Eres valido
                array_push($AlertMessages, "Rol Invalido"); break;}                             //O no?

            //=============  VERIFY THE ID =============
            $QueryID = $DataBase->query(" SELECT ID FROM Empleado WHERE 
                                            IDGerente = {$_SESSION['ID']} AND
                                            ID != {$_SESSION['ID']}");                          //Haz la consulta

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
    $QueryInfoEmployees = $DataBase->query("
        SELECT * FROM Empleado WHERE 
            IDGerente = {$_SESSION['ID']} AND ID != {$_SESSION['ID']}");                        //Haz la consulta

    if ($QueryInfoEmployees->num_rows == 0)                                                     //Si es que no hay tuplas
        array_push($AlertMessages, "No se puede acceder a Info de tus Empleados");              //Envia mensajes







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
                        if ($Column->name == 'Contrasena' or $Column->name == 'ID' or $Column->name == 'IDGerente') continue;?>
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
                            if ($Name == 'Contrasena' or $Name == 'ID' or $Name == 'IDGerente') continue;?>
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
                $("#EmployeesTablesButton").click( function() {
                    $("#EmployeesTables").toggle();
                });
            </script>

        </div>


        <br><br>


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
                        <option value="<?php echo $Row["ID"];?>"><?php echo $TemporalCompleteName;?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>Nombre del Empleado</label>
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


        <br><br><br><br>


        <!-- ================================================================== -->    
        <!-- =====================      ADD EMPLOYEES     ===================== -->      
        <!-- ================================================================== -->    
        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">
            
            <!-- ========  TITLE  ================ -->
            <h4 class="grey-text text-darken-2">
                <b>Añade</b> un Empleado
            </h4>

            <!-- ========  TEXT  ================ -->
            <span class="grey-text" style="font-size: 1.25rem;">
                Añade un empleado o administrador al sistema
                <br><br>
            </span>

            <!-- ========  MATERIAL FORM  ================ -->
            <form class="container" action="AdminAccounts.php" method="post">

                <!-- ========  EMPLOYEERS ID ============= -->
                <div class="input-field">                        
                    <select name="ID" id="ID" class="left-align">
                        <?php foreach ($InfoEmployees as $Row): 
                            $TemporalCompleteName = $Row['ApellidoPaterno']." ".$Row['ApellidoMaterno']." ".$Row['Nombre'];?>
                        <option value="<?php echo $Row["ID"];?>"><?php echo $TemporalCompleteName;?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>Nombre del Empleado</label>
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
                    class='col s10 m6 l6 offset-s1 offset-m3 offset-l3 btn btn-large waves-effect green lighten-1'>
                    Cambiar Valores
                </button>

            </form>

        </div>

        <br><br>
        

        <!-- ================================================================== -->    
        <!-- =====================    DELETE EMPLOYEES     ==================== -->      
        <!-- ================================================================== -->    
        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">
            
            <!-- ========  TITLE  ================ -->
            <h4 class="grey-text text-darken-2">
                <b>Elimina</b> a un Empleado
            </h4>

            <!-- ========  TEXT  ================ -->
            <span class="grey-text" style="font-size: 1.25rem;">
                Añade un empleado o administrador al sistema
                <br><br>
            </span>


            <!-- ========  MATERIAL FORM  ================ -->
            <form class="container" action="AdminAccounts.php" method="post">

                <!-- ========  EMPLOYEERS ID ============= -->
                <div class="input-field">                        
                    <select name="ID" id="ID" class="left-align">
                        <?php foreach ($InfoEmployees as $Row): 
                            $TemporalCompleteName = $Row['ApellidoPaterno']." ".$Row['ApellidoMaterno']." ".$Row['Nombre'];?>
                        <option value="<?php echo $Row["ID"];?>"><?php echo $TemporalCompleteName;?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>Nombre del Empleado</label>
                </div>

            </form>

        </div>


        <br><br><br>


        <!-- ================================================================== -->    
        <!-- =====================    CREATE ADMIN         ==================== -->      
        <!-- ================================================================== -->    
        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">
            
            <!-- ========  TITLE  ================ -->
            <h4 class="grey-text text-darken-2">
                <b>Crea </b> un nuevo Administrador
            </h4>

            <!-- ========  TEXT  ================ -->
            <span class="grey-text" style="font-size: 1.25rem;">
                Añade un empleado o administrador al sistema
                <br><br>
            </span>

        </div>


    </div>


    <!-- ================================================================= -->    
    <!-- =======================    CODE FOR THE PAGE   ================== -->    
    <!-- ================================================================= -->
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

    <br><br><br><br>


<?php include("PHP/HTMLFooter.php"); $DataBase->close(); ?>