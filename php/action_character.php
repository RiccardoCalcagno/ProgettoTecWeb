<?php
    require_once("GeneralPurpose.php");
    require_once("DBinterface.php");

    
        session_start();
    

    if ( isset($_GET["Personaggio"]) ) {

        $db = new DBinterface();
        $connection=$db->openConnection();
        if(!$connection)
            {errorPage("EDB");exit();}
        if($db->getCharacterOfUser($_GET["Personaggio"], $_SESSION["username"]))
        {
            $db->closeConnection();
            header("Location: CharacterPage.php?Personaggio=".$_GET["Personaggio"]);
            exit();
        }
        else
        {
            $db->closeConnection();  
            errorPage("Ci spiace informarla che non siamo riusciti a verificare i suoi diritti di visualizzazione su questa scheda giocatore");
            exit();
        }
    }

    if( isset($_GET['charAction']) ) {
        
        if($_GET['charAction'] == "MODIFICA") {

           redirect_GET("character_creation(FormAction).php", $_GET);
        }

        if($_GET['charAction'] == "ELIMINA") {

            $_SESSION['banners']="confermare_eliminazione_personaggio";
            $_SESSION['banners_ID'] = $_GET['charID']; 
            header("Location: CharacterPage.php?charID=".$_GET['charID']."#bannerID"); 
        }
    }

    if (isset($_POST['salvaPers'])) {

        $_SESSION['CharFormPOST'] = $_POST;
        
        if ( $_POST['salvaPers'] == 'SALVA SCHEDA' ) {
            header("Location: character_creation(FormAction).php"); 
        }
        else {  
            header("Location: character_creation(FormAction).php?charAction=MODIFICA&charID=".$_POST['charID']);  
        }
    }

    if (isset($_POST['documento']) && $_POST['documento'] == 'ELIMINA SCHEDA' ) {
        $db = new DBinterface();
        if($db->openConnection()) {

            $done = $db->deleteCharacter($_POST['charID']);
            $db->closeConnection();
    
            if ($done) {
                header("Location: area_personale.php");
            }
            else {
                errorPage("EDB");exit();
            }
        }
        else {
            errorPage("EDB");exit();
        }

    }

    if(isset($_GET["espandi"]))
    {
        $_SESSION["espandiPers"] = true;
        header("Location: area_personale.php#personaggiUtente");
        exit();
    }

    if(isset($_GET["riduci"]))
    {
        $_SESSION["espandiPers"] = false;
        header("Location: area_personale.php#personaggiUtente");
        exit();
    }

?>
