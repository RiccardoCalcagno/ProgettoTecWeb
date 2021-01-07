<?php
    require_once("DBinterface.php");
    require_once("character.php");
    require_once("Errore.php");

    function clean_input($var) {   
        $var = htmlentities($var);

        $var = trim($var);
     
        return $var;
    }

    function saveStaged(){
        $presenti=0;
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
                        return -1;
                    }else{
                        $presenti=1;
                    }
                } 
            }
            if($_SESSION['stagedReports']){
                foreach ($_SESSION['stagedReports'] as &$report){
                    $report->set_author($_SESSION['username']);
                    $result = $db->addReport($report);  
                    if(!$result){
                        $report->set_title("Errore di Salvataggio");
                        return -1;
                    }else{
                        $presenti=1;
                    }
                } 
            }
            $db->closeConnection();
        }
        return $presenti;
    }

    function setup($html) { // Setup generico per tutte le pagine
        
        if( session_status() == PHP_SESSION_NONE ) {
            session_start();
        }

        if(isset($_SESSION["login"])&&($_SESSION["login"])) {
            $html = str_replace('<input id="Accedi" type="submit" name="accesso" value="Accedi">', '<input id="Accesso" name="accesso" type="submit" value="Esci">', $html);
            $html = str_replace('<input id="Iscrizione" type="submit" name="accesso" value="Iscrizione">', '<input id="Iscrizione" name="accesso" type="submit" value="Area Personale">', $html);
        }

        return $html;
    }
    
    function clearSession() {   // Clear di variabili session utili solo a specifiche pagine
            //unset OK anche su null
        if( session_status() == PHP_SESSION_NONE ) {
            session_start();
        }

        unset($_SESSION["character_id"]);
        unset($_SESSION['modificaChar']);
    }

    function setup_clear($html) { // Setup generico e clearSession
        
        clearSession();
        return setup($html);
    }

    function errorPage($errorMessage) {
        error($errorMessage);
    }

    function redirect_GET($path, $get) {   // Dato il path, esegue il redirect come se action fosse su quella pagina

        $path .= '?';

        foreach($get as $key => $value) {
            $path .= $key . '=' . $value . '&';
        }
        $path = rtrim($path, '&');

        header("Location: $path");
        exit();
    }

?>