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
    if (empty($_SESSION['CurrentSaleID'])) {

        $_SESSION['mensaje']="";
        $_SESSION['nueva']=0;
        $_SESSION['total']=0;

        $QueryResult = $DataBase->query('SELECT MAX(ID) FROM Venta');                           //Dame el ID Maximo de Venta
        $Row = $QueryResult->fetch_row();                                                       //Ahora dame todo lo que salio

        $_SESSION['CurrentSaleID'] = $Row[0] + 1;                                               //Ahora guardalo y aumenta uno

        $QueryResult = $DataBase->query("
                            INSERT INTO Venta (ID, idEmpleado)
                                VALUES ({$_SESSION['CurrentSaleID']}, {$_SESSION['ID']})");     //Ahora crea uno más
        
        if (!$QueryResult) array_push($AlertMessages, "Error al Crear Venta");                  //Vemos que todo ok
    }

    //=============  IF YOU WANT TO MODIFY THE DATA  =============
    if (isset($_POST['SearchForProduct'])) {                                                    //Si es que quieres actualizar datos

        $SearchProduct = ClearSQLInyection($_POST['SearchProduct']);                            //Dame la info

        $QueryForProduct = 'SELECT ID, Stock, Nombre, Costo
                                FROM ProductoDulceria
                                WHERE
                                    Nombre LIKE "%'.$SearchProduct.'%"';                        //Buscamos los productos

        $_SESSION['NewSale'] = false;                                                           //Ahora ya no eresuna nueva venta
        $_SESSION['nueva']=0;
    }











     $Query2='select t.idproducto,p.Nombre,t.Costo,t.cantidad from productodulceria p, ticketdulceria t where p.id=t.idproducto and IDVenta='.$_SESSION['IDVenta'].';';





    // *****************************************************************************************
    // *************************     PROCESS TO START THE SYSTEM   *****************************
    // *****************************************************************************************
    $StandardGreyCard = "card grey lighten-4 col s12 m8 l8 offset-m2 offset-l2";                //Es una forma de que sea mas sencilla 


    include("PHP/HTMLHeader.php");                                                              //Incluimos un Asombroso Encabezado
?>


    <br><br>
    <div class="container center-align">


        <!-- ================================================================== -->    
        <!-- =====================    SELL INFO           ===================== -->      
        <!-- ================================================================== -->    
        <div class="<?php echo $StandardGreyCard;?>">

            <div class="card-image">
                <a id="Edit" name="Edit" class="btn-floating halfway-fab waves-effect waves-light btn-large indigo">
                    <i class="material-icons">info</i>
                </a>
            </div>

            <div class="card-content">

                <!-- ========  TITLE  ================ -->
                <h4 class="grey-text text-darken-2"><b>Detalles</b> de la Venta</h4>
            
                <!-- ========  TEXT  ================ -->
                <span class="grey-text" style="font-size: 1.25rem;">
                    Aquí se encuentra la información de la Venta Actual
                    <br><br>

                    

                </span>

                <!-- ========  MATERIAL FORM  ================ -->
                <form class="container" action="CandyStore.php" method="post">

                    <!-- ========  BUTTON TO SEND ===== -->
                    <button
                        type  = 'submit'
                        id    = 'SearchForProduct'
                        name  = 'SearchForProduct'
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

            <div class="card-image">
                <a id="Edit" name="Edit" class="btn-floating halfway-fab waves-effect waves-light btn-large indigo">
                    <i class="material-icons">search</i>
                </a>
            </div>

            <div class="card-content">

                <!-- ========  TITLE  ================ -->
                <h4 class="grey-text text-darken-2"><b>Busqueda</b> de Productos</h4>
            
                <!-- ========  TEXT  ================ -->
                <span class="grey-text" style="font-size: 1.25rem;">
                    Busca el nombre de lo que quieras vender :D
                    <br><br>
                </span>

                <!-- ========  MATERIAL FORM  ================ -->
                <form class="container" action="CandyStore.php" method="post">

                    <!-- ========  NAME ============= -->
                    <div class='input-field center-align'>
                        <i class="material-icons grey-text text-darken-2 prefix">search</i>
                        <input
                            class = 'validate'
                            type  = 'text'
                            id    = 'ProductName'
                            name  = 'ProductName'/>
                        <label>Busca Productos</label>
                    </div>

                    <br>

                    <!-- ========  BUTTON TO SEND ===== -->
                    <button
                        type  = 'submit'
                        id    = 'SearchForProduct'
                        name  = 'SearchForProduct'
                        class = 'col btn-large waves-effect indigo lighten-1'>
                        Buscar Producto
                    </button>

                </form>

            </div>
            
        </div>



        <br><br><br>

    </div>


<div class="container center-align row">

        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">

            <form action="CandyStore.php" method="post">
                
                <h4 class="grey-text text-darken-2"><br>Venta <br> </h4>

                <span class="grey-text">
                    <?php 
                    echo "IDVenta= ".$_SESSION['IDVenta']."<br>Total = $".  $_SESSION['total'] ."<br>";
                    echo $_SESSION['mensaje'];
                    ?>
                    <br><br>
                </span>
                <div class='row'>
                    <div class='input-field col s10 m8 l8 offset-s1 offset-m2 offset-l2'>
                        <input class='validate' type='text' id='SearchProduct' name='SearchProduct' />
                        <label for='Busqueda'>Busca un Producto</label>
                    </div>
                </div>
                <button
                        type='submit'
                        id='SearchForProduct'
                        name='SearchForProduct'
                        class='col s10 m6 l6 offset-s1 offset-m3 offset-l3 btn btn-large waves-effect indigo lighten-1'>
                        Buscar
                </button>








               <table class="centered hoverable striped responsive-table">
                <thead>
                    <tr>
                          <th>ID</th>
                          <th>Stock</th>
                          <th>Nombre</th>
                          <th>Costo</th>
                          <th>Cantidad</th>
                          <th>Agregar</th>                           
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($QueryResult = $DataBase->query($QueryForProduct)) 
                {
                    $i=1;
                    while ($Row = $QueryResult->fetch_row()) : ?>
                    <tr>
                    
                    <?php foreach ($Row as $Number => $Value): ?>
                        
                        <td><?php echo $Value; ?></td>
                        
                    <?php endforeach; ?>
                        <td>
                            <input
                            type='text'
                            <?php  echo "name='Cantidad".$Row[0]."'";?>
                            >
                            </input>
                        </td>
                        <td>
                            <button
                            type='submit'
                            <?php  echo "name='Agregar".$Row[0]."'";?>
                            >
                            Agregar
                            </button>
                        </td>
                    </tr>
                    
                    <?php 
                    $ID[$i]=$Row[0];
                    $i++;
                    endwhile;
                    $QueryResult->close();
                }
                ?>
                </tbody>
            </table>

            Vendido
            <table class="centered hoverable striped responsive-table">
                <thead>
                    <tr>
                          <th>ID</th>
                          <th>Nombre</th>
                          <th>Costo</th>
                          <th>Cantidad</th>
                          <th>Eliminar</th>                           
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($QueryResult2 = $DataBase->query($Query2)) 
                {
                    $IDventa[0]=1;//bandera para saber si existieron compras
                    $i=1;
                    while ($Row = $QueryResult2->fetch_row()) : ?>
                    <tr>
                    
                    <?php foreach ($Row as $Number => $Value): ?>
                        
                        <td><?php echo $Value; ?></td>
                        
                    <?php endforeach; ?>
                        <td>
                            <button
                            type='submit'
                            <?php  echo "name='Eliminar".$Row[0]."'";?>
                            >
                            Eliminar
                            </button>
                        </td>
                    </tr>
                    
                    <?php 
                    $IDventa[$i]=$Row[0];
                    $i++;
                    endwhile;
                    $QueryResult2->close();
                    if($i==1)//no existieron compras
                    {
                        $IDventa[0]=0;
                        echo "Ninguno";
                    }
                }
                ?>
                </tbody>
            </table>
                <button
                        type='submit'
                        name='Cancelar'
                        class='col s10 m6 l6 offset-s1 offset-m3 offset-l3 btn btn-large waves-effect indigo lighten-1'>
                        cancelar
                </button>
                <button
                        type='submit'
                        name='Finalizar'
                        class='col s10 m6 l6 offset-s1 offset-m3 offset-l3 btn btn-large waves-effect indigo lighten-1'>
                        Finalizar Venta
                </button>

                <br />


                <br />

            </form>
        </div>
    </div>

<?php 
    ////////////////////////////////////////////////////////////////////
    //////////////////////////botones///////////////////////////////////
    /////////////////////////////////////////////////////////////////////
        
        ////////////////////////////BOTONONES AGREGAR////////////////////////////////////////
    
        for ($i=1; $i <= count($ID); $i++) 
        { 
            if (isset($_POST['Agregar'.$ID[$i]]))                                               //al presionar un boton de agregar
            {
                $Cantidad=$_POST['Cantidad'.$ID[$i]];//obtenemos la cantidad vendida    

                 $QueryResult = $DataBase->query('select Costo from productodulceria WHERE id='. $ID[$i] .';' );//obtenemos el costo del producto
                 $Row = $QueryResult->fetch_row();                                                              
                $Costo=$Row[0];
                $QueryResult2->close();
                //comprobamos que no se hay vendido ya de este producto
                $queryConsulta='select count(*) from ticketdulceria WHERE idProducto= '. $ID[$i] .' and IDVenta = '. $_SESSION['IDVenta'].';';

                 $QueryResult = $DataBase->query( $queryConsulta);//buscamos si la venta es existenete
                $Row = $QueryResult->fetch_row();
                $Numero=$Row[0];
                if ($Numero == 0)   //si no existe esta venta                                                         
                {
                    $QueryVenta= ' insert into ticketdulceria values('. $Costo .',' . $Cantidad . ' , ' .$_SESSION['IDVenta'] . ' , ' .$ID[$i] .');';//insertamos en ticket venta
                     $DataBase->query($QueryVenta);
                     $_SESSION['total']=$_SESSION['total']+($Cantidad*$Costo);//aumentamos el valor del total 
                     $_SESSION['mensaje']="venta realizada";                                                                      
                } 
                else   
                {
                    $_SESSION['mensaje']="Ya lo Usaste";
                }

            $_SESSION['nueva']=0; 
            header('Location: extra.php'); 
            } 
                
        }
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