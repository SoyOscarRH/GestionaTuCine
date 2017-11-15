<?php

    /*=====================================================================================================================================
    ============================================                SYSTEM CONSTANT           =================================================
    =======================================================================================================================================

    HERE WE HAVE A LOT OF CONSTANT FOR THE SYSTEM */

    // ********************************************************************
    // *******************   HEADERS PARA HTML   **************************
    // ********************************************************************
        header('Content-type: text/html; charset=UTF-8');                                       //Obliga a recargar la pagina con UFT8
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");                                       //Obliga a recargar la pagina
        header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");                               //Obliga a recargar la pagina
        header("Cache-control: no-cache, must-revalidate");                                     //Obliga a recargar la pagina
        header("Pragma: no-cache");                                                             //Obliga a recargar la pagina
        ini_set("display_errors","On");                                                         //Obliga a recargar la pagina
        date_default_timezone_set ('America/Mexico_City');                                      //Da la zona horaria
        setlocale(LC_TIME, 'es_ES.UTF-8');                                                      //Pone un sistema local


    // ********************************************************************
    // *******************    LIBRARIES              **********************
    // ********************************************************************
        include_once("Functions.php");                                                          //Aqui se encuentran las fn en general
        include_once("DataBaseFunctions.php");                                                  //Aqui se encuentran las fn de conexion
    



    // *****************************************************************************************************
    // *********************************             VARIABLES          ************************************
    // *****************************************************************************************************

        // === GENERAL VARIABLES  =====
            $Title = 'Maneja tu Cine';
            $HTMLTitle = $Title;                                                                //Titulo de cada Pagina
            $UpdateDate = '23 de Julio del 2017';                                               //Fecha de actualizacion de la pagina

            $HTMLDocumentRoot = "/ManageYourCinema/Web/";                                       //Ruta de los archivos de HTML
            $PageDirection = "localhost";                                                       //Ruta de los archivos de HTML

            $WeShouldAddOriginalLinks = true;                                                   //¿Dbemos cambiarlos?


        // === DEFAULT LINKS ===== 
            $LinksForPages = array();                                                           //Guarda los links
            $LinksForPages["Iniciar Sesión"] = "Login.php";                                     //Iniciar Sesion
            $LinksForPages["Administrador"]  = "AdminAccounts.php";                             //Herramientas de Administrador



    // *****************************************************************************************************
    // *********************************             IMPORTANT STUFF          ******************************
    // *****************************************************************************************************

        // === DEFAULT LINKS ===== 
            if(!isset($_SESSION)) session_start();                                              //Si es que ya iniciamos sesion









?>