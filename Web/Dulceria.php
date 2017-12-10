
<?php
include("PHP/ForAllPages.php");   

    $HTMLTitle  = 'Ventas Dulceria';                                                           
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
    if ($res['RolActual']!='Dulceria' and $res['ID']!=$res['IDGerente'])
    {
        $NewHTMLTitle        = "Error con Permisos";                                            
        $TitleErrorPage      = "Error con Permisos";                                            
        $MessageErrorPage    = "No estas designado para Dulceria";                             
        $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   
        $ButtonTextErrorPage = "Accede al Sistema";                                             
        include("Error.php");                                                                   
        exit();
    }                                                                       
    if (isset($_POST['Venta']))
    {       
        $_SESSION['nueva']=1;                                           
        $_SESSION['NewSale'] = true;                                           
     header('Location: CandyStore.php');           
    }

  include("PHP/HTMLHeader.php");     
?>


<div class="container center-align row">

        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">

            <form action="Dulceria.php" method="post">
                
                <h4 class="grey-text text-darken-2"><br>Dulceria Bienvenido!!</h4>

                <span class="grey-text">
                    Comencemos 
                    <br><br>
                </span>
                
               <div class='row'>
                    <button 
                        type='submit'
                        name='Venta'
                        class='col s10 m6 l6 offset-s1 offset-m3 offset-l3 btn btn-large waves-effect indigo lighten-1'>
                        Iniciar Venta 
                    </button>
                </div>


                <br />


                <br />

            </form>
        </div>
    </div>
