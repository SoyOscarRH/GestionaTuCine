<?php
    /*=======================================================================================================================
    ============================================         ADMINISTRATOR POWERS          ======================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE FOR THE ADMINISTRADOR TO SEE THINGS */
    include("PHP/ForAllPages.php");                                                             //Dame todas las ventajas

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Administrador';                                                              //Titulo de cada Pagina
    $UpdateDate = '3 de Diciembre del 2017';                                                    //Fecha de actualizacion de pagina

    // ========== SPECIFIC FOR THIS SCRIPT ==========
    $AlertMessages = array();                                                                   //Mensajes que mostramos 
    $InfoEmployees = array();                                                                   //Info de los empleados  

    StandardCheckForStartedSession();                                                           //Asegurate de que pueda estar aqui
    $DataBase = StandardCheckForCorrectDataBase();                                              //Asegurate de que pueda estar aqui
    StandardUpdateSessionData($_SESSION['ID'], $DataBase);                                      //Asegurate que tdo este al dia

    if (StandardCheckForAdminStatus($_SESSION['ID'], $DataBase) == false)                       //Dime en este instante si tiene permisos
        CallErrorPageOnlyForAdmins();                                                           //Si no tienes permiso de estar aqui


    /*===================================================================
    ============         GET THE DATABASE      ==========================
    ===================================================================*/

    //=============  IF YOU WANT TO MODIFY THE DATA  =============
    if (isset($_POST['ChangeEmployeeData'])) {                                                  //Si es que quieres actualizar datos

        do {                                                                                    //while para usar el break XD

            //=============  GET THE DATA =============
            $ToChangeID = ClearSQLInyection($_POST['SelectedEmployee']);                        //Dame la info
            if (!is_numeric($ToChangeID)) {array_push($AlertMessages, "ID Invalido"); break;}   //Envia mensajes

            $Salary = ClearSQLInyection($_POST['Salary']);                                      //Dame la info
            if (!is_numeric($Salary)) {array_push($AlertMessages, "Salario Invalido"); break;}  //Envia mensajes

            $Turn = ClearSQLInyection($_POST['Turn']);                                          //Dame el turno
            if ($Turn != "Matutino" and $Turn != "Vespetirno") {                                //Eres valido
                array_push($AlertMessages, "Turno Invalido"); break;}                           //O no?

            $Rol = ClearSQLInyection($_POST['Rol']);                                            //Dame el turno
            if ($Rol != "Taquilla" and $Rol != "Dulceria")                                      //Eres valido
                {array_push($AlertMessages, "Rol Invalido"); break;}                            //O no?

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
                WHERE ID = {$ToChangeID}");                                                     //AHORA SI PUEDES HACER ESTO

            if (!$ValidQuery){array_push($AlertMessages, "Error al Actualizar Datos"); break;}  //Envia mensaje si todo mal
            else {array_push($AlertMessages, "Datos Actualizados");break;}                      //Envia mensaje si todo bien

        } while (false);                                                                        //Solo eras para el break
    }

    //=============  IF YOU WANT TO ADD THE DATA  =============
    if (isset($_POST['AddEmployee'])) {                                                         //Si es que quieres añadir un empleado

        do {                                                                                    //while para usar el break XD
            //=============  GET THE DATA =============
            $Name = ClearSQLInyection($_POST['Name']);                                          //Dame la info
            if ($Name == "") {array_push($AlertMessages, "Coloca Nombre"); break;}              //Envia mensajes

            $Surname1 = ClearSQLInyection($_POST['Surname1']);                                  //Dame la info
            if ($Surname1 == "") {array_push($AlertMessages, "Coloca Apellidos"); break;}       //Envia mensajes

            $Surname2 = ClearSQLInyection($_POST['Surname2']);                                  //Dame la info
            if ($Surname2 == "") {array_push($AlertMessages, "Coloca Apellidos"); break;}       //Envia mensajes

            $Salary = ClearSQLInyection($_POST['Salary']);                                      //Dame la info
            if (!is_numeric($Salary)) {array_push($AlertMessages, "Salario Invalido"); break;}  //Envia mensajes

            $Sex = ClearSQLInyection($_POST['Sex']);                                            //Dame el turno
            if ($Sex != "Masculino" and $Turn != "Femenino")                                    //Eres valido
                {array_push($AlertMessages, "Sexo Invalido"); break;}                           //O no?

            $Turn = ClearSQLInyection($_POST['Turn']);                                          //Dame el turno
            if ($Turn != "Matutino" and $Turn != "Vespetirno")                                  //Eres valido
                {array_push($AlertMessages, "Turno Invalido"); break;}                          //O no?

            $Rol = ClearSQLInyection($_POST['Rol']);                                            //Dame el turno
            if ($Rol != "Taquilla" and $Rol != "Dulceria")                                      //Eres valido
                {array_push($AlertMessages, "Rol Invalido"); break;}                            //O no?


            $Email = ClearSQLInyection($_POST['Email']);                                         //Dame email
            if ($Email == "") {array_push($AlertMessages, "Email vacío"); break;}                //Envia mensajes

            $Password = ClearSQLInyection($_POST['Password']);                                  //Dame el turno
            if ($Password == "") {array_push($AlertMessages, "Contraseña vacío"); break;}       //Envia password
            
            $Password = sha1($Password."ManageYourCinemaSalt");                                 //Esta es la de verdad


            //=============  VERIFY THE ID =============
            $DataBase->query(" 
                INSERT INTO Empleado (
                    Sueldo,
                    Turno,
                    Genero,
                    Nombre,
                    ApellidoPaterno,
                    ApellidoMaterno,
                    Correo,
                    Contrasena,
                    RolActual,
                    IDGerente
                )
                VALUES (
                    {$Salary},
                    '{$Turn}',
                    '{$Sex}',
                    '{$Name}',
                    '{$Surname1}',
                    '{$Surname2}',
                    '{$Email}',
                    '{$Password}',
                    '{$Rol}',
                    {$_SESSION['ID']}
            )");

            if ($DataBase->affected_rows != 0) array_push($AlertMessages, "Empleado Añadido");  //Añadir Empleados
            else array_push($AlertMessages, "Lo siento, Error al Actualizar");                  //Envia mensaje si todo bien

        } while (false);                                                                        //Solo eras para el break
    }

    //=============  IF YOU WANT TO DELETE THE DATA  ==========
    if (isset($_POST['DeleteEmployee'])) {                                                      //Si es que quieres actualizar datos

        do {                                                                                    //while para usar el break XD

            //=============  GET THE DATA =============
            $ToChangeID = ClearSQLInyection($_POST['SelectedEmployee']);                        //Dame la info
            if (!is_numeric($ToChangeID)) {array_push($AlertMessages, "ID Invalido"); break;}   //Envia mensajes

            //=============  VERIFY THE ID =============
            $QueryID = $DataBase->query(" SELECT ID FROM Empleado WHERE 
                                            IDGerente = {$_SESSION['ID']} AND
                                            ID = {$ToChangeID}");                               //Haz la consulta
            if ($QueryID->num_rows == 0)                                                        //Si es que no hay tuplas
                array_push($AlertMessages, "No es un empleado a tu cargo");                     //Envia mensajes

            //=============  CHANGE THE DATA =============
            $ValidQuery = $DataBase->query("DELETE FROM Empleado WHERE ID = {$ToChangeID}");    //AHORA SI PUEDES HACER ESTO

            if (!$ValidQuery){array_push($AlertMessages, "Error al Eliminar Datos"); break;}    //Envia mensaje si todo mal
            else {array_push($AlertMessages, "Empleado Eliminado");break;}                      //Envia mensaje si todo bien

        } while (false);                                                                        //Solo eras para el break
    }



    //=============  IF YOU WANT TO ADD ADMIN  ===============
    if (isset($_POST['AddAdmin'])) {                                                            //Si es que quieres actualizar datos
        do {                                                                                    //while para usar el break XD

            //=============  GET THE DATA =============
            $NewAdminID = ClearSQLInyection($_POST['NewAdminID']);                              //Dame la info
            if (!is_numeric($NewAdminID)) {array_push($AlertMessages, "ID Invalido"); break;}   //Envia mensajes

            //=============  VERIFY THE ID =============
            $NewEmployeesID = "";                                                               //Crea un String
            foreach ($_POST['NewEmployees'] as $Key => $Value) $NewEmployeesID .= "{$Value} ,"; //Con los numeros
            $NewEmployeesID = substr($NewEmployeesID, 0, -2);                                   //Y elimina la ultima coma

            $QueryID = $DataBase->query("SELECT ID FROM Empleado WHERE 
                                            IDGerente = {$_SESSION['ID']} AND
                                            ID IN ({$NewEmployeesID})");                        //Haz la consulta

            if ($QueryID->num_rows != sizeof($_POST['NewEmployees'])) {                          //Si es que hay problemas
                array_push($AlertMessages, "Seleccionaste empleados que no estan a tu cargo");   //Envia mensajes
                break;
            } 

            //=============  CHANGE THE DATA =============
            $NewEmployeesID .= ", {$NewAdminID}";                                               //Tu eres tu nuevo jefe
            $ValidQuery = $DataBase->query("
                UPDATE Empleado
                    SET IDGerente = {$NewAdminID} 
                    WHERE ID IN ({$NewEmployeesID})");                                          //AHORA SI PUEDES HACER ESTO

            if (!$ValidQuery){array_push($AlertMessages, "Error al poner nuevo jefe"); break;}  //Envia mensaje si todo mal
            else {array_push($AlertMessages, "Nuevo jefe seleccionado");break;}                 //Envia mensaje si todo bien
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
    $StandardGreyCard = "card grey lighten-4 col s12 m8 l8 offset-m2 offset-l2";                //Es una forma de que sea mas sencilla 
    $StandardButton = "col s10 m6 l6 offset-s1 offset-m3 offset-l3 btn btn-large waves-effect"; //Es una forma de que sea mas sencilla 

    include("PHP/HTMLHeader.php");                                                              //Incluimos un Asombroso Encabezado
?>
    <br><br>
    <div class="container center-align">
        

        <!-- ================================================================== -->    
        <!-- =====================    SHOW EMPLOYEES      ===================== -->      
        <!-- ================================================================== -->    
        <div class="<?php echo $StandardGreyCard;?>">

            <!-- =================   CARD CONTENT  ================ -->      
            <div id="CardSeeEmployeeInfo" name="CardSeeEmployeeInfo" class="card-content">

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
                    id="EmployeesTable" 
                    style="<?php if (!isset($_POST['ChangeEmployeeData'])) echo "display: none; "?>font-size: 0.9rem"
                    class="centered hoverable striped responsive-table bordered">

                    <!-- ========  TITLES ================ -->
                    <thead>
                        <tr>
                        <?php foreach ($QueryInfoEmployees->fetch_fields() as $Column): 
                            if (in_array($Column->name, array('ID', 'IDGerente', 'Contrasena'))) continue; ?>
                            <th><?php echo $Column->name; ?></th>
                        <?php endforeach; ?>
                        </tr>
                    </thead>

                    <!-- ========  CELLS ================ -->
                    <tbody>
                    <?php while ($Row = $QueryInfoEmployees->fetch_assoc()) : 
                        array_push($InfoEmployees, $Row);?>
                        <tr>
                        
                        <?php foreach ($Row as $Name => $Value): 
                            if (in_array($Name, array('ID', 'IDGerente', 'Contrasena'))) continue; ?>
                            <td><?php echo $Value; ?></td>
                        <?php endforeach; ?>

                        </tr>

                        <?php endwhile;?>

                    </tbody>
                </table>

                <br>

                <!-- ========  BUTTON ============= -->
                <button 
                    id="EmployeesTablesButton"
                    class='col s10 m6 l6 offset-s1 offset-m3 offset-l3 btn-large waves-effect teal lighten-1'
                    name="ShowEmployees">
                    Ve los Empleados
                </button>

            </div>

            <!-- ========  CARD SCRIPT  ===== -->
            <script>
                $("#EmployeesTablesButton").click( function() {
                    $("#EmployeesTable").toggle();

                    if ($.trim($(this).text()) === 'Ve los Empleados')
                        $(this).text('Oculta los Empleados');
                    else $(this).text('Ve los Empleados');        
                });

                $("#EmployeesTablesButtonCard").click(function() {
                    $("#EmployeesTablesButton").click(); 
                    return false;
                });
            </script>

        </div>


        <br><br>

        <!-- ================================================================== -->    
        <!-- =====================    ALTER EMPLOYEES     ===================== -->      
        <!-- ================================================================== -->    
        <div class="<?php echo $StandardGreyCard;?>">

            <!-- =================   CARD CONTENT  ================ -->      
            <div id="CardChangeEmployeeInfo" name="CardChangeEmployeeInfo" class="card-content">
            
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
                        <select required name="SelectedEmployee" id="SelectedEmployee" class="left-align">
                            <?php foreach ($InfoEmployees as $Row): 
                                $TemporalCompleteName = $Row['ApellidoPaterno']." ".$Row['ApellidoMaterno']." ".$Row['Nombre'];?>
                            <option value="<?php echo $Row["ID"];?>"><?php echo $TemporalCompleteName;?></option>
                            <?php endforeach; ?>
                        </select>
                        <label>Nombre del Empleado</label>
                    </div>

                    <!-- ========  WORK TIME ========== -->
                    <div class="input-field">                        
                        <select required name="Rol" id="Rol">
                            <option value="Dulceria">Dulceria</option>
                            <option value="Taquilla">Taquilla</option>
                        </select>
                        <label>Rol Actual</label>
                    </div>

                    <!-- ========  WORK TIME ========== -->
                    <div class="input-field">                        
                        <select required requiredname="Turn" id="Turn">
                            <option value="Matutino">Matutino</option>
                            <option value="Vespetirno">Vespetirno</option>
                        </select>
                        <label>Turno</label>
                    </div>

                    <!-- ========  SALARY ============= -->
                    <div class='input-field'>
                        <input required class='validate' type='number' name='Salary' id='Salary' />
                        <label>Sueldo</label>
                    </div>

                    <!-- ========  BUTTON TO SEND ===== -->
                    <button 
                        type='submit'
                        name='ChangeEmployeeData'
                        class='<?php echo $StandardButton;?> indigo lighten-1'>
                        Cambiar Valores
                    </button>

                </form>

            </div>

            <!-- ========  CARD SCRIPT  ===== -->
            <script>
                $("#SelectedEmployee").change(function() {
                    let SelectedID = $('#SelectedEmployee').val();

                    <?php foreach ($InfoEmployees as $Row): ?>
                    
                        if (SelectedID == <?php echo $Row['ID'];?> ) {
                            $('#Rol').val("<?php echo $Row['RolActual'] ?>");       
                            $('#Salary').val("<?php echo $Row['Sueldo'] ?>");
                            $('#Turn').val("<?php echo $Row['Turno'] ?>");
                        }

                    <?php endforeach;?>

                    $('select').material_select();
                    Materialize.updateTextFields();
                });

                $('#SelectedEmployee').trigger('change');
            </script>

        </div>


        <br><br>


        <!-- ================================================================== -->    
        <!-- =====================      ADD EMPLOYEES     ===================== -->      
        <!-- ================================================================== -->    
        <div class="<?php echo $StandardGreyCard;?>">

            <!-- =================   CARD CONTENT  ================ -->      
            <div id="CardAddEmployee" name="CardAddEmployee" class="card-content">
                
                <!-- ========  TITLE  ================ -->
                <h4 class="grey-text text-darken-2">
                    <b>Añade</b> un Empleado
                </h4>

                <!-- ========  TEXT  ================ -->
                <span class="grey-text" style="font-size: 1.25rem;">
                    Añade un empleado al sistema
                    <br><br>
                </span>

                <!-- ========  MATERIAL FORM  ================ -->
                <form class="container col s12" action="AdminAccounts.php" method="post">

                    <!-- ========  EMPLOYEERS ID ============= -->
                    <div class='input-field'>
                        <input required class='validate' type='text' name='Name' id='Name' />
                        <label>Nombre del Empleado</label>
                    </div>

                    <div id="DivEmployeeSurnames" class="row">
                        
                        <!-- ========  EMPLOYEERS ID ============= -->
                        <div class='input-field col s6'>
                            <input required class='validate' type='text' name='Surname1' id='Surname1' />
                            <label>Apellido Paterno</label>
                        </div>

                        <!-- ========  EMPLOYEERS ID ============= -->
                        <div class='input-field col s6'>
                            <input class='validate' type='text' name='Surname2' id='Surname2' />
                            <label>Apellido Materno</label>
                        </div>

                    </div>

                    <!-- ========  WORK TIME ========== -->
                    <div class="input-field">                        
                        <select required name="Sex" id="Sex">
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                        <label>Género</label>
                    </div>

                    <!-- ========  WORK ROL ========== -->
                    <div class="input-field">                        
                        <select required name="Rol" id="Rol">
                            <option value="Dulceria">Dulceria</option>
                            <option value="Taquilla">Taquilla</option>
                        </select>
                        <label>Rol Actual</label>
                    </div>

                    <!-- ========  WORK TURN ========== -->
                    <div class="input-field">                        
                        <select required name="Turn" id="Turn">
                            <option value="Matutino">Matutino</option>
                            <option value="Vespetirno">Vespetirno</option>
                        </select>
                        <label>Turno</label>
                    </div>

                    <!-- ========  SALARY ============= -->
                    <div class='input-field'>
                        <input required class='validate' type='number' name='Salary' id='Salary' />
                        <label>Sueldo</label>
                    </div>


                    <!-- ========  EMPLOYEERS ID ============= -->
                    <div class='input-field'>
                        <input required class='validate' type='email' name='Email' id='Email' />
                        <label>Correo del Empleado</label>
                    </div>

                    <!-- ========  EMPLOYEERS ID ============= -->
                    <div class='input-field'>
                        <input required class='validate' type='password' name='Password' id='Password' />
                        <label>Contraseña</label>
                    </div>

                    <br>

                    <!-- ========  BUTTON TO SEND ===== -->
                    <button 
                        type='submit'
                        name='AddEmployee'
                        class='<?php echo $StandardButton;?> green lighten-1'>
                        Añadir Empleado
                    </button>

                </form>

            </div>

        </div>

        <br><br>
        

        <!-- ================================================================== -->    
        <!-- =====================    DELETE EMPLOYEES     ==================== -->      
        <!-- ================================================================== -->    
        <div class="<?php echo $StandardGreyCard;?>">

            <!-- =================   CARD CONTENT  ================ -->      
            <div id="CardDeleteEmployee" name="CardDeleteEmployee" class="card-content">

                <!-- ========  TITLE  ================ -->
                <h4 class="grey-text text-darken-2">
                    <b>Elimina</b> a un Empleado
                </h4>

                <!-- ========  TEXT  ================ -->
                <span class="grey-text" style="font-size: 1.25rem;">
                    Elimina a algún empleado del sistema
                    <br><br>
                </span>

                <!-- ========  MATERIAL FORM  ================ -->
                <form class="container" action="AdminAccounts.php" method="post">

                    <!-- ========  EMPLOYEERS ID ============= -->
                    <div class="input-field">                        
                        <select required name="SelectedEmployee" id="SelectedEmployee" class="left-align">
                            <?php foreach ($InfoEmployees as $Row): 
                                $TemporalCompleteName = $Row['ApellidoPaterno']." ".$Row['ApellidoMaterno']." ".$Row['Nombre'];?>
                            <option value="<?php echo $Row["ID"];?>"><?php echo $TemporalCompleteName;?></option>
                            <?php endforeach; ?>
                        </select>
                        <label>Nombre del Empleado</label>
                    </div>

                    <!-- ========  BUTTON TO SEND ===== -->
                    <button 
                        type='submit'
                        name='DeleteEmployee'
                        class='<?php echo $StandardButton;?> red lighten-2'>
                        Eliminar Empleado
                    </button>

                </form>

            </div>

        </div>


        <br><br><br>


        <!-- ================================================================== -->    
        <!-- =====================    CREATE ADMIN         ==================== -->      
        <!-- ================================================================== -->    
        <div class="<?php echo $StandardGreyCard;?>">

            <!-- =================   CARD CONTENT  ================ -->      
            <div id="CardAddAdmin" name="CardAddAdmin" class="card-content">

                <!-- ========  TITLE  ================ -->
                <h4 class="grey-text text-darken-2">
                    <b>Crea </b> un nuevo Administrador
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
                        <select required name="NewAdminID" id="NewAdminID" class="left-align">
                            <?php foreach ($InfoEmployees as $Row): 
                                $TemporalCompleteName = $Row['ApellidoPaterno']." ".$Row['ApellidoMaterno']." ".$Row['Nombre'];?>
                            <option value="<?php echo $Row["ID"];?>"><?php echo $TemporalCompleteName;?></option>
                            <?php endforeach; ?>
                        </select>
                        <label>Nombre del Nuevo Administrador</label>
                    </div>

                    <!-- ========  EMPLOYEERS ID ============= -->
                    <div class="input-field">                        
                        <select required name="NewEmployees[]" id="NewEmployees" class="left-align" multiple>
                            <?php foreach ($InfoEmployees as $Row): 
                                $TemporalCompleteName = $Row['ApellidoPaterno']." ".$Row['ApellidoMaterno']." ".$Row['Nombre'];?>
                            <option value="<?php echo $Row["ID"];?>"><?php echo $TemporalCompleteName;?></option>
                            <?php endforeach; ?>
                        </select>
                        <label>Nuevos Empleados a su Cargo</label>
                    </div>

                    <!-- ========  BUTTON TO SEND ===== -->
                    <button 
                        type='submit'
                        name='AddAdmin'
                        class='<?php echo $StandardButton;?> blue lighten-1'>
                        Crear Administrador
                    </button>

                </form>

                <!-- ========  CODE OF THE SELECT ===== -->
                <script>
                    $(document).ready(function() {

                        let DeletedID = $('#NewAdminID').val();
                        let DeletedKey = $('#NewAdminID :selected').text();

                        $("#NewEmployees option[value='"+ $('#NewAdminID').val() +"']").remove();

                        $('select').material_select();

                         $("#NewAdminID").change(function() {

                            $("#NewEmployees").append('<option value="'+DeletedID+'">'+DeletedKey+'</option>');

                            DeletedID = $('#NewAdminID').val();
                            DeletedKey = $('#NewAdminID :selected').text();

                            $("#NewEmployees option[value='"+ $('#NewAdminID').val() +"']").remove();

                            $('select').material_select();
                            Materialize.updateTextFields();
                        });

                    });
                </script>

            </div>

        </div>

    </div>


    <!-- ================================================================= -->    
    <!-- =======================    CODE FOR THE PAGE   ================== -->    
    <!-- ================================================================= -->
    <script>
        $(document).ready(function() {

            // Start a Select
            $('select').material_select();
            Materialize.updateTextFields();

            // Create all the Toast
            <?php 
                $TitleAlert = '<span class = "yellow-text"><b>Alerta: &nbsp; </b></span>';
                foreach ($AlertMessages as $Alert) echo "Materialize.toast('$TitleAlert $Alert', 9000);"; 
            ?>

        });
    </script>

    <br><br><br><br>


    <!-- ================================================================= -->    
    <!-- ===============         FAB FOR THE PAGE       ================== -->    
    <!-- ================================================================= -->
    <div class="fixed-action-btn <?php if (WeAreAtMobile()) echo "click-to-toggle";?>">
        
        <a class="btn-floating btn-large cyan darken-3">
            <i class="unselectable large material-icons">view_list</i>
        </a>
        
        <ul>

            <!-- =======  TO SEE EMPLOYEES INFO  ========== -->    
            <li>
                <a href    = "#CardSeeEmployeeInfo"
                   onclick = "$('.fixed-action-btn').closeFAB();" 
                   class   = "btn-floating teal">
                    <i class="unselectable material-icons">info_outline</i>
                </a>
            </li>

            <!-- =======  TO EDIT EMPLOYEES INFO  ========== -->    
            <li>
                <a href    = "#CardChangeEmployeeInfo"
                   onclick = "$('.fixed-action-btn').closeFAB();" 
                   class   = "btn-floating indigo">
                    <i class="unselectable material-icons">edit</i>
                </a>
            </li>

            <!-- =======  TO ADD EMPLOYEES INFO  ========== -->    
            <li>
                <a href    = "#CardAddEmployee"
                   onclick = "$('.fixed-action-btn').closeFAB();" 
                   class   = "btn-floating green">
                    <i class="unselectable material-icons">add</i>
                </a>
            </li>

            <!-- =======  TO DETELE EMPLOYEES INFO  ========== -->    
            <li>
                <a href    = "#CardDeleteEmployee"
                   onclick = "$('.fixed-action-btn').closeFAB();" 
                   class   = "btn-floating red">
                    <i class="unselectable material-icons">delete</i>
                </a>
            </li>

            <!-- =======  TO ADD ADMIN INFO  ========== -->    
            <li>
                <a href    = "#CardAddAdmin"
                   onclick = "$('.fixed-action-btn').closeFAB();" 
                   class   = "btn-floating blue">
                    <i class="unselectable material-icons">people</i>
                </a>
            </li>

        </ul>
    </div>


<?php 
    /*===================================================================
    ============         CLOSE ALL DATABASE     =========================
    ===================================================================*/
    include("PHP/HTMLFooter.php");

    if (isset($DataBase)) $DataBase->close();
?>





