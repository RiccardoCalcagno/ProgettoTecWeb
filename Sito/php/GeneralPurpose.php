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
            //Gestione Errore
        }else{
            if($_SESSION['stagedPersonaggi']){
                foreach ($_SESSION['stagedPersonaggi'] as &$personaggio){
                    $result = $db->addCharacter($personaggio);  
                    if(!$result){
                        $personaggio->set_name("Errore di Salvataggio");
                    }
                } 
            }
            if($_SESSION['stagedReports']){
                foreach ($_SESSION['stagedReports'] as &$report){
                    $result = $db->addReport($report);  
                    if(!$result){
                        $report->set_title("Errore di Salvataggio");
                    }
                } 
            }
            $db->closeConnection();
        }
    }
?>