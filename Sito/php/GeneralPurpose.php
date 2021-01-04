<?php
    require_once("DBinterface.php");
    require_once("character.php");

    function clean_input($var) {   
        $var = htmlentities($var);

        $var = trim($var);
     
        return $var;
    }

    function saveStaged(){
        $db = new DBinterface();
        $openConnection = $db->openConnection();
        if ($openConnection == false) {
            return false;
        }else{
            if($_SESSION['stagedPersonaggi']){
                foreach ($_SESSION['stagedPersonaggi'] as &$personaggio){
                    $personaggio->set_author($_SESSION['username']);
                    $result = $db->addCharacter($personaggio);  
                    if(!$result){
                        $personaggio->set_name("Errore di Salvataggio");
                        return false;
                    }
                } 
            }
            if($_SESSION['stagedReports']){
                foreach ($_SESSION['stagedReports'] as &$report){
                    $report->set_author($_SESSION['username']);
                    $result = $db->addReport($report);  
                    if(!$result){
                        $report->set_title("Errore di Salvataggio");
                        return false;
                    }
                } 
            }
            $db->closeConnection();
        }
        return true;
    }

    function setup($html) { // Setup generico per tutte le pagine
        
        if( !isset($_SESSION) ) {
            session_start();
        }


        if(isset($_SESSION["username"])) {
            $html = str_replace('<input id="Accedi" type="submit" name="accesso" value="Accedi">', '<input id="Accesso" type="submit" value="Esci">', $html);
            $html = str_replace('<input id="Iscrizione" type="submit" name="accesso" value="Iscrizione">', '<input id="Iscrizione" type="submit" value="Area Personale">', $html);
        }

        return $html;
    }
    
    function clearSession() {   // Clear di variabili session utili solo a specifiche pagine
            //unset OK anche su null
        if( !isset($_SESSION) ) {
            session_start();
        }

        unset($_SESSION["character_id"]);
        unset($_SESSION['modificaChar']);
    }

    function setup_clear($html) { // Setup generico e clearSession
        
        clearSession();
        return setup($html);
    }



?>