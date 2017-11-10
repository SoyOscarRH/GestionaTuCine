<?php
    /*=======================================================================================================================
    ============================================       ERROR  PROMPT           ==============================================
    =========================================================================================================================

    THIS IS THE GENERAL PAGE */
    include("/Applications/XAMPP/xamppfiles/htdocs/ManageYourCinema/Web/PHP/ForAllPages.php");  //Dame todas las ventajas que tiene incluir

    // ================ VARIABLES =============================
    $HTMLTitle  = 'Error Horrible';                                                             //Titulo de cada Pagina
    $UpdateDate = '23 de Julio del 2017';                                                       //Fecha de actualizacion de la pagina


    if (!isset($TitleErrorPage)) $TitleErrorPage = "A ocurrido un error desconocido";           //Error variables
    if (!isset($MessageErrorPage)) $MessageErrorPage = "Lamentamos las molestias :(";           //Error variables
    if (!isset($ButtonLinkErrorPage)) $ButtonLinkErrorPage = $HTMLDocumentRoot;                 //Error variables
    if (!isset($ButtonTextErrorPage)) $ButtonTextErrorPage = "Regresa a Página Principal";      //Error variables
    
    if (isset($NewHTMLTitle)) $HTMLTitle = $NewHTMLTitle;                                       //Error variables


    include($PHPDocumentRoot."PHP/HTMLHeader.php");                                             //Incluimos un Asombroso Encabezado
?>

<br>
<br>

    <div class="container center-align row">

        <div class="card-panel grey lighten-4 col s12 m8 l8 offset-m2 offset-l2">

            <form action="<?php echo $ButtonLinkErrorPage; ?> " method="post">
            
                <h4 class="grey-text text-darken-2"><br> <?php echo $TitleErrorPage; ?></h4>

                <span class="flow-text grey-text">
                    <?php echo $MessageErrorPage; ?>
                    <br>
                </span>

                <br><br>

                <div class='row'>
                    <button 
                        type='submit'
                        name='CheckDataToEnterSystem'
                        class='col s10 m6 l6 offset-s1 offset-m3 offset-l3 btn btn-large waves-effect indigo lighten-1'>
                        <?php echo $ButtonTextErrorPage; ?>
                    </button>
                </div>

                <br />

            </form>

        </div>

        <br><br><br><br><br>
        <br><br><br><br><br>
        <br><br><br><br><br>
        <br><br><br><br><br>
        <br><br><br><br><br>

    </div>

<?php include($PHPDocumentRoot."PHP/HTMLFooter.php"); ?>

