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
        function CallErrorPageSession($CostumMessage = "") {                                            //== AQUI HAY PROBLEMAS  ==
            global $HTMLDocumentRoot;                                                                   //Eres global :D
            $TitleErrorPage      = "Error de Sesión";                                                   //Error variables

            if ($CostumMessage != "") $MessageErrorPage = $CostumMessage;                               //Cosas de Mensajes
            else $MessageErrorPage  = "No iniciaste sesión en el Sistema";                              //Error variables

            $ButtonLinkErrorPage = $HTMLDocumentRoot."Login.php";                                       //Error variables
            $ButtonTextErrorPage = "Accede al Sistema";                                                 //Error variables

            include("Error.php");                                                                       //Llama a la pagina de error
            exit();                                                                                     //Adios vaquero
        }

        function CallErrorPagePermissions($CostumMessage = "") {                                        //== AQUI HAY PROBLEMAS  ==
            global $HTMLDocumentRoot;                                                                   //Eres global :D
            $TitleErrorPage      = "Error Permisos";                                                    //Error variables

            if ($CostumMessage != "") $MessageErrorPage = $CostumMessage;                               //Cosas de Mensajes
            else $MessageErrorPage  = "No tienes permiso de estar en esta página";                      //Error variables

            $ButtonLinkErrorPage = $HTMLDocumentRoot."MenuEmployee.php";                                //Error variables
            $ButtonTextErrorPage = "Volver al Menu";                                                    //Error variables

            include("Error.php");                                                                       //Llama a la pagina de error
            exit();                                                                                     //Adios vaquero
        }

        function CallGeneralErrorPage($CostumMessage = "") {                                            //== AQUI HAY PROBLEMAS  ==
            global $HTMLDocumentRoot;                                                                   //Eres global :D
            $TitleErrorPage      = "Error";                                                             //Error variables

            if ($CostumMessage != "") $MessageErrorPage = $CostumMessage;                               //Cosas de Mensajes
            else $MessageErrorPage  = "Ha ocurrido un error D':";                                       //Error variables

            $ButtonLinkErrorPage = $HTMLDocumentRoot;                                                   //Error variables
            $ButtonTextErrorPage = "Ve al Inicio";                                                      //Error variables

            include("Error.php");                                                                       //Llama a la pagina de error
            exit();                                                                                     //Adios vaquero
        }

        function CallErrorPageOnlyForAdmins($CostumMessage = "") {                                      //== NO MIJO, NO PERMISO ==
            global $HTMLDocumentRoot;                                                                   //Eres global :D
            $TitleErrorPage      = "Error Permisos";                                                    //Error variables

            if ($CostumMessage != "") $MessageErrorPage = $CostumMessage;                               //Cosas de Mensajes
            else $MessageErrorPage  = "No eres un Administrador, no puedes ver esta página";            //Error variables

            $ButtonLinkErrorPage = $HTMLDocumentRoot."MenuEmployee.php";                                //Error variables
            $ButtonTextErrorPage = "Volver al Menu";                                                    //Error variables

            include("Error.php");                                                                       //Llama a la pagina de error
            exit();                                                                                     //Adios vaquero
        }

        function CallClosePage($CostumMessage = "") {                                                   //== YA QUE CARGO AL VER... ==
            global $HTMLDocumentRoot;                                                                   //Eres global :D
            $NewHTMLTitle        = "Cerrar Sesión";                                                     //Cambia el titulo de pag error
            $TitleErrorPage      = "Sesión Cerrada";                                                    //Error variables

            if ($CostumMessage != "") $MessageErrorPage = $CostumMessage;                               //Cosas de Mensajes
            else $MessageErrorPage    = "La sesión se ha cerrado";                                      //Error variables
            
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
            if (empty($_SESSION)) CallErrorPageSession();                                               //Go to call error page
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

        // ==========================================================================================
        // ====================       CHECK FOR UP TO DATE DATA     =================================
        // ==========================================================================================
        function StandardUpdateSessionData($ID, $DataBase) {                                            //=== INICIASTE SESION ===
            
            $NewDataFromBase = $DataBase->query("
                SELECT * FROM Empleado WHERE ID = {$ID}");                                              //AHORA SI PUEDES HACER ESTO

            if (!$NewDataFromBase) CallClosePage("Error con el Valor de Sesión");                       //Envia mensaje si todo mal

            $_SESSION = array_merge($_SESSION, $NewDataFromBase->fetch_assoc());                        //Actualiza valores de Sesion
            unset($_SESSION['Contrasena']);                                                             //Creo que esto no es buena idea :0

            $_SESSION["CompleteUserName"] = $_SESSION['Nombre'];                                        //Actualiza valores de Sesion
            $_SESSION["CompleteUserName"].= " ".$_SESSION['ApellidoPaterno'];                           //Dame su info
            $_SESSION["CompleteUserName"].= " ".$_SESSION['ApellidoMaterno'];                           //Dame su info

            $_SESSION["IAmAManager"] = ($_SESSION["IDGerente"] == $_SESSION["ID"]);                     //Dice true si eres gerente
        }


        // ==========================================================================================
        // ====================       CHECK FOR UP TO DATE DATA     =================================
        // ==========================================================================================
        function StandardCheckForAdminStatus($ID, $DataBase) {                                          //=== INICIASTE SESION ===

            $QueryID = $DataBase->query(" SELECT ID FROM Empleado WHERE 
                                            IDGerente = {$ID} AND
                                            ID = {$ID}");                                               //Haz la consulta

            if (!$QueryID) return false;
            else return true;                                                    //Dime si encontraste algo
        }




?>