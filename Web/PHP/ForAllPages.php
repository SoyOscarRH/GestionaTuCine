<?php

    /*=====================================================================================================================================
    ============================================                SYSTEM CONSTANT           =================================================
    =======================================================================================================================================

    HERE WE HAVE A LOT OF CONSTANT FOR THE SYSTEM */

    // ********************************************************************
    // *******************   HEADERS PARA HTML   **************************
    // ********************************************************************
        header('Content-type: text/html; charset=UTF-8');                                   //Obliga a recargar la pagina con UFT8
        header("Content-Type: text/html;charset=utf_8");                                    //Obliga a recargar la pagina con UFT8
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");                                   //Obliga a recargar la pagina
        header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");                           //Obliga a recargar la pagina
        header("Cache-control: no-cache, must-revalidate");                                 //Obliga a recargar la pagina
        header("Pragma: no-cache");                                                         //Obliga a recargar la pagina
        ini_set("display_errors","On");                                                     //Obliga a recargar la pagina
        date_default_timezone_set ('America/Mexico_City');                                  //Da la zona horaria
        setlocale(LC_TIME, 'es_ES.UTF-8');                                                  //Pone un sistema local


    // ********************************************************************
    // *******************      LIBRERIAS EXTERNAS   **********************
    // ********************************************************************
        include_once("Functions.php");                                                      //Aqui se encuentran las fn en general
        //include_once("DataBases.php");                                                    //Aqui se encuentran las fn de conexion
    







    // *****************************************************************************************************
    // *********************************             VARIABLES          ************************************
    // *****************************************************************************************************

        // === GENERAL VARIABLES  =====
            $Title = 'Maneja tu Cine';
            $HTMLTitle = $Title;                                                                //Titulo de cada Pagina
            $UpdateDate = '23 de Julio del 2017';                                               //Fecha de actualizacion de la pagina

            $PHPDocumentRoot = "/Applications/XAMPP/xamppfiles/htdocs/ManageYourCinema/Web/";
            $HTMLDocumentRoot = "/ManageYourCinema/Web/";

            $WeShouldAddOriginalLinks = true;




        // === DEFAULT LINKS ===== 
            $LinksForPages = array(); 
            $LinksForPages["Iniciar Sesión"] = "Login.php";   
            $LinksForPages["Cerrar Sesión"]  = "adios";   
            $LinksForPages["Opciones"]       = "Vacio";   


?>