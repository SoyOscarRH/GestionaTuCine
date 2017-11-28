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


    // *****************************************************************************************************
    // *********************************             VARIABLES          ************************************
    // *****************************************************************************************************

        // === GENERAL VARIABLES  =====
            $Title = 'Maneja tu Cine';
            $HTMLTitle = $Title;                                                                //Titulo de cada Pagina
            $UpdateDate = '23 de Julio del 2017';                                               //Fecha de actualizacion de la pagina

            $HTMLDocumentRoot = "/ManageYourCinema/Web/";                                       //Ruta de los archivos de HTML
            $PageDirection = "localhost";                                                       //Ruta de los archivos de HTML

            $LinksForPages = array();                                                           //Guarda los links
            if (session_status() == PHP_SESSION_NONE) session_start();                          //Si es que no has sesión crear una plox




    // ****************************************************************************************************
    // ********************************    LIBRARIES              *****************************************
    // ****************************************************************************************************
        include_once("Functions.php");                                                          //Aqui se encuentran las fn en general
        include_once("DataBaseFunctions.php");                                                  //Aqui se encuentran las fn de conexion
    



    // *****************************************************************************************************
    // *********************************             IMPORTANT STUFF          ******************************
    // *****************************************************************************************************


        // === DEFAULT LINKS ===== 
        if (empty($_SESSION)) {                                                                 //Si es que no has iniciado sesión 
            $LinksForPages["Iniciar Sesión"] = "Login.php";                                     //Iniciar Sesion
        }
        else {
            $LinksForPages = array("Menú de Opciones"=>"Login.php") + $LinksForPages;           //Menu de Sesion
            $LinksForPages["Cerrar Sesion"] = "MenuEmployeeOrManager.php?CloseSession";         //Añadimos el Sesion
        }









?>