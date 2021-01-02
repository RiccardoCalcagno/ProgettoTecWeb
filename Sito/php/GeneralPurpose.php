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
?>