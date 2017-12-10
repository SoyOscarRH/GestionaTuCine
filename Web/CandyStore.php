<?php
    /*=======================================================================================================================
    ============================================         LOGIN PROMPT          ==============================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE */
    include("PHP/ForAllPages.php");                                                             //Dame todas las ventajas que tiene incluir

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Area De Dulcería';                                                           //Titulo de cada Pagina
    $UpdateDate = '23 de Noviembre del 2017';                                                   //Fecha de actualizacion de la pagina


    // ========== SPECIFIC FOR THIS SCRIPT ==========
    $AlertMessages = array();                                                                   //Mensajes que mostramos 

    StandardCheckForStartedSession();                                                           //Asegurate de que pueda estar aqui
    $DataBase = StandardCheckForCorrectDataBase();                                              //Asegurate de que pueda estar aqui
    StandardUpdateSessionData($_SESSION['ID'], $DataBase);                                      //Asegurate que tdo este al dia


    ob_start();

    //=============  ADVANCE CHECK FOR VISITING THIS PAGE  =============
    if ($_SESSION['RolActual'] != 'Dulceria' AND !$_SESSION['IAmAManager'])                     //Solos los empleados entran
        CallErrorPagePermissions("No estas designado para Dulcería");                           //Sino error :o

    print_r($_SESSION);

    
    /*===================================================================
    ============         GET THE DATABASE      ==========================
    ===================================================================*/
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

    //=============  ADD SOMETHING TO THE SHOPING BOX =============
    foreach ($_POST as $Name => $Value) {                                                       //Buscar el POST[] correcto
        if (fnmatch("*QuantityProduct", $Name)) {                                               //Se tiene que parecer a esto
            do {                                                                                //Bien, vamos a intentar que todo ok
                //=============  GET THE DATA  =============
                $ProductID = str_replace("QuantityProduct", "", $Name); 
                $QuantityProduct = ClearSQLInyection($_POST[$ProductID.'QuantityProduct']);     //Por si las dudas
                $ProductName = ClearSQLInyection($_POST[$ProductID.'ProductName']);             //Por si las dudas

                
                //=============  FIND PRODUCT DETAILS ======
                $TemporalQueryResult = $DataBase->query("
                        SELECT Costo FROM ProductoDulceria 
                            WHERE ID = {$ProductID}");                                          //Obtenemos el costo del producto

                if (!$TemporalQueryResult)                                                      //Si es que hubo un problema
                    {array_push($AlertMessages, "Error con la Base de Datos"); break;}          //Error Misterioso

                $Row = $TemporalQueryResult->fetch_row();                                       //Dame todos producto que hagan eso
                $ProductTotalCost = $Row[0] * $QuantityProduct;                                 //Seguro que solo hay uno
                $TemporalQueryResult->close();                                                  //Adios al Query

                //=============  THIS PRODUCT WAS ALREADY IN THE LIST ======
                $TemporalQueryResult = $DataBase->query("
                        SELECT Costo FROM TicketDulceria 
                            WHERE 
                                IDProducto = {$ProductID} AND 
                                IDVenta = {$_SESSION['CurrentSaleID']}");                       //Obtenemos el precio anterior

                if (!$TemporalQueryResult)                                                      //Si es que hubo un problema
                {array_push($AlertMessages, "Error con la Base de Datos"); break;}              //Error Misterioso

                //=============  ADD TO SHOPPING LIST ======
                if ($TemporalQueryResult->num_rows == 0) {                                      //Si es que no tenias esto
                    
                    $TemporalQueryResult = $DataBase->query("
                        INSERT INTO TicketDulceria
                            VALUES (
                                {$ProductTotalCost},
                                {$QuantityProduct},
                                {$_SESSION['CurrentSaleID']},
                                {$ProductID}
                            )");                                                                //Creo nuevo registro del producto

                    if (!$TemporalQueryResult)                                                  //Si es que hubo un problema
                        {array_push($AlertMessages, "Error con la Base de Datos"); break;}      //Error Misterioso
                    
                    $_SESSION['TotalSell'] += $ProductTotalCost;                                //Añado al total
                    array_push($AlertMessages, "Producto Añadido al Carrito");                  //Muestro lindo Mensajito
                }
                //=============  UPDATE THE SHOPPING LIST ======
                else {                                                                          //Si es que ya tenia este producto
                    $Row = $TemporalQueryResult->fetch_row();                                   //Dame el costo anterior
                    $_SESSION['TotalSell'] -= $Row[0];                                          //Lo quito

                    $TemporalQueryResult = $DataBase->query("
                        UPDATE TicketDulceria
                            SET 
                                Costo = {$ProductTotalCost},
                                Cantidad = {$QuantityProduct}
                            WHERE 
                                IDProducto = {$ProductID} AND 
                                IDVenta = {$_SESSION['CurrentSaleID']}");                       //Actualizo datos


                    if (!$TemporalQueryResult)                                                  //Si es que hubo un problema
                        {array_push($AlertMessages, "Error con la Base de Datos"); break;}      //Error Misterioso
                    
                    $_SESSION['TotalSell'] += $ProductTotalCost;                                //Añado al total el nuevo
                    array_push($AlertMessages, "Producto Actualizado en el Carrito");           //Mensajito de Felicidades
                }

                //=============  UPDATE THE SHOPPING STOCK ======
                $TemporalQueryResult = $DataBase->query("
                        UPDATE ProductoDulceria
                            SET Stock = Stock - {$QuantityProduct}
                            WHERE ID = {$ProductID}");                                          //Actualizo datos

                if (!$TemporalQueryResult) array_push($AlertMessages, "Error con el Stock");    //Error Misterioso
                else array_push($AlertMessages, "Stock Actualizado");                           //Error Misterioso

            }
            while (false);


        }

    }





    print_r ($_POST);    














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
            
                <!-- ========  TEXT  ================ -->
                <span class="grey-text" style="font-size: 1.25rem;">
                    Aquí se encuentra la información de la Venta Actual
                    <br><br>

                </span>

                <span class="grey-text darken-2">
                    <b>IDVentas: </b> <?php echo $_SESSION['CurrentSaleID']; ?><br>
                </span>
                    
                <br><br>

                <div class="container">
                
                    <ul class="collection">

                        <?php 
                            $TemporalQueryResult = $DataBase->query("
                            SELECT 
                                ProductoDulceria.Nombre,
                                TicketDulceria.Costo,
                                TicketDulceria.Cantidad
                                FROM 
                                    ProductoDulceria, TicketDulceria
                                WHERE 
                                    ProductoDulceria.ID = TicketDulceria.iDProducto AND
                                    IDventa = {$_SESSION['CurrentSaleID']}");                       //Busco productos


                            if (!$TemporalQueryResult)                                              //Si es que hubo un problema
                                array_push($AlertMessages, "Error con los Productos Vendidos");

                            while ($Row = $TemporalQueryResult->fetch_assoc()) :
                        ?>
                            
                            <li class="collection-item left-align">
                                <div>

                                    <span class="grey-text text-darken-1" <?php if (WeAreAtMobile())echo 'style = "font-size: 0.9rem;"'?>>
                                        <b><?php echo "({$Row['Cantidad']}) {$Row['Nombre']}"; ?></b>
                                    </span>

                                    <span class="secondary-content"> <?php echo "$".$Row['Costo']; ?></span>
                                </div>
                            </li>

                        <?php endwhile;?>
                    </ul>


                    <h5 class="grey-text text-darken-1 left-align"> 
                        Total : <b>$<?php echo $_SESSION['TotalSell'];?></b>
                    </h5>

                </div>


                <br><br>




                <!-- ========  MATERIAL FORM  ================ -->
                <form class="container" action="CandyStore.php" method="post">
            
                    <!-- ========  BUTTON TO SEND ===== -->
                    <button
                        type  = 'submit'
                        id    = 'FinalizeSell'
                        name  = 'FinalizeSell'
                        class = 'col btn-large waves-effect indigo lighten-1'>
                        Finalizar Venta
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
                <span class="grey-text" style="font-size: 1.25rem;">
                    Busca el nombre de lo que quieras vender :D
                    <br><br>
                </span>

                <!-- ========  MATERIAL FORM FOR SEACH  =============== -->
                <form class="container" action="CandyStore.php" method="post">

                    <!-- ========  NAME ============= -->
                    <div class='input-field center-align'>
                        <i class="material-icons grey-text text-darken-2 prefix">search</i>
                        <input
                            class = 'validate'
                            type  = 'text'
                            id    = 'PossibleProductName'
                            name  = 'PossibleProductName'/>
                        <label>Busca Productos</label>
                    </div>

                    <br>

                    <!-- ========  BUTTON TO SEND ===== -->
                    <button
                        type  = 'submit'
                        id    = 'SearchForProduct'
                        name  = 'SearchForProduct'
                        class = 'col btn-large waves-effect green lighten-1'>
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
                                            class = "btn-flat waves-effect waves-light light-green darken-1 white-text"
                                            type  = "submit"
                                            id    = '<?php echo $Row['ID']."Button"; ?>'
                                            name  = '<?php echo $Row['ID']."Button"; ?>'>
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
                foreach ($AlertMessages as $Alert) echo "Materialize.toast('$TitleAlert $Alert', 9000);"; 
            ?>

        });
    </script>




<?php 
    ////////////////////////////////////////////////////////////////////
    //////////////////////////botones///////////////////////////////////
    /////////////////////////////////////////////////////////////////////
        
        ////////////////////////////BOTONONES AGREGAR////////////////////////////////////////
    
        //////////////////////////BOTONES ELIMINAR/////////////////////////////////////////////
        for ($i=1; $i <= count($IDventa)-1 && $IDventa[0]!=0; $i++) //id venta en reliada son los id de productos ya vendidos no el de la entidad venta XD
        { 
            if (isset($_POST['Eliminar'.$IDventa[$i]]))                                               //al presionar un boton de elimnar
            {                                         
                 $QueryResult = $DataBase->query('select Costo,cantidad from ticketdulceria where idProducto = '.$IDventa[$i].' and IDVenta ='. $_SESSION['IDVenta']. ';' );//obtenemos el costo del producto
                 $Row = $QueryResult->fetch_row();                                                              
                $Costo=$Row[0];  
                 $Cantidad=$Row[1];                                                                                  
                $QueryDelete= ' delete from  ticketdulceria where idProducto = '.$IDventa[$i].' and IDVenta ='. $_SESSION['IDVenta']. ';';//insertamos en ticket venta
                 $DataBase->query($QueryDelete);
                 $_SESSION['total']=$_SESSION['total']-($Cantidad*$Costo);                                                                       //aumentamos el valor del total
                $_SESSION['nueva']=0;  
                 header('Location: extra.php');
                 $_SESSION['mensaje']="Eliminado";         
            } 
                
        }
        ///////////////////////////BOTON FINALIZAR///////////////////////////////
        if (isset($_POST['Finalizar']))
        {  
           $hoy = getdate();  //obtenemos la hora y fecha del momento en que se finaliza la venta                                          
           $QueryFin = 'UPDATE venta SET fecha="'.$hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].'", total = '.$_SESSION['total'].' WHERE id= '. $_SESSION['IDVenta'].';';
           $DataBase->query($QueryFin);
           $_SESSION['nueva']=1;
            header('Location: Dulceria.php'); 
        }
        ///////////////////////////BOTON CANCELAR///////////////////////////////
        if (isset($_POST['Cancelar']))
        {                                          
           $QueryFin = 'DELETE from venta where id='. $_SESSION['IDVenta'].';';
           $DataBase->query($QueryFin);
           $_SESSION['nueva']=1;
           header('Location: Dulceria.php');
        }
        $_SESSION['Busqueda']=$Query;
         /* close connection */

        $DataBase->close(); 
     ob_end_flush();  

?>

<?php 
    /*===================================================================
    ============         CLOSE ALL DATABASE     =========================
    ===================================================================*/
    include("PHP/HTMLFooter.php");

    if (isset($DataBase)) $DataBase->close();
?>
