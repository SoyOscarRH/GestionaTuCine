<?php
    /*=======================================================================================================================
    ============================================         LOGIN PROMPT          ==============================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE */
    include("PHP/ForAllPages.php");                                                             //Dame todas las ventajas que tiene incluir

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Venta Dulcería';                                                             //Titulo de cada Pagina
    $UpdateDate = '8 de Diciembre del 2017';                                                    //Fecha de actualizacion de la pagina


    // ========== SPECIFIC FOR THIS SCRIPT ==========
    $AlertMessages = array();                                                                   //Mensajes que mostramos 

    StandardCheckForStartedSession();                                                           //Asegurate de que pueda estar aqui
    $DataBase = StandardCheckForCorrectDataBase();                                              //Asegurate de que pueda estar aqui
    StandardUpdateSessionData($_SESSION['ID'], $DataBase);                                      //Asegurate que tdo este al dia

    //=============  ADVANCE CHECK FOR VISITING THIS PAGE  =============
    if ($_SESSION['RolActual'] != 'Dulceria' AND !$_SESSION['IAmAManager'])                     //Solos los empleados entran
        CallErrorPagePermissions("No estas designado para Dulcería");                           //Sino error :o

    // ======================================================================
    // ======================   FINISH A SELL  ==============================
    // ======================================================================

    //=============  END HAPPY SELL  =============
    if (isset($_POST['CancelSellButton']) AND ($_SESSION['TotalSellCandyShop'] != 0.0)) {      //Si es que vamos a hacer un feliz Salida

        $TemporalQueryResult = $DataBase->query("
                DELETE FROM TicketDulceria 
                        WHERE IDventa = {$_SESSION['CurrentSaleIDCandyShop']}");                //Liberamos lo que necesito

        if (!$TemporalQueryResult) array_push($AlertMessages, "Error al Cancelar Venta");       //Mensajito
        else array_push($AlertMessages, "Venta Cancelada :D");                                  //Mensajito

        $_SESSION['TotalSellCandyShop'] = 0.0;                                                  //Volvemos todo a la normalidad
    }


    //=============  END HAPPY SELL  =============
    if (isset($_POST['FinalizeSuccessfulSell'])) {                                              //Si es que vamos a hacer un feliz Salida

        if ($_SESSION['TotalSellCandyShop'] != 0.0) {
            $TemporalQueryResult = $DataBase->query("
                    UPDATE Venta
                        SET Total = {$_SESSION['TotalSellCandyShop']} 
                        WHERE ID = {$_SESSION['CurrentSaleIDCandyShop']}");                     //Liberamos lo que necesito

            if (!$TemporalQueryResult) array_push($AlertMessages, "Error al Finalizar Venta");  //Mensajito

            unset($_SESSION['CurrentSaleIDCandyShop']);                                         //Adios Sesion    
        }

        array_push($AlertMessages, "Venta Guardada Correcta");                                  //Mensajito
    }


    // ======================================================================
    // ======================   START A SELL   ==============================
    // ======================================================================
    if (empty($_SESSION['CurrentSaleIDCandyShop'])) {                                           //Si es que no tienes ID de Venta
        do {                                                                                    //Solo para los breaks :v
            $_SESSION['TotalSellCandyShop'] = 0.0;                                              //Lo que se va a cobrar a un cliente

            $TemporalQueryResult = $DataBase->query('LOCK TABLES Venta WRITE');                 //NO ME TOQUES
            if (!$TemporalQueryResult)                                                          //Si es que hay algo mal
                {array_push($AlertMessages, "Error al Crear Nueva Venta");break;}               //Vemos que todo ok

            $TemporalQueryResult = $DataBase->query('SELECT MAX(ID) FROM Venta');               //Dame el ID Maximo de Venta
            if (!$TemporalQueryResult)                                                          //Si es que hay algo mal
                {array_push($AlertMessages, "Error al Crear Nueva Venta");break;}               //Vemos que todo ok
            
            $OldUltimateSellID = $TemporalQueryResult->fetch_row();                             //Ahora dame todo lo que salio
            $_SESSION['CurrentSaleIDCandyShop'] = $OldUltimateSellID[0] + 1;                    //Ahora guardalo y aumenta uno

            $TemporalQueryResult = $DataBase->query("
                                INSERT INTO Venta (ID, IDEmpleado, Fecha)
                                    VALUES (
                                        {$_SESSION['CurrentSaleIDCandyShop']}, 
                                        {$_SESSION['ID']},
                                        now()
                                    )");                                                        //Ahora crea uno más
            
            if (!$TemporalQueryResult)                                                          //Si es que hay algo mal
                {array_push($AlertMessages, "Error al Crear Nueva Venta");break;}               //Vemos que todo ok

            $TemporalQueryResult = $DataBase->query('UNLOCK TABLES');                           //NO ME TOQUES
            if (!$TemporalQueryResult)                                                          //Si es que hay algo mal
                {array_push($AlertMessages, "Error al Crear Nueva Venta");break;}               //Vemos que todo ok
        }
        while (false);                                                                          //Solo te quiero por el break :v
    }

    // ======================================================================
    // =================  IF YOU WANT TO MODIFY THE DATA  ===================
    // ======================================================================
    if (isset($_POST['SearchForProduct'])) {                                                    //Si es que quieres actualizar datos
        do {
            $PossibleProductName = ClearSQLInyection($_POST['PossibleProductName']);            //Dame la info
            $PossibleProductName = str_replace(' ', '%', $PossibleProductName);                 //Esto ayuda con el reconocimiento            
            $QueryPossibleProducts = $DataBase->query('
                        SELECT ID, Stock, Nombre, Costo 
                            FROM ProductoDulceria
                            WHERE
                                Nombre LIKE "%'.$PossibleProductName.'%"');                     //Busquemos productos posibles

            if (!$QueryPossibleProducts) {                                                      //Si error
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

    // ======================================================================
    // ==========   DELETE, ALTER, ADD ITEM SHOPPING CAR      ===============
    // ======================================================================
    foreach ($_POST as $Name => $Value) {                                                       //Buscar el POST[] correcto
        if(fnmatch("*ButtonChangeShoppingCar",$Name) OR fnmatch("*ButtonDeleteProduct",$Name)){ //Se tiene que parecer a esto
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

                if (!$QuantityProduct OR !$ProductName OR !$ProductID)                         //Si estas estupido
                    {array_push($AlertMessages, "No colocaste datos"); break;}                 //Error Misterioso 

                //=============  FIND PRODUCT DETAILS ======
                $TemporalQueryResult = $DataBase->query("
                        SELECT Costo FROM ProductoDulceria 
                            WHERE ID = {$ProductID}");                                          //Obtenemos el costo del producto

                if (!$TemporalQueryResult)                                                      //Si es que hubo un problema
                    {array_push($AlertMessages, "Error con la Base de Datos"); break;}          //Error Misterioso

                $TemporalQueryData = $TemporalQueryResult->fetch_row();                         //Dame todos producto que hagan eso
                $ProductUnitCost = $TemporalQueryData[0];                                       //Dame este valor
                $ProductTotalCost = $ProductUnitCost * $QuantityProduct;                        //Seguro que solo hay uno
                $TemporalQueryResult->close();                                                  //Adios al Query


                //============================================
                //=============  WE WANNA DELETE ITEM ? ======
                //============================================
                if (isset($_POST[$ProductID.'ButtonDeleteProduct'])){                           // === SI ES QUE QUIERES ELIMINAR ===    

                    $QuantityProduct = ClearSQLInyection($_POST[$ProductID.'OriginalQuantity']);//Este es el correcto
                    $_SESSION['TotalSellCandyShop'] -= ($ProductUnitCost * $QuantityProduct);   //Añado al total el nuevo

                    do {
                        //=============  DELETE PRODUCT DETAILS ======
                        $TemporalQueryResult = $DataBase->query("
                                DELETE FROM TicketDulceria 
                                    WHERE 
                                        IDProducto = {$ProductID} AND
                                        IDVenta = {$_SESSION['CurrentSaleIDCandyShop']}");      //Obtenemos el costo del producto

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
                    while (false);                                                              //Solo lo queria por el break
                }
                else {                                                                          // === SI QUIERES AÑADIR / ALTERAR ===
                    //=============  THIS PRODUCT WAS ALREADY IN THE LIST ? ======
                    $TemporalQueryResult = $DataBase->query("
                            SELECT Costo FROM TicketDulceria 
                                WHERE 
                                    IDProducto = {$ProductID} AND 
                                    IDVenta = {$_SESSION['CurrentSaleIDCandyShop']}");          //Obtenemos el precio anterior



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
                            INSERT INTO TicketDulceria
                                VALUES (
                                    {$ProductTotalCost},
                                    {$QuantityProduct},
                                    {$_SESSION['CurrentSaleIDCandyShop']},
                                    {$ProductID}
                                )");                                                            //Creo nuevo registro del producto

                        if (!$TemporalQueryResult)                                              //Si es que hubo un problema
                            {array_push($AlertMessages, "Error con la Base de Datos"); break;}  //Error Misterioso
                        
                        $_SESSION['TotalSellCandyShop'] += $ProductTotalCost;                   //Añado al total
                        array_push($AlertMessages, "Producto Añadido al Carrito");              //Muestro lindo Mensajito
                    }
                    //============================================
                    //=========  EXISTING ITEM TO SHOPPING CAR  ==
                    //============================================
                    else {                                                                      //Si es que ya tenia este producto
                        $TemporalQueryData = $TemporalQueryResult->fetch_row();                 //Dame el costo anterior
                        $_SESSION['TotalSellCandyShop'] -= $TemporalQueryData[0];               //Lo quito

                        if (!isset($_POST[$ProductID.'OriginalQuantity'])) {                    //Si es que estamos en el menu buscar
                            $TemporalQueryResult = $DataBase->query("
                                SELECT 
                                    Cantidad
                                    FROM TicketDulceria
                                    WHERE 
                                        TicketDulceria.IDProducto = {$ProductID} AND
                                        IDventa = {$_SESSION['CurrentSaleIDCandyShop']}");      //Busco productos

                            if (!$TemporalQueryResult)                                          //Si es que hubo un problema
                                array_push($AlertMessages, "Error con los Productos Vendidos");

                            $TemporalQueryData = $TemporalQueryResult->fetch_row();             //Dame el costo anterior
                            $OriginalQuantity = $TemporalQueryData[0];                          //Lo quito
                        }

                        $TemporalQueryResult = $DataBase->query("
                            UPDATE TicketDulceria
                                SET 
                                    Costo = {$ProductTotalCost},
                                    Cantidad = {$QuantityProduct}
                                WHERE 
                                    IDProducto = {$ProductID} AND 
                                    IDVenta = {$_SESSION['CurrentSaleIDCandyShop']}");          //Actualizo datos

                        if (!$TemporalQueryResult)                                              //Si es que hubo un problema
                            {array_push($AlertMessages, "Error con la Base de Datos"); break;}  //Error Misterioso
                        
                        $_SESSION['TotalSellCandyShop'] += $ProductTotalCost;                   //Añado al total el nuevo
                        array_push($AlertMessages, "Producto Actualizado en el Carrito");       //Mensajito de Felicidades
                    }

                    //=============  UPDATE THE SHOPPING STOCK ======
                    $NewStock = ($QuantityProduct - $OriginalQuantity);                         //Este es el verdadero stock

                    $TemporalQueryResult = $DataBase->query("
                            UPDATE ProductoDulceria
                                SET Stock = Stock - {$NewStock}
                                WHERE ID = {$ProductID}");                                      //Actualizo datos

                    if (!$TemporalQueryResult) {
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
                    ProductoDulceria.ID,
                    ProductoDulceria.Nombre,
                    ProductoDulceria.Stock,
                    ProductoDulceria.Costo AS CostoProducto,
                    TicketDulceria.Costo,
                    TicketDulceria.Cantidad
                    FROM 
                        ProductoDulceria, TicketDulceria
                    WHERE 
                        ProductoDulceria.ID = TicketDulceria.IDProducto AND
                        IDventa = {$_SESSION['CurrentSaleIDCandyShop']}");                      //Busco productos

        if (!$QueryProductsInCurrentSale)                                                       //Si es que hubo un problema
            array_push($AlertMessages, "Error con los Productos Vendidos");
    }


    // ======================================================================
    // ===============      ADD NEW DATA TO THE CANDY SHOP   ================
    // ======================================================================
    if (isset($_POST['AddNewProduct'])) {                                                       //Vamos a crear algo
        do {                                                                                    //Solo te quiero por el break :v
            $NewProductName = ClearSQLInyection($_POST['NewProductName']);                      //Por si las dudas
            $NewProductCost = ClearSQLInyection($_POST['NewProductCost']);                      //Por si las dudas
            $NewProductStock = ClearSQLInyection($_POST['NewProductStock']);                    //Por si las dudas


            $TemporalQueryResult = $DataBase->query("
                SELECT Nombre FROM ProductoDulceria
                        WHERE Nombre = '{$NewProductName}'");                                   //Dime que soy la primera :(

            if ($TemporalQueryResult->num_rows != 0)                                            //Si es que no hay tuplas
                {array_push($AlertMessages, "Este producto ya existe :("); break;}              //Envia mensajes

            $TemporalQueryResult = $DataBase->query("
                INSERT INTO ProductoDulceria(Stock, Nombre, Costo)
                        VALUES (
                                {$NewProductStock}, 
                                '{$NewProductName}', 
                                {$NewProductCost} 
                            )");                                                                //Actualizo datos

            if (!$TemporalQueryResult) array_push($AlertMessages, "Error al Añadir Producto");  //Error Misterioso
            else array_push($AlertMessages, "Producto Añadido");                                //Error Misterioso
        }
        while (false);                                                                          //Solo te quiero por el break :v
    }

    
    // ======================================================================
    // ===============      DELETE  DATA TO THE CANDY SHOP   ================
    // ======================================================================
    //=============  DELETE A PRODUCT  =============
    if (isset($_POST['DeleteProduct'])) {
        $ProductID = ClearSQLInyection($_POST['SelectedProduct']);                              //Por si las dudas
        
        $TemporalQueryResult = $DataBase->query("
                DELETE FROM ProductoDulceria 
                    WHERE ID = {$ProductID}");                                                  //Adios

        if (!$TemporalQueryResult) array_push($AlertMessages, "Error al Eliminar Producto");    //Envia mensajes
        else array_push($AlertMessages, "Producto Eliminado :D");                               //Envia mensajes
    }

    //=============  UPDATE THE LIST  =============
    if ($_SESSION['IAmAManager']) {                                                             //Si eres un gerente
        $QueryProductsData = $DataBase->query("SELECT Nombre, ID FROM ProductoDulceria");       //Dame la info

        if (!$QueryProductsData) array_push($AlertMessages, "Error al Abrir Base de Datos");    //Envia mensajes
        else if ($QueryProductsData->num_rows == 0)                                             //Si es que no hay tuplas
            array_push($AlertMessages, "Error al Buscar Productos");                            //Envia mensajes
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
                    
                    <!-- ========  TEXT   ======= -->
                    <span class="grey-text" style="font-size: 1.20rem;">
                        <br>
                        Aquí se encuentra la información de la Venta Actual, es decir
                        el total a cobrar, los elementos comprados y el subtotal por cada
                        uno.
                        <br><br>

                    </span>

                    <!-- ========  CURRENT ID   ======= -->
                    <h5 class="grey-text darken-2">
                        <b>IDVenta: </b> <?php echo $_SESSION['CurrentSaleIDCandyShop']; ?><br>
                    </h5>
                        
                    <br><br>

                </div>

                <div class="<?php if (!WeAreAtMobile()) echo "container"; ?>">

                    <!-- ========  IF YOU ALREADY SELL SOMETHING   ======= -->
                    <?php if ($_SESSION['TotalSellCandyShop'] != 0.0) :?>
                                            
                        <!-- ================================================================== -->    
                        <!-- ==============       PRODUCTS DETAILS         ==================== -->      
                        <!-- ================================================================== -->   
                        <ul class="collapsible" data-collapsible="accordion">
                            <?php while ($Product = $QueryProductsInCurrentSale->fetch_assoc()) :?>

                                <li>
                                    <!-- ========  THE VITAL INFO OF THE PRODUCT ============ -->
                                    <div class = "collapsible-header" 
                                        style = "display: block; <?php if (WeAreAtMobile()) echo 'font-size: 0.9rem;'?>">

                                        <span class="left grey-text text-darken-1">
                                            <b><?php echo "({$Product['Cantidad']}) {$Product['Nombre']}"; ?></b>
                                        </span>

                                        <span class="right grey-text text-darken-1"> <?php echo "$".$Product['Costo']; ?></span>

                                        <br>

                                    </div>

                                    <!-- ========  ALTER THE VITAL INFO OF THE PRODUCT ============ -->
                                    <div class="collapsible-body">
                                        
                                        <form action="CandyStore.php" method="post" class="row">
                                            
                                            <!-- ========  PRICE  ============ -->
                                            <div class="col s12 left-align">
                                                <span class="grey-text text-darken-3" style="font-size: 1.7rem;">
                                                    <b>$<?php echo $Product['CostoProducto']; ?></b>
                                                </span>
                                            </div>

                                            <!-- =====  SEND THE NUMBER OF THINGS ===== -->
                                            <div class="col s6 m8 l8">
                                                <div class='input-field'>
                                                    <input 
                                                        class = 'validate'
                                                        type  = 'number'
                                                        name  = '<?php echo $Product['ID']."QuantityProduct"; ?>' 
                                                        id    = '<?php echo $Product['ID']."QuantityProduct"; ?>'
                                                        min   = '1'
                                                        max   = '<?php echo ($Product['Stock'] + $Product['Cantidad']);?>'
                                                        value = '<?php echo $Product['Cantidad'];?>'
                                                    />
                                                    <label>Cantidad</label>
                                                </div>
                                            </div>

                                            <!-- =====  INITIAL STATE ===== -->
                                            <input 
                                                type  = "hidden" 
                                                id    = "<?php echo $Product['ID']."OriginalQuantity"; ?>"
                                                name  = "<?php echo $Product['ID']."OriginalQuantity"; ?>"
                                                value = '<?php echo $Product['Cantidad'];?>'
                                                >

                                            <!-- =====  SEND THE NAME OF THINGS ===== -->
                                            <input 
                                                type  = "hidden"
                                                name  = "<?php echo $Product['ID']."ProductName"; ?>"
                                                id    = "<?php echo $Product['ID']."ProductName"; ?>"
                                                value = "<?php echo $Product['Nombre']; ?>">

                                            <!-- =====  BUTTON TO SEND THE INFO ===== -->
                                            <br>
                                            <div class="col s2 l4">

                                                <div class="row">
                                                    <button 
                                                        class = "btn-flat waves-effect waves-light green lighten-1 white-text"
                                                        type  = "submit"
                                                        id    = '<?php echo $Product['ID']."ButtonChangeShoppingCar"; ?>'
                                                        name  = '<?php echo $Product['ID']."ButtonChangeShoppingCar"; ?>'>
                                                        Cambiar
                                                    </button>
                                                </div>

                                                <div class="row">
                                                    <button 
                                                        class = "btn-flat waves-effect waves-light red lighten-1 white-text"
                                                        type  = "submit"
                                                        id    = '<?php echo $Product['ID']."ButtonDeleteProduct"; ?>'
                                                        name  = '<?php echo $Product['ID']."ButtonDeleteProduct"; ?>'>
                                                        Eliminar
                                                    </button>
                                                </div>

                                            </div>
                                                
                                            <!-- =====  HOW MUCH IN STOCK ===== -->
                                            <div class="col s12 left-align">
                                                <span class="grey-text text-darken-2" style="font-size: 0.8rem;">
                                                    Disponibles para vender más: <?php echo $Product['Stock']; ?> <br>
                                                    Disponibles en Total: <?php echo ($Product['Stock'] + $Product['Cantidad']); ?> <br>
                                                </span>
                                            </div>

                                        </form>

                                    </div>

                                </li>

                            <?php endwhile;?>
                        </ul>

                        <br><br>

                        <!-- ========  CLOSE AN OK SALE ================ -->
                        <form class="row" action="CandyStore.php" method="post">
                    
                            <!-- ========  BUTTON TO SEND ===== -->
                            <button
                                type  = 'submit'
                                id    = 'FinalizeSuccessfulSell'
                                name  = 'FinalizeSuccessfulSell'
                                class = 'col s8 offset-s2 l6 offset-l3 btn-large waves-effect green lighten-1'>
                                Cobrar $<?php echo $_SESSION['TotalSellCandyShop'];?>
                            </button>
                        </form>

                        <!-- ========  CLOSE BAD SALE ================ -->
                        <form class="row" action="CandyStore.php" method="post">
                    
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
                            required
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
                        class = 'col s8 btn-large waves-effect blue lighten-1'>
                        Buscar Producto
                    </button>

                </form>

                <!-- ========  LIST OF POSSIBLE PRODUCTS  =============== -->
                <?php if (isset($_POST['SearchForProduct'])) :?>
                    <br><br>
                    
                    <div class="<?php if (!WeAreAtMobile()) echo "container"; ?>">
                    <ul class="collapsible" data-collapsible="accordion">
                    
                        <!-- ========  FOR EACH POSSIBLE PRODUCT ================ -->
                        <?php while ($Product = $QueryPossibleProducts->fetch_assoc()) : ?>
                        <li>
                            
                            <!-- ========  NUMBER AN ICON OF PRODUCT ============ -->
                            <div class="collapsible-header">

                                <i class="material-icons">local_dining</i>
                                
                                <span class="grey-text text-darken-3 left-align" style="font-size: 1.1rem;">
                                    <?php echo $Product['Nombre']; ?>
                                </span>

                            </div>

                            <!-- ========  INFO OF PRODUCT ============ -->
                            <div class="collapsible-body">

                                <!-- ========  A ROW TO STYLE ALL A FORM TO SEND INFO ======== -->
                                <form action="CandyStore.php" method="post" class="row">
                                    
                                    <!-- ========  PRICE  ============ -->
                                    <div class="col s12 left-align">
                                        <span class="grey-text text-darken-3" style="font-size: 1.7rem;">
                                            <b>$<?php echo $Product['Costo']; ?></b>
                                        </span>
                                    </div>

                                    <!-- =====  SEND THE NUMBER OF THINGS ===== -->
                                    <div class="col s6 m8 l8">
                                        <div class='input-field'>
                                            <input 
                                                class = 'validate'
                                                type  = 'number'
                                                name  = '<?php echo $Product['ID']."QuantityProduct"; ?>' 
                                                id    = '<?php echo $Product['ID']."QuantityProduct"; ?>'
                                                min   = '0'
                                                value = 1
                                                max   = '<?php echo $Product['Stock'];?>'
                                            />
                                            <label>Cantidad</label>
                                        </div>
                                    </div>

                                    <!-- =====  SEND THE NAME OF THINGS ===== -->
                                    <input 
                                        type  = "hidden"
                                        name  = "<?php echo $Product['ID']."ProductName"; ?>"
                                        id    = "<?php echo $Product['ID']."ProductName"; ?>"
                                        value = "<?php echo $Product['Nombre']; ?>">

                                    <!-- =====  BUTTON TO SEND THE INFO ===== -->
                                    <div class="col s2 l4">

                                        <br>
                                        <button 
                                            <?php if ($Product['Stock'] == 0) echo "disabled"; ?>
                                            class = "btn-flat waves-effect waves-light light-green white-text"
                                            type  = "submit"
                                            id    = '<?php echo $Product['ID']."ButtonChangeShoppingCar"; ?>'
                                            name  = '<?php echo $Product['ID']."ButtonChangeShoppingCar"; ?>'>
                                            Agregar
                                        </button>
        
                                    </div>
                                        
                                    <!-- =====  HOW MUCH IN STOCK ===== -->
                                    <div class="col s12 left-align">
                                        <span class="grey-text text-darken-2" style="font-size: 0.8rem;">
                                            Disponibles para vender más: <?php echo $Product['Stock']; ?>
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


        <!-- ================================================================== -->    
        <!-- =====================      CREATE  STUFF        ================== -->      
        <!-- ================================================================== -->
        <?php if ($_SESSION['IAmAManager']): ?>    
        <div class="<?php echo $StandardGreyCard;?>">

            <!-- =================   CARD CONTENT  ================ -->      
            <div id="CardCreateProduct" name="CardCreateProduct" class="card-content">

                <!-- ========  TITLE  ================ -->
                <h4 class="grey-text text-darken-2"><b>Creación</b> de Productos</h4>
            
                <!-- ========  TEXT  ================ -->
                <span class="grey-text" style="font-size: 1.15rem;">
                    Crea un nuevo producto para ser desplegado ahora en la dulcería
                    <br><br>
                </span>

                <!-- ========  MATERIAL FORM  ================ -->
                <form class="container" action="CandyStore.php" method="post">

                    <!-- ========  EMPLOYEERS ID ============= -->
                    <div class='input-field'>
                        <input required class='validate' type='text' name='NewProductName' id='NewProductName' />
                        <label>Nombre del Producto</label>
                    </div>

                    <!-- ========  EMPLOYEERS ID ============= -->
                    <div class='input-field'>
                        <input required class='validate' type='number' name='NewProductCost' id='NewProductCost' />
                        <label>Costo Unitario</label>
                    </div>

                    <!-- ========  EMPLOYEERS ID ============= -->
                    <div class='input-field'>
                        <input required class='validate' type='number' name='NewProductStock' id='NewProductStock' />
                        <label>Stock Actual</label>
                    </div>

                    <br>

                    <!-- ========  BUTTON TO SEND ===== -->
                    <button 
                        type='submit'
                        name='AddNewProduct'
                        class='<?php echo $StandardButton;?> btn-large green lighten-1'>
                        Añadir Producto
                    </button>

                </form>

                </form>


            </div>

        </div>
            
        <br><br><br>

        <div class="<?php echo $StandardGreyCard;?>">

            <!-- =================   CARD CONTENT  ================ -->      
            <div id="CardDeleteProduct" name="CardDeleteProduct" class="card-content">

                <!-- ========  TITLE  ================ -->
                <h4 class="grey-text text-darken-2"><b>Elimina</b> un Producto</h4>
            
                <!-- ========  TEXT  ================ -->
                <span class="grey-text" style="font-size: 1.15rem;">
                    Elimina un producto de la dulcería
                    <br><br>
                </span>

                <!-- ========  MATERIAL FORM  ================ -->
                <form class="container" action="CandyStore.php" method="post">

                    <!-- ========  EMPLOYEERS ID ============= -->
                    <div class="input-field">                        
                        <select required name="SelectedProduct" id="SelectedProduct" class="left-align">
                            <?php while ($Product = $QueryProductsData->fetch_assoc()) :?>
                                <option value="<?php echo $Product['ID']; ?>"><?php echo $Product['Nombre']; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <label>Nombre del Producto</label>
                    </div>

                    <br>

                    <!-- ========  BUTTON TO SEND ===== -->
                    <button 
                        type='submit'
                        name='DeleteProduct'
                        class='<?php echo $StandardButton;?> btn-large red lighten-1'>
                        Eliminar Producto
                    </button>

                </form>

                </form>


            </div>

        </div>

        <br><br><br>

        <?php endif; ?>




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
                   class   = "btn-floating blue">
                    <i class="unselectable material-icons">search</i>
                </a>
            </li>


            <?php if (isset($_SESSION['IAmAManager'])): ?>
            <!-- =======  TO SEE EMPLOYEES INFO  ========== -->    
            <li>
                <a href    = "#CardCreateProduct"
                   onclick = "$('.fixed-action-btn').closeFAB();" 
                   class   = "btn-floating green">
                    <i class="unselectable material-icons">add</i>
                </a>
            </li>

            <!-- =======  TO SEE EMPLOYEES INFO  ========== -->    
            <li>
                <a href    = "#CardDeleteProduct"
                   onclick = "$('.fixed-action-btn').closeFAB();" 
                   class   = "btn-floating red">
                    <i class="unselectable material-icons">delete</i>
                </a>
            </li>
            <?php endif; ?>

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