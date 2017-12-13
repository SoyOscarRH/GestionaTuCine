<?php
    /*=======================================================================================================================
    ============================================         ADMINISTRATE MOVIES          ======================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE FOR THE ADMINISTRADOR TO SEE THINGS */
    include("PHP/ForAllPages.php");                                                             //Dame todas las ventajas

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Venta Boletos';                                                              //Titulo de cada Pagina
    $UpdateDate = '10 de Diciembre del 2017';                                                   //Fecha de actualizacion de pagina

    // ========== SPECIFIC FOR THIS SCRIPT ==========
    $AlertMessages = array();                                                                   //Mensajes que mostramos 
    $FunctionInfo = array();                                                                    //Mensajes que mostramos 

    StandardCheckForStartedSession();                                                           //Asegurate de que pueda estar aqui
    $DataBase = StandardCheckForCorrectDataBase();                                              //Asegurate de que pueda estar aqui
    StandardUpdateSessionData($_SESSION['ID'], $DataBase);                                      //Asegurate que tdo este al dia

    //=============  ADVANCE CHECK FOR VISITING THIS PAGE  =============
    if ($_SESSION['RolActual'] != 'Taquilla' AND !$_SESSION['IAmAManager'])                     //Solos los empleados entran
        CallErrorPagePermissions("No estas designado para Taquilla");                           //Sino error :o

    // ======================================================================
    // ======================   FINISH A SELL  ==============================
    // ======================================================================

    //=============  END HAPPY SELL  =============
    if (isset($_POST['CancelSellButton']) AND ($_SESSION['TotalSellMovieTickets'] != 0.0)) {    //Si es que vamos a hacer un feliz Salida

        $TemporalQueryResult = $DataBase->query("
                DELETE FROM TicketDulceria 
                        WHERE IDventa = {$_SESSION['CurrentSaleIDMovies']}");                   //Liberamos lo que necesito

        if (!$TemporalQueryResult) array_push($AlertMessages, "Error al Cancelar Venta");       //Mensajito
        else array_push($AlertMessages, "Venta Cancelada :D");                                  //Mensajito

        $_SESSION['TotalSellMovieTickets'] = 0.0;                                               //Volvemos todo a la normalidad
    }


    //=============  END HAPPY SELL  =============
    if (isset($_POST['FinalizeSuccessfulSell'])) {                                              //Si es que vamos a hacer un feliz Salida

        if ($_SESSION['TotalSellMovieTickets'] != 0.0) {
            $TemporalQueryResult = $DataBase->query("
                    UPDATE Venta
                        SET Total = {$_SESSION['TotalSellMovieTickets']} 
                        WHERE ID = {$_SESSION['CurrentSaleIDMovies']}");                        //Liberamos lo que necesito

            if (!$TemporalQueryResult) array_push($AlertMessages, "Error al Finalizar Venta");  //Mensajito

            unset($_SESSION['CurrentSaleIDMovies']);                                            //Adios Sesion    
        }

        array_push($AlertMessages, "Venta Guardada Correcta");                                  //Mensajito
    }


    // ======================================================================
    // ======================   START A SELL   ==============================
    // ======================================================================
    if (empty($_SESSION['CurrentSaleIDMovies'])) {                                              //Si es que no tienes ID de Venta
        do {                                                                                    //Solo para los breaks :v
            $_SESSION['TotalSellMovieTickets'] = 0.0;                                           //Lo que se va a cobrar a un cliente

            $TemporalQueryResult = $DataBase->query('LOCK TABLES Venta WRITE');                 //NO ME TOQUES
            if (!$TemporalQueryResult)                                                          //Si es que hay algo mal
                {array_push($AlertMessages, "Error al Crear Nueva Venta");break;}               //Vemos que todo ok

            $TemporalQueryResult = $DataBase->query('SELECT MAX(ID) FROM Venta');               //Dame el ID Maximo de Venta
            if (!$TemporalQueryResult)                                                          //Si es que hay algo mal
                {array_push($AlertMessages, "Error al Crear Nueva Venta");break;}               //Vemos que todo ok
            
            $OldUltimateSellID = $TemporalQueryResult->fetch_row();                             //Ahora dame todo lo que salio
            $_SESSION['CurrentSaleIDMovies'] = $OldUltimateSellID[0] + 1;                       //Ahora guardalo y aumenta uno

            $TemporalQueryResult = $DataBase->query("
                                INSERT INTO Venta (ID, IDEmpleado, Fecha)
                                    VALUES (
                                        {$_SESSION['CurrentSaleIDMovies']}, 
                                        {$_SESSION['ID']},
                                        NOW()
                                    )");                                                        //Ahora crea uno más
            
            if (!$TemporalQueryResult)                                                          //Si es que hay algo mal
                {array_push($AlertMessages, "Error al Crear Nueva Venta");break;}               //Vemos que todo ok

            $TemporalQueryResult = $DataBase->query('UNLOCK TABLES');                           //NO ME TOQUES
            if (!$TemporalQueryResult)                                                          //Si es que hay algo mal
                {array_push($AlertMessages, "Error al Crear Nueva Venta");break;}               //Vemos que todo ok
        }
        while (false);                                                                          //Solo te quiero por el break :v
    }


    /*======================================================================================
    =========================         GET THE DATABASE      ================================
    ======================================================================================*/

    // ======================================================================
    // =================  IF YOU WANT TO SEARCH THE DATA  ===================
    // ======================================================================
    if (isset($_POST['SearchForMovie'])) {                                                      //Si es que quieres buscar pelicula
        
        do {                                                                                    //Solo por le break
            // =================  GET THE INFO  ===================
            if (isset($_POST['PossibleMovieName'])) {
                $PossibleMovieName = ClearSQLInyection($_POST['PossibleMovieName']);            //Dame la info
                $PossibleMovieName = str_replace(' ', '%', $PossibleMovieName);                 //Esto ayuda con el reconocimiento   
            }
            else $PossibleMovieName = "";                                                       //Sino vacio

            if (isset($_POST['PossibleGenres'])) {                                              //SI EXISTE ESTO:
                $PossibleGenres = $_POST['PossibleGenres'];                                     //Lo tenemos
                foreach ($PossibleGenres as $Genre) {$Genre = ClearSQLInyection($Genre);}       //Lo limpiamos

                if (in_array("All", $PossibleGenres)) $AllPossibleGenresText = "";              //Si es que todo
                else {
                    $AllPossibleGenresText = "AND Genero IN (";                                 //Sino entonces empezamos a armarlo
                    foreach ($PossibleGenres as $Genre)                                         //Lo agrego
                        $AllPossibleGenresText .= "'{$Genre}', ";                               //Por cada elemento
                    $AllPossibleGenresText = substr($AllPossibleGenresText, 0, -2);             //Quito la ultima coma
                    $AllPossibleGenresText .= ")";                                              //Pongo parentesis
                }
            }
            else $AllPossibleGenresText = "";                                                   //Sino es vacio :v

            if (isset($_POST['PossibleClassifications'])) {                                     //SI EXISTE ESTO:
                $PossibleClassifications = $_POST['PossibleClassifications'];                   //Lo tenemos
                foreach ($PossibleClassifications as $Classification)                           //Lo limpiamos
                    {$Classification = ClearSQLInyection($Classification);}

                if (in_array("All", $PossibleClassifications)) $AllClassificationsText = "";    //Si es que era todo
                else {
                    $AllClassificationsText = "AND Clasificacion IN (";                         //Sino entonces empezamos a armarlo
                    foreach ($PossibleClassifications as $Classification)                       //Lo agrego            
                        $AllClassificationsText .= "'{$Classification}', ";                     //Por cada elemento            
                    $AllClassificationsText = substr($AllClassificationsText, 0, -2);           //Quito la ultima coma
                    $AllClassificationsText .= ")";                                             //Pongo parentesis
                }
            }
            else $AllClassificationsText = "";                                                  //Sino es vacio :v
           
            // =================  TALK TO THE BASE  ===================
            $QueryPossibleMovies = $DataBase->query("
                        SELECT * FROM Pelicula
                            WHERE
                                Nombre LIKE '%{$PossibleMovieName}%' AND
                                Exhibicion = 'En Exhibición'
                                {$AllPossibleGenresText}
                                {$AllClassificationsText}");                                    //Ahora hacemos el query

            
        }
        while (false);                                                                          //Solo queria el break :v

    }

    // ======================================================================
    //====================      SEE FUNCTIONS    ============================
    // ======================================================================
        if (empty($PossibleMovieName)) $PossibleMovieName = "";                                 //Corregimos   
        if (empty($AllPossibleGenresText)) $AllPossibleGenresText = "";                         //Corregimos           
        if (empty($AllClassificationsText)) $AllClassificationsText = "";                       //Corregimos           

        $QueryFunctionInfo = $DataBase->query("
            SELECT 
                Funcion.ID, Funcion.Hora, Funcion.NumeroSala, Funcion.Precio, Funcion.TipoFuncion,
                Pelicula.Nombre, Pelicula.Clasificacion, Pelicula.Exhibicion, Pelicula.Genero,  
                Sala.Tipo, Sala.NumeroAsientos              
                    FROM Funcion, Pelicula, Sala
                        WHERE 
                            TipoFuncion = 'Funcion Activa'   AND
                            Pelicula.ID = Funcion.IDPelicula AND
                            Sala.NumeroSala = Funcion.NumeroSala AND
                            Pelicula.Nombre LIKE '%{$PossibleMovieName}%' AND
                            Hora >= NOW()
                            {$AllPossibleGenresText}
                            {$AllClassificationsText}
                        ORDER BY Hora");                                                        //Haz la consulta

        if (!$QueryFunctionInfo)                                                                //Si es que no hay tuplas
            array_push($AlertMessages, "No se puede acceder a la Base de Datos");               //Envia mensajes
        else if ($QueryFunctionInfo->num_rows == 0)                                             //Si es que no hay tuplas
            array_push($AlertMessages, "No Tenemos esas funciones");                            //Envia mensajes



 


    
    // ======================================================================
    // ==========   DELETE, ALTER, ADD ITEM SHOPPING CAR      ===============
    // ======================================================================
    // ======================================================================
    
    // ======================================================================
    //==============      SEE SELLED TICKETS     ============================
    // ======================================================================
        $QueryTemporal = $DataBase->query("SELECT ID FROM Funcion GROUP BY ID");
        if (!$QueryTemporal)                                                                    //Si es que no hay tuplas
            array_push($AlertMessages, "No se puede acceder a la Base de Datos");               //Envia mensajes

        $PossibleFunctions = array();                                                           //Posibles Funciones
        while ($Row = $QueryTemporal->fetch_row()) array_push($PossibleFunctions, $Row[0]);     //Damelas

        $UsedSpots = array();                                                                   //Dime cuantos lugares

        foreach ($PossibleFunctions as $ID) {                                                   //Para cada ID
            $QueryTemporal = $DataBase->query("
                SELECT IDFuncion, SUM(Cantidad) FROM TicketBoleto WHERE IDFuncion = {$ID}");    //Haz la consulta

            if (!$QueryTemporal) $UsedSpots[$ID] = 0;                                           //Si no habia nadie
            else {$Row = $QueryTemporal->fetch_row(); $UsedSpots[$ID] = $Row[1];}               //Sino se lo pones       
        }

    foreach ($_POST as $Name => $Value) {                                                       //Buscar el POST[] correcto
        if(fnmatch("*ButtonChangeShoppingCar",$Name) OR fnmatch("*ButtonDeleteProduct",$Name)){ //Se tiene que parecer a esto
            do {                                                                                //Bien, vamos a intentar que todo ok
                
                //=============  GET THE DATA  =============
                if (fnmatch("*ButtonChangeShoppingCar", $Name))                                 //Si es que eras de aqui
                    $ProductID = str_replace("ButtonChangeShoppingCar", "", $Name);             //Puede que seas tu
                else $ProductID = str_replace("ButtonDeleteProduct", "", $Name);                //Puede que seas tu

                $QuantityTicket = ClearSQLInyection($_POST[$ProductID.'QuantityTickets']);      //Por si las dudas

                if (isset($_POST[$ProductID.'OriginalQuantity']))                               //Dame la anterior
                    $OriginalQuantity=ClearSQLInyection($_POST[$ProductID.'OriginalQuantity']); //Por si las dudas
                else $OriginalQuantity = 0;                                                     //Sino era cero :v

                if (!$QuantityTicket OR !$ProductID)                                            //Si estas estupido
                    {array_push($AlertMessages, "No colocaste datos"); break;}                  //Error Misterioso 

                //=============  FIND PRODUCT DETAILS ======
                $TemporalQueryResult = $DataBase->query("
                        SELECT Precio FROM Funcion 
                            WHERE ID = {$ProductID}");                                          //Obtenemos el costo del producto

                if (!$TemporalQueryResult)                                                      //Si es que hubo un problema
                    {array_push($AlertMessages, "Error con la Base de Datos"); break;}          //Error Misterioso

                $TemporalQueryData = $TemporalQueryResult->fetch_row();                         //Dame todos producto que hagan eso
                $ProductUnitCost = $TemporalQueryData[0];                                       //Dame este valor
                $ProductTotalCost = $ProductUnitCost * $QuantityTicket;                        //Seguro que solo hay uno
                $TemporalQueryResult->close();                                                  //Adios al Query


                //============================================
                //=============  WE WANNA DELETE ITEM ? ======
                //============================================
                if (isset($_POST[$ProductID.'ButtonDeleteProduct'])){                           // === SI ES QUE QUIERES ELIMINAR ===    

                    $QuantityTicket = ClearSQLInyection($_POST[$ProductID.'OriginalQuantity']);//Este es el correcto
                    $_SESSION['TotalSellMovieTickets'] -= ($ProductUnitCost * $QuantityTicket);//Añado al total el nuevo

                    do {
                        //=============  DELETE PRODUCT DETAILS ======
                        $TemporalQueryResult = $DataBase->query("
                                DELETE FROM TicketBoleto 
                                    WHERE 
                                        IDFuncion = {$ProductID} AND
                                        IDVenta = {$_SESSION['CurrentSaleIDMovies']}");         //Obtenemos el costo del producto

                        if (!$TemporalQueryResult)                                              //Si es que hubo un problema
                            {array_push($AlertMessages, "Error al Eliminar Elemento"); break;}  //Error Misterioso

                        //=============  ALTER STOCK ======
                        $UsedSpots[$ProductID] -= $QuantityTicket;
                        array_push($AlertMessages, "Producto Eliminado");                       //Adios Producto
                    }
                    while (false);                                                              //Solo lo queria por el break
                }
                else {                                                                          // === SI QUIERES AÑADIR / ALTERAR ===
                    //=============  THIS PRODUCT WAS ALREADY IN THE LIST ? ======
                    $TemporalQueryResult = $DataBase->query("
                            SELECT Costo FROM TicketBoleto 
                                WHERE 
                                    IDFuncion = {$ProductID} AND 
                                    IDVenta = {$_SESSION['CurrentSaleIDMovies']}");             //Obtenemos el precio anterior

                    if (!$TemporalQueryResult)                                                  //Si es que hubo un problema
                    {array_push($AlertMessages, "Error con la Base de Datos"); break;}          //Error Misterioso


                    //=============  TRANSACCION ======
                    $MiniTemporalQueryResult = $DataBase->query("START TRANSACTION");           //Todo o nada
                    if (!$MiniTemporalQueryResult)                                              //Si es que hubo un problema
                        {array_push($AlertMessages, "Error con la Base de Datos"); break;}      //Error Misterioso

                    //============================================
                    //=========  ADD NEW ITEM TO SHOPPING CAR  ===
                    //============================================
                    if ($TemporalQueryResult->num_rows == 0) {                                  //Producto Nuevo :o
                        
                        $TemporalQueryResult = $DataBase->query("
                            INSERT INTO TicketBoleto
                                VALUES (
                                    {$ProductTotalCost},
                                    {$QuantityTicket},
                                    {$_SESSION['CurrentSaleIDMovies']},
                                    {$ProductID},
                                    NOW()
                                )");                                                            //Creo nuevo registro del producto

                        if (!$TemporalQueryResult)                                              //Si es que hubo un problema
                            {array_push($AlertMessages, "Error con la Base de Datos"); break;}  //Error Misterioso
                        
                        $_SESSION['TotalSellMovieTickets'] += $ProductTotalCost;                //Añado al total
                        array_push($AlertMessages, "Boletos Añadido al Carrito");               //Muestro lindo Mensajito
                    }
                    //============================================
                    //=========  EXISTING ITEM TO SHOPPING CAR  ==
                    //============================================
                    else {                                                                      //Si es que ya tenia este producto
                        $TemporalQueryData = $TemporalQueryResult->fetch_row();                 //Dame el costo anterior
                        $_SESSION['TotalSellMovieTickets'] -= $TemporalQueryData[0];            //Lo quito

                        if (!isset($_POST[$ProductID.'OriginalQuantity'])) {                    //Si es que estamos en el menu buscar
                            $TemporalQueryResult = $DataBase->query("
                                SELECT 
                                    Cantidad
                                    FROM TicketBoleto
                                    WHERE 
                                        TicketBoleto.IDFuncion = {$ProductID} AND
                                        IDventa = {$_SESSION['CurrentSaleIDMovies']}");         //Busco productos

                            if (!$TemporalQueryResult)                                          //Si es que hubo un problema
                                array_push($AlertMessages, "Error con los Productos Vendidos");

                            $TemporalQueryData = $TemporalQueryResult->fetch_row();             //Dame el costo anterior
                            $OriginalQuantity = $TemporalQueryData[0];                          //Lo quito
                        }

                        $TemporalQueryResult = $DataBase->query("
                            UPDATE TicketBoleto
                                SET 
                                    Costo = {$ProductTotalCost},
                                    Cantidad = {$QuantityTicket}
                                WHERE 
                                    IDFuncion = {$ProductID} AND 
                                    IDVenta = {$_SESSION['CurrentSaleIDMovies']}");             //Actualizo datos

                        if (!$TemporalQueryResult)                                              //Si es que hubo un problema
                            {array_push($AlertMessages, "Error con la Base de Datos"); break;}  //Error Misterioso
                        
                        $_SESSION['TotalSellMovieTickets'] += $ProductTotalCost;                //Añado al total el nuevo
                        array_push($AlertMessages, "Boletos Actualizado en el Carrito");        //Mensajito de Felicidades
                    }

                    //=============  UPDATE THE SHOPPING STOCK ======
                    $NewStock = ($QuantityTicket - $OriginalQuantity);                         //Este es el verdadero stock

                    $TemporalQueryResult = $DataBase->query("
                            SELECT NumeroAsientos
                                FROM Sala, Funcion
                                WHERE ID = {$ProductID} AND 
                                Funcion.NumeroSala = Sala.NumeroSala");                       //Actualizo datos

                    if (!$TemporalQueryResult) {
                        array_push($AlertMessages, "Error con Base de Datos"); break;}        //Error Misterioso

                    $TemporalQueryData = $TemporalQueryResult->fetch_row();                   //Dame todos producto que hagan eso
                    $TotalSpots = $TemporalQueryData[0];                                      //Dame este valor

                    if ((($TotalSpots - $UsedSpots[$ProductID]) - $NewStock) < 0) {
                        array_push($AlertMessages, "Error con Stock");                          //Error Misterioso

                        //=============  END TRANSACCION  ======
                        $MiniTemporalQueryResult = $DataBase->query("ROLLBACK");                //Todo o nada
                        if (!$MiniTemporalQueryResult)                                          //Si es que hubo un problema
                            {array_push($AlertMessages, "Error con la Base de Datos"); break;}  //Error Misterioso
                    } 
                    else {
                        array_push($AlertMessages, "Stock Actualizado");                        //Cosa Magica
                        
                        //=============  END TRANSACCION  ======
                        $MiniTemporalQueryResult = $DataBase->query("COMMIT");                  //Todo o nada
                        if (!$MiniTemporalQueryResult)                                          //Si es que hubo un problema
                            {array_push($AlertMessages, "Error con la Base de Datos"); break;}  //Error Misterioso
                    }
                }
            }
            while (false);                                                                      //Solo lo queria por el break :v
        }

    }

    // ======================================================================
    // ====  CHECK THE INFO OF ALL THE CURRENTS PRODUCTS IN SHOPPING   ======
    // ======================================================================
    if (true) {                                                                                 //Dame la info de todos los productos
        $QueryProductsInCurrentSale = $DataBase->query("
                SELECT 
                    Funcion.ID,
                    Funcion.Precio,
                    Funcion.Hora,
                    Pelicula.Nombre,
                    TicketBoleto.Costo,
                    TicketBoleto.Cantidad,
                    Sala.NumeroAsientos
                    FROM 
                        Funcion, Pelicula, TicketBoleto, Sala
                    WHERE 
                        Pelicula.ID = Funcion.IDPelicula AND
                        Funcion.ID = TicketBoleto.IDFuncion AND
                        Funcion.NumeroSala = Sala.NumeroSala AND
                        IDventa = {$_SESSION['CurrentSaleIDMovies']}");                         //Busco productos

        if (!$QueryProductsInCurrentSale)                                                       //Si es que hubo un problema
            array_push($AlertMessages, "Error con los Productos Vendidos");
    }

    


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
        <!-- =====================    SELL THINGS          ==================== -->      
        <!-- ================================================================== -->    
        <div class="<?php echo $StandardGreyCard;?>">

            <!-- =================   CARD CONTENT  ================ -->      
            <div id="CardDetailsSell" name="CardDetailsSell" class="card-content">

                <!-- ========  TITLE  ================ -->
                <h4 class="grey-text text-darken-2"><b>Detalles</b> de la Venta</h4>
            
                <!-- ========  TEXT AND MENUS  ======= -->
                <div class="container">
                    
                    <!-- ========  TEXT   ======= -->
                    <span class="grey-text" style="font-size: 1.20rem;">
                        <br>
                        Aquí se encuentra la información de la Venta Actual, es decir
                        el total a cobrar, los boletos comprados y el subtotal por cada
                        uno.
                        <br><br>

                    </span>

                    <!-- ========  CURRENT ID   ======= -->
                    <h5 class="grey-text darken-2">
                        <b>IDVenta: </b> <?php echo $_SESSION['CurrentSaleIDMovies']; ?><br>
                    </h5>
                        
                    <br><br>

                </div>

                <div class="<?php if (!WeAreAtMobile()) echo "container"; ?>">

                    <!-- ========  IF YOU ALREADY SELL SOMETHING   ======= -->
                    <?php if ($_SESSION['TotalSellMovieTickets'] != 0.0) :?>
                                            
                        <!-- ================================================================== -->    
                        <!-- ==============       PRODUCTS DETAILS         ==================== -->      
                        <!-- ================================================================== -->   
                        <ul class="collapsible" data-collapsible="accordion">
                            <?php while ($Function = $QueryProductsInCurrentSale->fetch_assoc()) :
                                $Function['Stock'] = $Function['NumeroAsientos'] - $UsedSpots[$Function['ID']];?>

                                <li>
                                    <!-- ========  THE VITAL INFO OF THE PRODUCT ============ -->
                                    <div class = "collapsible-header" 
                                        style = "display: block; <?php if (WeAreAtMobile()) echo 'font-size: 0.9rem;'?>">

                                        <span class="left grey-text text-darken-1">
                                            
                                            <b><?php echo "({$Function['Cantidad']})"?>
                                            <?php 
                                                if (strlen($Function['Nombre']) > 18 AND WeAreAtMobile())
                                                    echo substr($Function['Nombre'], 0, 14)."...";
                                                else echo $Function['Nombre']; 
                                            ?>
                                            
                                            <b><?php echo "a las {$Function['Hora']}"; ?></b>
                                        </span>

                                        <span class="right grey-text text-darken-1"> <?php echo "$".$Function['Costo']; ?></span>
                                        <br>

                                    </div>

                                    <!-- ========  ALTER THE VITAL INFO OF THE PRODUCT ============ -->
                                    <div class="collapsible-body">
                                        
                                        <form action="SellMovieTickets.php" method="post" class="row">
                                            
                                            <!-- ========  PRICE  ============ -->
                                            <div class="col s12 left-align">
                                                <span class="grey-text text-darken-3" style="font-size: 1.7rem;">
                                                    <b>$<?php echo $Function['Precio']; ?></b>
                                                </span>
                                            </div>

                                            <!-- =====  SEND THE NUMBER OF THINGS ===== -->
                                            <div class="col s6 m8 l8">
                                                <div class='input-field'>
                                                    <input 
                                                        class = 'validate'
                                                        type  = 'number'
                                                        name  = '<?php echo $Function['ID']."QuantityTickets"; ?>' 
                                                        id    = '<?php echo $Function['ID']."QuantityTickets"; ?>'
                                                        min   = '1'
                                                        max   = '<?php echo ($Function['Stock'] + $Function['Cantidad']);?>'
                                                        value = '<?php echo $Function['Cantidad'];?>'
                                                    />
                                                    <label>Cantidad</label>
                                                </div>
                                            </div>

                                            <!-- =====  INITIAL STATE ===== -->
                                            <input 
                                                type  = "hidden" 
                                                id    = "<?php echo $Function['ID']."OriginalQuantity"; ?>"
                                                name  = "<?php echo $Function['ID']."OriginalQuantity"; ?>"
                                                value = '<?php echo $Function['Cantidad'];?>'
                                                >

                                            <!-- =====  SEND THE NAME OF THINGS ===== -->
                                            <input 
                                                type  = "hidden"
                                                name  = "<?php echo $Function['ID']."ProductName"; ?>"
                                                id    = "<?php echo $Function['ID']."ProductName"; ?>"
                                                value = "<?php echo $Function['Nombre']; ?>">

                                            <!-- =====  BUTTON TO SEND THE INFO ===== -->
                                            <br>
                                            <div class="col s2 l4">

                                                <div class="row">
                                                    <button 
                                                        class = "btn-flat waves-effect waves-light green lighten-1 white-text"
                                                        type  = "submit"
                                                        id    = '<?php echo $Function['ID']."ButtonChangeShoppingCar"; ?>'
                                                        name  = '<?php echo $Function['ID']."ButtonChangeShoppingCar"; ?>'>
                                                        Cambiar
                                                    </button>
                                                </div>

                                                <div class="row">
                                                    <button 
                                                        class = "btn-flat waves-effect waves-light red lighten-1 white-text"
                                                        type  = "submit"
                                                        id    = '<?php echo $Function['ID']."ButtonDeleteProduct"; ?>'
                                                        name  = '<?php echo $Function['ID']."ButtonDeleteProduct"; ?>'>
                                                        Eliminar
                                                    </button>
                                                </div>

                                            </div>
                                                
                                            <!-- =====  HOW MUCH IN STOCK ===== -->
                                            <div class="col s12 left-align">
                                                <span class="grey-text text-darken-2" style="font-size: 0.8rem;">
                                                    Disponibles para vender más: <?php echo ($Function['Stock'] - $Function['Cantidad']); ?> <br>
                                                    Disponibles en Total:  <?php echo $Function['Stock']; ?><br>
                                                </span>
                                            </div>

                                        </form>

                                    </div>

                                </li>

                            <?php endwhile;?>
                        </ul>

                        <br><br>

                        <!-- ========  CLOSE AN OK SALE ================ -->
                        <form class="row" action="SellMovieTickets.php" method="post">
                    
                            <!-- ========  BUTTON TO SEND ===== -->
                            <button
                                type  = 'submit'
                                id    = 'FinalizeSuccessfulSell'
                                name  = 'FinalizeSuccessfulSell'
                                class = 'col s8 offset-s2 l6 offset-l3 btn-large waves-effect green lighten-1'>
                                Cobrar $<?php echo $_SESSION['TotalSellMovieTickets'];?>
                            </button>
                        </form>

                        <!-- ========  CLOSE BAD SALE ================ -->
                        <form class="row" action="SellMovieTickets.php" method="post">
                    
                            <!-- ========  BUTTON TO SEND ===== -->
                            <button
                                type  = 'submit'
                                id    = 'CancelSellButton'
                                name  = 'CancelSellButton'
                                class = 'col s8 offset-s2 l6 offset-l3 btn-large waves-effect red lighten-1'>
                                Cancelar Venta
                            </button>

                        </form>

                    <?php endif; ?>

                </div>


            </div>

        </div>

        <br><br><br>


        <!-- ================================================================== -->    
        <!-- =====================   SEARCH FOR STUFF        ================== -->      
        <!-- ================================================================== -->    
        <div class="<?php echo $StandardGreyCard;?>">

            <!-- =================   CARD CONTENT  ================ -->      
            <div id="CardFindFunction" name="CardFindFunction" class="card-content">

                <!-- ========  TITLE  ================ -->
                <h4 class="grey-text text-darken-2"><b>Venta </b> de Boletos-Función</h4>
            
                <!-- ========  TEXT  ================ -->
                <span class="grey-text" style="font-size: 1.15rem;">
                    Selecciona la función para la que estas vendiendo
                    <br><br>
                </span>


                <!-- ========  MATERIAL FORM FOR SEACH  =============== -->
                <form class="container" action="SellMovieTickets.php" method="post">

                    <!-- ========  TYPES ============= -->
                    <div class='input-field center-align'>
                        <input
                            <?php if (isset($_POST['SearchForProduct'])) echo "autofocus"; ?>
                            class = 'validate'
                            type  = 'text'
                            id    = 'PossibleMovieName'
                            name  = 'PossibleMovieName'/>
                        <label>Busca Nombres de Películas</label>
                    </div>


                    <!-- ========  GENRES ============= -->
                    <div class='input-field'>
                        <select name="PossibleGenres[]" id="PossibleGenres" class="left-align" multiple>
                            <option value="All">Todos los Géneros</option>
                            <?php 
                                $AllGenres = array('Accion y Aventura', 'Familiar', 'Comedia', 'Documental', 'Drama', 'Terror', 
                                                    'Fantasia', 'Romantica', 'CienciaFiccion', 'Deportes', 'Suspenso');
                                
                                foreach ($AllGenres as $Genre): ?>
                                <option value="<?php echo $Genre;?>"><?php echo $Genre;?></option>
                            <?php endforeach; ?>

                        </select>
                        <label>Generos Buscados</label>
                    </div>

                    <!-- ========  CLASSIFICATIONS ============= -->
                    <div class='input-field'>
                        <select name="PossibleClassifications[]" id="PossibleClassifications" class="left-align" multiple>
                            <option value="All">Todos las Clasificaciones</option>
                            <?php 
                                $AllClassifications = array('AA', 'A', 'B', 'B15', 'C', 'D');
                                
                                foreach ($AllClassifications as $Classification): ?>
                                <option value="<?php echo $Classification;?>"><?php echo $Classification;?></option>
                            <?php endforeach; ?>

                        </select>
                        <label>Clasificaciones Buscadas</label>
                    </div>


                    <br>
                    <br>

                    <!-- ========  BUTTON TO SEND ===== -->
                    <button
                        type  = 'submit'
                        id    = 'SearchForMovie'
                        name  = 'SearchForMovie'
                        class = 'col s8 btn-large waves-effect indigo lighten-1'>
                        Buscar Función
                    </button>

                </form>

                <!-- ========  LIST OF POSSIBLE PRODUCTS  =============== -->
                <br><br>
                
                <div class="<?php if (!WeAreAtMobile()) echo "container"; ?>">
                <ul class="collapsible" data-collapsible="accordion">
                
                    <!-- ========  FOR EACH POSSIBLE PRODUCT ================ -->
                    <?php while ($Function = $QueryFunctionInfo->fetch_assoc()) : 
                        $Function['Stock'] = $Function['NumeroAsientos'] - $UsedSpots[$Function['ID']];
                    ?>
                    <!-- ========  MOVIE ITME ================ -->
                    <li>
                       
                        <!-- ========  MOVIE TITLE  ======== -->
                        <div class="collapsible-header" style = "display: block;"">

                            <!-- ========  MOVIE TEXT  ======== -->
                            <div 
                                class="grey-text text-darken-3 left valign-wrapper" 
                                style="font-size: <?php echo (WeAreAtMobile())? "0.9" : "1.1";?>rem;">
                                <!-- ========  MOVIE ICON  ======== -->
                                <i class="material-icons">local_play</i> 
                                
                                <?php 
                                    if (strlen($Function['Nombre']) > 21 AND WeAreAtMobile())
                                        echo substr($Function['Nombre'], 0, 18)."...";
                                    else echo $Function['Nombre']; 
                                ?>
                            </div>

                            <!-- ========  MOVIE TIME  ======== -->
                            <span class="indigo-text text-darken-3 right" style="font-size: 0.9rem;">
                                <b><?php echo $Function['Hora']; ?></b>
                            </span>

                            <br>

                        </div>
                      
                        <!-- ========  MOVIE BODY  ======== -->
                        <div class="collapsible-body">
                            
                            

                            <!-- ========  CLASSIFICATION TEXT  ======== -->
                            <form action="SellMovieTickets.php" method="post" class="row">

                                <!-- ========  PRICE  ============ -->
                                <div class="col s12 left-align">
                                    <span class="grey-text text-darken-3" style="font-size: 1.4rem;">
                                        <b><?php echo "Boleto: \${$Function['Precio']}"; ?></b>
                                    </span>
                                </div>

                                <!-- =====  SEND THE NUMBER OF THINGS ===== -->
                                <div class="col s6 m8 l8">
                                    <div class='input-field'>
                                        <input 
                                            class = 'validate'
                                            type  = 'number'
                                            name  = '<?php echo $Function['ID']."QuantityTickets"; ?>' 
                                            id    = '<?php echo $Function['ID']."QuantityTickets"; ?>'
                                            min   = '0'
                                            value = 1
                                            max   = '<?php echo $Function['Stock'];?>'
                                        />
                                        <label>Cantidad</label>
                                    </div>
                                </div>

                                <!-- =====  BUTTON TO SEND THE INFO ===== -->
                                <div class="col s2 l4">

                                    <br>
                                    <button 
                                        <?php if ($Function['Stock'] == 0) echo "disabled"; ?>
                                        class = "btn-flat waves-effect waves-light green lighten-1 white-text"
                                        type  = "submit"
                                        id    = '<?php echo $Function['ID']."ButtonChangeShoppingCar"; ?>'
                                        name  = '<?php echo $Function['ID']."ButtonChangeShoppingCar"; ?>'>
                                        Agregar
                                    </button>
    
                                </div>

                                <div class="row">
                                    <!-- =====  HOW MUCH IN STOCK ===== -->
                                    <div class="col s12 offset-s1 left-align">
                                        <span class="grey-text text-darken-2" style="font-size: 0.8rem;">
                                            Disponibles <?php echo $Function['Stock']; ?> lugares
                                        </span>
                                    </div>
                                </div>


                                <div 
                                    class="chip right" 
                                    style = "font-size: <?php if (WeAreAtMobile()) echo "0.85rem"; else "1rem;" ?>">
                                        <b><?php echo (str_replace(',', ', ', $Function['Genero']));?></b>
                                </div>

                                <div class="chip right">
                                    <b>Clasificación: <?php echo $Function['Clasificacion']; ?></b>
                                </div>

                                <div class="chip right">
                                    <b>Sala: <?php echo $Function['NumeroSala']; ?> </b>
                                </div>

                                <div class="chip right">
                                    <b>Tipo de Función: <?php echo $Function['Tipo']; ?> </b>
                                </div>

                            </form>

                        </div>
                    </li>
                    <?php endwhile;?>

                </ul>
                </div>

                <br><br>

            </div>

        </div>
        <br><br>


    </div>
    <br><br>



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



<?php 
    /*===================================================================
    ============         CLOSE ALL DATABASE     =========================
    ===================================================================*/
    include("PHP/HTMLFooter.php");

    if (isset($DataBase)) $DataBase->close();
?>