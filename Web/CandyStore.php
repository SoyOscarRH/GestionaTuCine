<?php
    /*=======================================================================================================================
    ============================================         LOGIN PROMPT          ==============================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE */
    include("PHP/ForAllPages.php");                                                             //Dame todas las ventajas que tiene incluir

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Area De Dulcería';                                                           //Titulo de cada Pagina
    $UpdateDate = '8 de Diciembre del 2017';                                                    //Fecha de actualizacion de la pagina


    // ========== SPECIFIC FOR THIS SCRIPT ==========
    $AlertMessages = array();                                                                   //Mensajes que mostramos 

    StandardCheckForStartedSession();                                                           //Asegurate de que pueda estar aqui
    $DataBase = StandardCheckForCorrectDataBase();                                              //Asegurate de que pueda estar aqui
    StandardUpdateSessionData($_SESSION['ID'], $DataBase);                                      //Asegurate que tdo este al dia

    ob_start();

    //=============  ADVANCE CHECK FOR VISITING THIS PAGE  =============
    if ($_SESSION['RolActual'] != 'Dulceria' AND !$_SESSION['IAmAManager'])                     //Solos los empleados entran
        CallErrorPagePermissions("No estas designado para Dulcería");                           //Sino error :o

    
    //=============  END HAPPY SELL  =============
    if (isset($_POST['FinalizeSuccessfulSell'])) {                                              //Si es que vamos a hacer un feliz Salida

        if ($_SESSION['TotalSell'] != 0.0) {
            $TemporalQueryResult = $DataBase->query("
                    UPDATE Venta
                        SET Total = {$_SESSION['TotalSell']} 
                        WHERE ID = {$_SESSION['CurrentSaleID']}");                              //Liberamos lo que necesito

            if (!$TemporalQueryResult) array_push($AlertMessages, "Error al Finalizar Venta");  //Mensajito

            unset($_SESSION['CurrentSaleID']);                                                  //Adios Sesion    
        }
    }

    //=============  END HAPPY SELL  =============
    if (isset($_POST['CancelSellButton'])) {                                                    //Si es que vamos a hacer un feliz Salida

        if ($_SESSION['TotalSell'] != 0.0) {
            $TemporalQueryResult = $DataBase->query("
                    DELETE FROM TicketDulceria 
                            WHERE IDventa = {$_SESSION['CurrentSaleID']}");                     //Liberamos lo que necesito

            if (!$TemporalQueryResult) array_push($AlertMessages, "Error al Cancelar Venta");   //Mensajito
        }
    }





    //======      IF WE NEED THE VARS FOR A NEW SALE    ===================
    if (empty($_SESSION['CurrentSaleID'])) {                                                    //Si es que no tienes ID de Venta
        
        $_SESSION['TotalSell'] = 0.0;                                                           //Lo que se va a cobrar a un cliente

        $QueryResult = $DataBase->query('LOCK TABLES Venta WRITE');                             //NO ME TOQUES
        if (!$QueryResult) array_push($AlertMessages, "Error al Crear Nueva Venta");            //Vemos que todo ok

        $QueryResult = $DataBase->query('SELECT MAX(ID) FROM Venta');                           //Dame el ID Maximo de Venta
        if (!$QueryResult) array_push($AlertMessages, "Error al Crear Nueva Venta");            //Vemos que todo ok
        
        $Row = $QueryResult->fetch_row();                                                       //Ahora dame todo lo que salio
        $_SESSION['CurrentSaleID'] = $Row[0] + 1;                                               //Ahora guardalo y aumenta uno

        $QueryResult = $DataBase->query("
                            INSERT INTO Venta (ID, IDEmpleado, Fecha)
                                VALUES (
                                    {$_SESSION['CurrentSaleID']}, 
                                    {$_SESSION['ID']},
                                    now()
                                )");                                                            //Ahora crea uno más
        
        if (!$QueryResult) array_push($AlertMessages, "Error al Crear Nueva Venta");            //Vemos que todo ok

        $QueryResult = $DataBase->query('UNLOCK TABLES');                                       //NO ME TOQUES
        if (!$QueryResult) array_push($AlertMessages, "Error al Crear Nueva Venta");            //Vemos que todo ok
    }

    //=============  IF YOU WANT TO MODIFY THE DATA  =============
    if (isset($_POST['SearchForProduct'])) {                                                    //Si es que quieres actualizar datos
        do {
            $PossibleProductName = ClearSQLInyection($_POST['PossibleProductName']);            //Dame la info
            $PossibleProductName = str_replace(' ', '%', $PossibleProductName);                 //Esto ayuda con el reconocimiento

            $QueryPossibleProducts = $DataBase->query('
                        SELECT ID, Stock, Nombre, Costo 
                            FROM ProductoDulceria
                            WHERE
                                Nombre LIKE "%'.$PossibleProductName.'%"');                     //Busquemos productos posibles

            if (!$QueryPossibleProducts)  {                                                     //Si error
                array_push($AlertMessages, "Error con la Base de Datos");                       //Envia mensajes
                unset($_POST['SearchForProduct']);                                              //Adios al texto y todo
                break;                                                                          //Adios proceso
            }

            if ($QueryPossibleProducts->num_rows == 0) {                                        //Si es que no hay tuplas
                array_push($AlertMessages, "No existe ese producto :(");                        //Envia mensajes
                unset($_POST['SearchForProduct']);                                              //Adios al texto y todo
            }
        }
        while (false);                                                                          //Solo queria el break :v
    }

    //=============  ADD / DELETE SOMETHING TO THE SHOPING BOX ===
    foreach ($_POST as $Name => $Value) {                                                       //Buscar el POST[] correcto
        if (fnmatch("*ButtonChangeShoppingCar",$Name) OR fnmatch("*ButtonDeleteProduct",$Name)){//Se tiene que parecer a esto
            do {                                                                                //Bien, vamos a intentar que todo ok
                //=============  GET THE DATA  =============
                if (fnmatch("*ButtonChangeShoppingCar", $Name))                                 //Si es que eras de aqui
                    $ProductID = str_replace("ButtonChangeShoppingCar", "", $Name);             //Puede que seas tu
                else $ProductID = str_replace("ButtonDeleteProduct", "", $Name);                //Puede que seas tu

                $QuantityProduct = ClearSQLInyection($_POST[$ProductID.'QuantityProduct']);     //Por si las dudas
                $ProductName = ClearSQLInyection($_POST[$ProductID.'ProductName']);             //Por si las dudas

                if (isset($_POST[$ProductID.'OriginalQuantity']))                               //Dame la anterior
                    $OriginalQuantity=ClearSQLInyection($_POST[$ProductID.'OriginalQuantity']); //Por si las dudas
                else $OriginalQuantity = 0;                                                     //Sino era cero :v

                //=============  FIND PRODUCT DETAILS ======
                $TemporalQueryResult = $DataBase->query("
                        SELECT Costo FROM ProductoDulceria 
                            WHERE ID = {$ProductID}");                                          //Obtenemos el costo del producto

                if (!$TemporalQueryResult)                                                      //Si es que hubo un problema
                    {array_push($AlertMessages, "Error con la Base de Datos"); break;}          //Error Misterioso

                $Row = $TemporalQueryResult->fetch_row();                                       //Dame todos producto que hagan eso
                $ProductUnitCost = $Row[0];                                                     //Dame este valor
                $ProductTotalCost = $ProductUnitCost * $QuantityProduct;                        //Seguro que solo hay uno
                $TemporalQueryResult->close();                                                  //Adios al Query


                //=============  WE WANNA DELETE ITEM ? ======
                if (isset($_POST[$ProductID.'ButtonDeleteProduct'])){                           // === SI ES QUE QUIERES ELIMINAR ===    

                    $QuantityProduct = ClearSQLInyection($_POST[$ProductID.'OriginalQuantity']);//Este es el correcto
                    $_SESSION['TotalSell'] -= ($ProductUnitCost * $QuantityProduct);            //Añado al total el nuevo

                    do {
                        //=============  DELETE PRODUCT DETAILS ======
                        $TemporalQueryResult = $DataBase->query("
                                DELETE FROM TicketDulceria 
                                    WHERE 
                                        IDProducto = {$ProductID} AND
                                        IDVenta = {$_SESSION['CurrentSaleID']}");               //Obtenemos el costo del producto

                        if (!$TemporalQueryResult)                                              //Si es que hubo un problema
                            {array_push($AlertMessages, "Error al Eliminar Elemento"); break;}  //Error Misterioso

                        //=============  ALTER STOCK ======
                        $TemporalQueryResult = $DataBase->query("
                            UPDATE ProductoDulceria
                                SET Stock = Stock + {$QuantityProduct}
                                WHERE ID = {$ProductID}");                                      //Actualizo datos

                        if (!$TemporalQueryResult)                                              //Si es que hubo un problema
                            {array_push($AlertMessages, "Error al Actualizar Stock"); break;}   //Error Misterioso

                        array_push($AlertMessages, "Producto Eliminado");                       //Adios Producto
                    }
                    while (false);

                }
                else {                                                                          // === SI QUIERES AÑADIR / ALTERAR ===
                    //=============  THIS PRODUCT WAS ALREADY IN THE LIST ? ======
                    $TemporalQueryResult = $DataBase->query("
                            SELECT Costo FROM TicketDulceria 
                                WHERE 
                                    IDProducto = {$ProductID} AND 
                                    IDVenta = {$_SESSION['CurrentSaleID']}");                   //Obtenemos el precio anterior

                    if (!$TemporalQueryResult)                                                  //Si es que hubo un problema
                    {array_push($AlertMessages, "Error con la Base de Datos"); break;}          //Error Misterioso

                    //========== NO: ADD TO SHOPPING LIST ======
                    if ($TemporalQueryResult->num_rows == 0) {                                  //Si es que no tenias esto
                        
                        $TemporalQueryResult = $DataBase->query("
                            INSERT INTO TicketDulceria
                                VALUES (
                                    {$ProductTotalCost},
                                    {$QuantityProduct},
                                    {$_SESSION['CurrentSaleID']},
                                    {$ProductID}
                                )");                                                            //Creo nuevo registro del producto

                        if (!$TemporalQueryResult)                                              //Si es que hubo un problema
                            {array_push($AlertMessages, "Error con la Base de Datos"); break;}  //Error Misterioso
                        
                        $_SESSION['TotalSell'] += $ProductTotalCost;                            //Añado al total
                        array_push($AlertMessages, "Producto Añadido al Carrito");              //Muestro lindo Mensajito
                    }
                    //========= YES: UPDATE THE SHOPPING LIST ======
                    else {                                                                      //Si es que ya tenia este producto
                        $Row = $TemporalQueryResult->fetch_row();                               //Dame el costo anterior
                        $_SESSION['TotalSell'] -= $Row[0];                                      //Lo quito

                        $TemporalQueryResult = $DataBase->query("
                            UPDATE TicketDulceria
                                SET 
                                    Costo = {$ProductTotalCost},
                                    Cantidad = {$QuantityProduct}
                                WHERE 
                                    IDProducto = {$ProductID} AND 
                                    IDVenta = {$_SESSION['CurrentSaleID']}");                   //Actualizo datos


                        if (!$TemporalQueryResult)                                              //Si es que hubo un problema
                            {array_push($AlertMessages, "Error con la Base de Datos"); break;}  //Error Misterioso
                        
                        $_SESSION['TotalSell'] += $ProductTotalCost;                            //Añado al total el nuevo
                        array_push($AlertMessages, "Producto Actualizado en el Carrito");       //Mensajito de Felicidades
                    }

                    //=============  UPDATE THE SHOPPING STOCK ======
                    $NewStock = ($QuantityProduct - $OriginalQuantity);                         //Este es el verdadero stock

                    $TemporalQueryResult = $DataBase->query("
                            UPDATE ProductoDulceria
                                SET Stock = Stock - {$NewStock}
                                WHERE ID = {$ProductID}");                                      //Actualizo datos

                    if (!$TemporalQueryResult) array_push($AlertMessages, "Error con Stock");   //Error Misterioso
                    else array_push($AlertMessages, "Stock Actualizado");                       //Error Misterioso
                }
            }
            while (false);                                                                      //Solo lo queria por el break :v
        }

    }


    //== CHECK THE INFO OF ALL THE CURRENTS PRODUCTS IN SHOPPING ===
    if (true) {                                                                                 //Dame la info de todos los productos
        $QueryProductsInCurrentSale = $DataBase->query("
                SELECT 
                    ProductoDulceria.ID,
                    ProductoDulceria.Nombre,
                    ProductoDulceria.Stock,
                    ProductoDulceria.Costo AS CostoProducto,
                    TicketDulceria.Costo,
                    TicketDulceria.Cantidad
                    FROM 
                        ProductoDulceria, TicketDulceria
                    WHERE 
                        ProductoDulceria.ID = TicketDulceria.iDProducto AND
                        IDventa = {$_SESSION['CurrentSaleID']}");                               //Busco productos

                if (!$QueryProductsInCurrentSale)                                              //Si es que hubo un problema
                    array_push($AlertMessages, "Error con los Productos Vendidos");

    }










    // *****************************************************************************************
    // *************************     PROCESS TO START THE SYSTEM   *****************************
    // *****************************************************************************************
    $StandardGreyCard = "card grey lighten-4 col s12 m8 l8 offset-m2 offset-l2";                //Es una forma de que sea mas sencilla 


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
                    
                    <span class="grey-text" style="font-size: 1.20rem;">
                        <br>
                        Aquí se encuentra la información de la Venta Actual, es decir
                        el total a cobrar, los elementos comprados y el subtotal por cada
                        uno.
                        <br><br>

                    </span>

                    <span class="grey-text darken-2">
                        <b>IDVentas: </b> <?php echo $_SESSION['CurrentSaleID']; ?><br>
                    </span>
                        
                    <br><br>

                    
                    <!-- ================================================================== -->    
                    <!-- ==============       PRODUCTS DETAILS         ==================== -->      
                    <!-- ================================================================== -->   
                    <ul class="collapsible" data-collapsible="accordion">

                        <?php while ($Row = $QueryProductsInCurrentSale->fetch_assoc()) :?>

                            <li>
                                <!-- ========  THE VITAL INFO OF THE PRODUCT ============ -->
                                <div class = "collapsible-header" 
                                    style = "display: block; <?php if (WeAreAtMobile()) echo 'font-size: 0.9rem;'?>">

                                    <span class="left grey-text text-darken-1">
                                        <b><?php echo "({$Row['Cantidad']}) {$Row['Nombre']}"; ?></b>
                                    </span>

                                    <span class="right grey-text text-darken-1"> <?php echo "$".$Row['Costo']; ?></span>

                                    <br>

                                </div>

                                <!-- ========  ALTER THE VITAL INFO OF THE PRODUCT ============ -->
                                <div class="collapsible-body">
                                    
                                    <form action="CandyStore.php" method="post" class="row">
                                        
                                        <!-- ========  PRICE  ============ -->
                                        <div class="col s12 left-align">
                                            <span class="grey-text text-darken-3" style="font-size: 1.7rem;">
                                                <b>$<?php echo $Row['CostoProducto']; ?></b>
                                            </span>
                                        </div>

                                        <!-- =====  SEND THE NUMBER OF THINGS ===== -->
                                        <div class="col s6 m8 l8">
                                            <div class='input-field'>
                                                <input 
                                                    class = 'validate'
                                                    type  = 'number'
                                                    name  = '<?php echo $Row['ID']."QuantityProduct"; ?>' 
                                                    id    = '<?php echo $Row['ID']."QuantityProduct"; ?>'
                                                    min   = '1'
                                                    max   = '<?php echo ($Row['Stock'] + $Row['Cantidad']);?>'
                                                    value = '<?php echo $Row['Cantidad'];?>'
                                                />
                                                <label>Cantidad</label>
                                            </div>
                                        </div>

                                        <!-- =====  INITIAL STATE ===== -->
                                        <input 
                                            type  = "hidden" 
                                            id    = "<?php echo $Row['ID']."OriginalQuantity"; ?>"
                                            name  = "<?php echo $Row['ID']."OriginalQuantity"; ?>"
                                            value = '<?php echo $Row['Cantidad'];?>'
                                            >

                                        <!-- =====  SEND THE NAME OF THINGS ===== -->
                                        <input 
                                            type  = "hidden"
                                            name  = "<?php echo $Row['ID']."ProductName"; ?>"
                                            id    = "<?php echo $Row['ID']."ProductName"; ?>"
                                            value = "<?php echo $Row['Nombre']; ?>">

                                        <!-- =====  BUTTON TO SEND THE INFO ===== -->
                                        <br>
                                        <div class="col s2 l4">

                                            <div class="row">
                                                <button 
                                                    class = "btn-flat waves-effect waves-light green lighten-1 white-text"
                                                    type  = "submit"
                                                    id    = '<?php echo $Row['ID']."ButtonChangeShoppingCar"; ?>'
                                                    name  = '<?php echo $Row['ID']."ButtonChangeShoppingCar"; ?>'>
                                                    Cambiar
                                                </button>
                                            </div>

                                            <div class="row">
                                                <button 
                                                    class = "btn-flat waves-effect waves-light red lighten-1 white-text"
                                                    type  = "submit"
                                                    id    = '<?php echo $Row['ID']."ButtonDeleteProduct"; ?>'
                                                    name  = '<?php echo $Row['ID']."ButtonDeleteProduct"; ?>'>
                                                    Eliminar
                                                </button>
                                            </div>

                                            
            
                                        </div>
                                            
                                        <!-- =====  HOW MUCH IN STOCK ===== -->
                                        <div class="col s12 left-align">
                                            <span class="grey-text text-darken-2" style="font-size: 0.8rem;">
                                                Cantidad en Stock Libre: <?php echo $Row['Stock']; ?> <br>
                                                Cantidad en Stock Total: <?php echo ($Row['Stock'] + $Row['Cantidad']); ?> <br>
                                            </span>
                                        </div>

                                    </form>

                                </div>

                            </li>

                        <?php endwhile;?>
                    </ul>

                </div>

                <br><br>

                <!-- ========  CLOSE AN OK SALE ================ -->
                <form class="row" action="CandyStore.php" method="post">
            
                    <!-- ========  BUTTON TO SEND ===== -->
                    <button
                        type  = 'submit'
                        id    = 'FinalizeSuccessfulSell'
                        name  = 'FinalizeSuccessfulSell'
                        class = 'col s8 offset-s2 l4 offset-l4 btn-large waves-effect green lighten-1'>
                        Cobrar $<?php echo $_SESSION['TotalSell'];?>
                    </button>
                </form>

                <!-- ========  CLOSE BAD SALE ================ -->
                <form class="row" action="CandyStore.php" method="post">
            
                    <!-- ========  BUTTON TO SEND ===== -->
                    <button
                        type  = 'submit'
                        id    = 'CancelSellButton'
                        name  = 'CancelSellButton'
                        class = 'col s8 offset-s2 l4 offset-l4 btn-large waves-effect red lighten-1'>
                        Cancelar Venta
                    </button>

                </form>

            </div>

        </div>

        <br><br><br>


        <!-- ================================================================== -->    
        <!-- =====================   SEARCH FOR STUFF        ================== -->      
        <!-- ================================================================== -->    
        <div class="<?php echo $StandardGreyCard;?>">

            <!-- =================   CARD CONTENT  ================ -->      
            <div id="CardFindProduct" name="CardFindProduct" class="card-content">

                <!-- ========  TITLE  ================ -->
                <h4 class="grey-text text-darken-2"><b>Busqueda</b> de Productos</h4>
            
                <!-- ========  TEXT  ================ -->
                <span class="grey-text" style="font-size: 1.15rem;">
                    Busca el nombre (o fragmentos) de lo que quieras vender :D
                    <br><br>
                </span>

                <!-- ========  MATERIAL FORM FOR SEACH  =============== -->
                <form class="container" action="CandyStore.php" method="post">

                    <!-- ========  NAME ============= -->
                    <div class='input-field center-align'>
                        <i class="material-icons grey-text text-darken-2 prefix">search</i>
                        <input
                            <?php if (isset($_POST['SearchForProduct'])) echo "autofocus"; ?>
                            class = 'validate'
                            type  = 'text'
                            id    = 'PossibleProductName'
                            name  = 'PossibleProductName'/>
                        <label>Busca Productos</label>
                    </div>

                    <br>
                    <br>

                    <!-- ========  BUTTON TO SEND ===== -->
                    <button
                        type  = 'submit'
                        id    = 'SearchForProduct'
                        name  = 'SearchForProduct'
                        class = 'col s8 btn-large waves-effect green lighten-1'>
                        Buscar Producto
                    </button>

                </form>

                <!-- ========  LIST OF POSSIBLE PRODUCTS  =============== -->
                <?php if (isset($_POST['SearchForProduct'])) :?>
                    <br><br>
                    
                    <div class="container">
                    <ul class="collapsible" data-collapsible="accordion">
                    
                        <!-- ========  FOR EACH POSSIBLE PRODUCT ================ -->
                        <?php while ($Row = $QueryPossibleProducts->fetch_assoc()) : ?>
                        <li>
                            
                            <!-- ========  NUMBER AN ICON OF PRODUCT ============ -->
                            <div class="collapsible-header">

                                <i class="material-icons">local_dining</i>
                                
                                <span class="grey-text text-darken-3 left-align" style="font-size: 1.1rem;">
                                    <?php echo $Row['Nombre']; ?>
                                </span>

                            </div>

                            <!-- ========  INFO OF PRODUCT ============ -->
                            <div class="collapsible-body">

                                <!-- ========  A ROW TO STYLE ALL A FORM TO SEND INFO ======== -->
                                <form action="CandyStore.php" method="post" class="row">
                                    
                                    <!-- ========  PRICE  ============ -->
                                    <div class="col s12 left-align">
                                        <span class="grey-text text-darken-3" style="font-size: 1.7rem;">
                                            <b>$<?php echo $Row['Costo']; ?></b>
                                        </span>
                                    </div>

                                    <!-- =====  SEND THE NUMBER OF THINGS ===== -->
                                    <div class="col s6 m8 l8">
                                        <div class='input-field'>
                                            <input 
                                                class = 'validate'
                                                type  = 'number'
                                                name  = '<?php echo $Row['ID']."QuantityProduct"; ?>' 
                                                id    = '<?php echo $Row['ID']."QuantityProduct"; ?>'
                                                min   = '0'
                                                max   = '<?php echo $Row['Stock'];?>'
                                            />
                                            <label>Cantidad</label>
                                        </div>
                                    </div>

                                    <!-- =====  SEND THE NAME OF THINGS ===== -->
                                    <input 
                                        type  = "hidden"
                                        name  = "<?php echo $Row['ID']."ProductName"; ?>"
                                        id    = "<?php echo $Row['ID']."ProductName"; ?>"
                                        value = "<?php echo $Row['Nombre']; ?>">

                                    <!-- =====  BUTTON TO SEND THE INFO ===== -->
                                    <div class="col s2 m4 l4">

                                        <br>
                                        <button 
                                            <?php if ($Row['Stock'] == 0) echo "disabled"; ?>
                                            class = "btn-flat waves-effect waves-light light-green darken-1 white-text"
                                            type  = "submit"
                                            id    = '<?php echo $Row['ID']."ButtonChangeShoppingCar"; ?>'
                                            name  = '<?php echo $Row['ID']."ButtonChangeShoppingCar"; ?>'>
                                            Agregar
                                        </button>
        
                                    </div>
                                        
                                    <!-- =====  HOW MUCH IN STOCK ===== -->
                                    <div class="col s12 left-align">
                                        <span class="grey-text text-darken-2" style="font-size: 0.8rem;">
                                            Cantidad en Stock: <?php echo $Row['Stock']; ?>
                                        </span>
                                    </div>

                                </form>
                                    
                            </div>

                        </li>
                        <?php endwhile;?>

                    </ul>
                    </div>

                <?php endif; ?>

            </div>

        </div>



        <br><br><br>

    </div>


    <!-- ================================================================= -->    
    <!-- ===============         FAB FOR THE PAGE       ================== -->    
    <!-- ================================================================= -->
    <div class="fixed-action-btn <?php if (WeAreAtMobile()) echo "click-to-toggle";?>">
        
        <a class="btn-floating btn-large cyan darken-3">
            <i class="unselectable large material-icons">view_list</i>
        </a>
        
        <ul>

            <!-- =======  TO EDIT EMPLOYEES INFO  ========== -->    
            <li>
                <a href    = "#CardDetailsSell"
                   onclick = "$('.fixed-action-btn').closeFAB();" 
                   class   = "btn-floating indigo">
                    <i class="unselectable material-icons">info_outline</i>
                </a>
            </li>

            <!-- =======  TO SEE EMPLOYEES INFO  ========== -->    
            <li>
                <a href    = "#CardFindProduct"
                   onclick = "$('.fixed-action-btn').closeFAB();" 
                   class   = "btn-floating green">
                    <i class="unselectable material-icons">search</i>
                </a>
            </li>

        </ul>
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
                foreach ($AlertMessages as $Alert) echo "Materialize.toast('$TitleAlert $Alert', 5000);"; 
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
