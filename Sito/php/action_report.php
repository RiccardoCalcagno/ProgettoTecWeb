<?php

    $db = new DBinterface();
    
    if(isset($_POST["PostRep"]))
    {
        $db->openConnection();
        $db->setExplorable($_POST["PostRep"], true);
        $db->closeConnection();
        header("Location : area_personale.php");
    }

    if(isset($_POST["RemoveRep"]))
    {
        $db->openConnection();
        $db->setExplorable($_POST["RemoveRep"], false);
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


    

    

?>