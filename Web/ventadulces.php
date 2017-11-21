<?php
ob_start() ;
include("PHP/ForAllPages.php");   



    
    $HTMLTitle  = 'Area De Dulceria';                                                           
    $UpdateDate = '20 de Noviembre del 2017'; 
 if (empty($_SESSION)) {                                                                     
        $NewHTMLTitle        = "Error con Permisos";                                            
        $TitleErrorPage      = "Error con Permisos";                                            
        $MessageErrorPage    = "No iniciaste sesión en el Sistema";                             
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   
        $ButtonTextErrorPage = "Accede al Sistema";                                             
        include("Error.php");                                                                   
        exit();                                                                                 
    }

///////////////////////////////////////////////////////////////////////////////
////////////////Comprobando que el empleado tenga acceso a la dulceria/////////
///////////////////////////////////////////////////////////////////////////////
    $DataBase = new mysqli("127.0.0.1", "root", "root", "Proyect");                //Abrir una conexión
    if ((mysqli_connect_errno() != 0) or !$DataBase) 
    {                                  //Si hubo problemas
        $TitleErrorPage      = "Error con la BD";                                       //Error variables
        $MessageErrorPage    = "No podemos acceder a la base de datos";                 //Error variables
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Dulceria.php";                           //Error variables
        $ButtonTextErrorPage = "Intenta otra vez";                                      //Error variables
       include("Error.php");                                                           //Llama a la pagina de error
        exit();                                                                         //Adios vaquero
    }

    $QueryResult = $DataBase->query('
     SELECT * FROM Empleado WHERE ID = "'.$_SESSION['ID'].'";');                       //Haz la consulta
     $res=$QueryResult->fetch_assoc();
    if ($res['RolActual']!='Dulceria' and $res['ID']!=$res['IDGerente'])                //si su rol no es dulceria, pero si es gerente continua
    {
        $NewHTMLTitle        = "Error con Permisos";                                            
        $TitleErrorPage      = "Error con Permisos";                                            
        $MessageErrorPage    = "No estas designado para Dulceria";                             
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   
        $ButtonTextErrorPage = "Accede al Sistema";                                             
        include("Error.php");                                                                   
        exit();
    }   
 

        ///////////////////////////Nueva Venta///////////////////////////
    $Query=$_SESSION['Busqueda'];
    if ( $_SESSION['nueva']==1)//si es una venta nueva
    {
        //consulta de productos
        $_SESSION['mensaje']="";
        $Query='select id,stock,Nombre,Costo from productodulceria;';
        $_SESSION['nueva']=0;
        $_SESSION['total']=0;
        $QueryResult = $DataBase->query('select max(ID) from venta;');
        $Row = $QueryResult->fetch_row();
        $_SESSION['idventa']=$Row[0] + 1;
        $insert = 'insert into venta (id,idEmpleado) values(' .$_SESSION['idventa'] . ' , '. $_SESSION['ID'].');';
        mysqli_query($DataBase, $insert);
        //echo "nueva";
    }
         //////////////////////////BOTON BUSCAR////////////////////////
    if (isset($_POST['Buscar']))
    {                                                  
           $Query = 'select id,stock,Nombre,Costo from productodulceria WHERE Nombre like "%'.$_POST['Busqueda'].'%";';
           $_SESSION['nueva']=0;
    }
     $Query2='select t.idproducto,p.Nombre,t.Costo,t.cantidad from productodulceria p, ticketdulceria t where p.id=t.idproducto and idventa='.$_SESSION['idventa'].';';
  include("PHP/HTMLHeader.php");     
?>


<div class="container center-align row">

        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">

            <form action="ventadulces.php" method="post">
                
                <h4 class="grey-text text-darken-2"><br>Venta <br> </h4>

                <span class="grey-text">
                    <?php 
                    echo "IDVenta= ".$_SESSION['idventa']."<br>Total = $".  $_SESSION['total'] ."<br>";
                    echo $_SESSION['mensaje'];
                    ?>
                    <br><br>
                </span>
                <div class='row'>
                    <div class='input-field col s10 m8 l8 offset-s1 offset-m2 offset-l2'>
                        <input class='validate' type='text' name='Busqueda' id='Busqueda' />
                        <label for='Busqueda'>Buscar producto</label>
                    </div>
                </div>
                <button
                        type='submit'
                        name='Buscar'
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
                if ($QueryResult = $DataBase->query($Query)) 
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
                $queryConsulta='select count(*) from ticketdulceria WHERE idProducto= '. $ID[$i] .' and idventa = '. $_SESSION['idventa'].';';

                 $QueryResult = $DataBase->query( $queryConsulta);//buscamos si la venta es existenete
                $Row = $QueryResult->fetch_row();
                $Numero=$Row[0];
                if ($Numero == 0)   //si no existe esta venta                                                         
                {
                    $QueryVenta= ' insert into ticketdulceria values('. $Costo .',' . $Cantidad . ' , ' .$_SESSION['idventa'] . ' , ' .$ID[$i] .');';//insertamos en ticket venta
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
                 $QueryResult = $DataBase->query('select Costo,cantidad from ticketdulceria where idProducto = '.$IDventa[$i].' and idventa ='. $_SESSION['idventa']. ';' );//obtenemos el costo del producto
                 $Row = $QueryResult->fetch_row();                                                              
                $Costo=$Row[0];  
                 $Cantidad=$Row[1];                                                                                  
                $QueryDelete= ' delete from  ticketdulceria where idProducto = '.$IDventa[$i].' and idventa ='. $_SESSION['idventa']. ';';//insertamos en ticket venta
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
           $QueryFin = 'UPDATE venta SET fecha="'.$hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].'", total = '.$_SESSION['total'].' WHERE id= '. $_SESSION['idventa'].';';
           $DataBase->query($QueryFin);
           $_SESSION['nueva']=1;
            header('Location: Dulceria.php'); 
        }
        ///////////////////////////BOTON CANCELAR///////////////////////////////
        if (isset($_POST['Cancelar']))
        {                                          
           $QueryFin = 'DELETE from venta where id='. $_SESSION['idventa'].';';
           $DataBase->query($QueryFin);
           $_SESSION['nueva']=1;
           header('Location: Dulceria.php');
        }
        $_SESSION['Busqueda']=$Query;
         /* close connection */

        $DataBase->close(); 
     ob_end_flush();  

?>