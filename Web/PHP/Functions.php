<?php 

        // ==========================================================================================
        // ======================   ARE WE AT A MOBILE SYSTEM      ====================================
        // ==========================================================================================
        function WeAreAtMobile() {                                                                      // === VEAMOS SI ESTAMOS EN MOVIL ===
            $Data = "/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|";                     //Tal vez seas esto
            $Data.= "hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i";         //Tal vez seas esto
            return preg_match($Data, $_SERVER["HTTP_USER_AGENT"]);                                      //Dime ¿va?
        }


        // ==========================================================================================
        // ====================        CLEAR FOR HORRIBLE QUERYS        =============================
        // ==========================================================================================
        function ClearSQLInyection($SomeString) {                                                       //=== LIMPIAMOS UN STRING ===
            $SomeString = htmlspecialchars($SomeString);                                                //Empieza a limpiar

            $Bad = array('INSERT ','DELETE ','SELECT ','UPDATE ','"','=',"'",'<',';',' AND ',' OR ');   //Valores a cambiar
            $Good = array('','','','','','','','','','','');                                            //Valores nuevos
            $SomeString = str_ireplace($Bad, $Good, $SomeString);                                       //Cambia los valore
            return $SomeString;                                                                         //Regresa ahora si el bueno
        }


        // ==========================================================================================
        // ====================       STANDAR ERROR MESSAGES     ====================================
        // ==========================================================================================
        function CallErrorPagePermissions() {                                                           //== NO INICIASTE SESION ==
            global $HTMLDocumentRoot;                                                                   //Eres global :D
            $TitleErrorPage      = "Error Permisos";                                                    //Error variables
            $MessageErrorPage    = "No iniciaste sesión en el Sistema";                                 //Error variables
            $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                       //Error variables
            $ButtonTextErrorPage = "Accede al Sistema";                                                 //Error variables

            include("Error.php");                                                                       //Llama a la pagina de error
            exit();                                                                                     //Adios vaquero
        }

        function CallErrorPageOnlyForAdmins() {                                                         //== NO MIJO, NO PERMISO ==
            global $HTMLDocumentRoot;                                                                   //Eres global :D
            $TitleErrorPage      = "Error Permisos";                                                    //Error variables
            $MessageErrorPage    = "No eres un Administrador por lo tanto no puedes ver esta página";   //Error variables
            $ButtonLinkErrorPage = $HTMLDocumentRoot."MenuEmployeeOrManager.php";                       //Error variables
            $ButtonTextErrorPage = "Volver al Menu";                                                    //Error variables

            include("Error.php");                                                                       //Llama a la pagina de error
            exit();                                                                                     //Adios vaquero
        }

        function CallClosePage() {                                                                      //== YA QUE CARGO AL VER... ==
            global $HTMLDocumentRoot;                                                                   //Eres global :D
            $NewHTMLTitle        = "Cerrar Sesión";                                                     //Cambia el titulo de pag error
            $TitleErrorPage      = "Sesión Cerrado";                                                    //Error variables
            $MessageErrorPage    = "La sesión se ha cerrado";                                           //Error variables
            $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                       //Error variables
            $ButtonTextErrorPage = "Accede (otra vez) al Sistema";                                      //Error variables
            session_destroy();

            include("Error.php");                                                                       //Llama a la pagina de error
            exit();                                                                                     //Adios vaquero
        }

        // ==========================================================================================
        // ====================       CHECK FOR CORRECT STARTED SESSION  == =========================
        // ==========================================================================================
        function StandardCheckForStartedSession() {                                                     //=== INICIASTE SESION ===
            if (empty($_SESSION)) CallErrorPagePermissions();                                           //Go to call error page
        }


        // ==========================================================================================
        // ====================       CHECK FOR CORRECT DATA BASE    ================================
        // ==========================================================================================
        function StandardCheckForCorrectDataBase() {                                                    //=== TODO BIEN CON LA BASE ===
            $DataBase = @new mysqli("127.0.0.1", "root", "root", "Proyect");                            //Abrir una conexión
            
            if ((mysqli_connect_errno() != 0) or !$DataBase) {                                          //Si hubo problemas
                global $HTMLDocumentRoot;                                                               //You are a a global variable
                $TitleErrorPage      = "Error con la BD";                                               //Error variables
                $MessageErrorPage    = "No podemos acceder a la base de datos";                         //Error variables
                $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                   //Error variables
                $ButtonTextErrorPage = "Intenta otra vez";                                              //Error variables

                include("Error.php");                                                                   //Llama a la pagina de error
                exit();                                                                                 //Adios vaquero
            }

            return $DataBase;
        }




?>