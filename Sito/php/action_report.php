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
        $db->setExplorable($_POST["PostRep"], false);
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

    if(isset($_POST["id"]))
    {
        if(!isset($_SESSION))
            session_start();

        $_SESSION["report_id"] = $_POST["id"];

        header("Location : report.php");
    }


    

    

?>