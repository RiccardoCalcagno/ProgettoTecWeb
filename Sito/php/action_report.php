<?php

    $db = new DBinterface();
    
    if(isset($_POST["PostRep"]))
    {
        $_POST["PostRep"]->set_isExplorable(true);
        $db->openConnection();
        $db->setExplorable($_POST["PostRep"]->get_id(), true);
        $db->closeConnection();
        header("Location : area_personale.php");
    }

    if(isset($_POST["RemoveRep"]))
    {
        $_POST["RemoveRep"]->set_isExplorable(false);
        $db->openConnection();
        $db->setExplorable($_POST["RemoveRep"]->get_id(), false);
        $db->closeConnection();
        header("Location : area_personale.php");
    }

    if(isset($_POST["ReportMaster"]))
    {
        $db->openConnection();
        $_SESSION["report_id"] = $db->getReport($_POST["ReportMaster"]);
        $db->closeConnection();
        header("Location : ReportPage.php");
    }

    if(isset($_POST["ReportPartecip"]))
    {
        $db->openConnection();
        $_SESSION["report_id"] = $db->getReport($_POST["ReportPartecip"]);
        $db->closeConnection();
        header("Location : ReportPage.php");
    }

    if(isset($_POST["ReportEsplora"]))
    {
        if(!isset($_SESSION))
            session_start();

        $_SESSION["report_id"] = $_POST["ReportEsplora"];

        header("Location : ReportPage.php");
    }

    if(isset($_POST["espandi"]))
    {
        if($_POST["espandi"] == "masterPrecedente")
        {
            $_SESSION["vai_indietro_master"] = true;
            header("Location : area_personale.php");
        }

        if($_POST["espandi"] == "masterSuccessivo")
        {
            $_SESSION["vai_avanti_master"] = true;
            header("Location : area_personale.php");
        }

        if($_POST["espandi"] == "partecPrecedente")
        {
            $_SESSION["vai_indietro_rep"] = true;
            header("Location : area_personale.php");
        }

        if($_POST["espandi"] == "partecSuccessivo")
        {
            $_SESSION["vai_avanti_rep"] = true;
            header("Location : area_personale.php");
        }

    }


    

    

?>