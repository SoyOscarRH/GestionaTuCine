<?php 

	    // ==========================================================================================
        // ======================   ARE WE AT A MOBILE SYSTEM      ====================================
        // ==========================================================================================
        function WeAreAtMobile() {                                                            			// === VEAMOS SI ESTAMOS EN MOVIL ===
            $Data = "/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|";               		//Tal vez seas esto
            $Data.= "hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i";   		//Tal vez seas esto
            return preg_match($Data, $_SERVER["HTTP_USER_AGENT"]);                                		//Dime ¿va?
        }


        // ==========================================================================================
        // ====================        CLEAR FOR HORRIBLE QUERYS        =============================
        // ==========================================================================================
        function ClearSQLInyection($SomeString){                                              			//=== LIMPIAMOS UN STRING ===
            $Bad = array('INSERT ','DELETE ','SELECT ','UPDATE ','"','=',"'",'-',';',' AND ',' OR ');   //Valores a cambiar
            $Good = array('','','','','','','','','','','');                                          	//Valores nuevos
            $SomeString = str_ireplace($Bad, $Good, $SomeString);                               		//Cambia los valore
            return $SomeString;                                                                         //Regresa ahora si el bueno
        }


?>