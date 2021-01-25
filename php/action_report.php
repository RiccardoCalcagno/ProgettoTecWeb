<?php
    require_once("GeneralPurpose.php");

    
        session_start();
   

    if ( isset($_GET["PostRep"])) {  
        $db = new DBinterface();
        if( $db->openConnection() ) {
            if (!$db->setExplorable($_GET["PostRep"]) ) {
                $db->closeConnection();
                errorPage("EDB");exit();
            }else{
                unset($_SESSION["first_logged"]);
                $_SESSION["banners"]="pubblica_esplora_eplora_confermata";
            }
            $db->closeConnection();
        }
        else {
            errorPage("EDB");exit();
        }
        header("Location: area_personale.php#reportMaster");
        exit();
    }
    if ( isset($_GET["RemoveRep"])) {   
        $db = new DBinterface();
        if( $db->openConnection() ) {
            if (!$db->setExplorable($_GET["RemoveRep"],0) ) {
                $db->closeConnection();
                errorPage("EDB");exit();
            }else{
                unset($_SESSION["first_logged"]);
            }
            $db->closeConnection();
        }
        else {
            errorPage("EDB");exit();
        }
        header("Location: area_personale.php#reportMaster");
        exit();
    }
    

    if(isset($_GET["RemoveRep"]))
    {
        $db = new DBinterface();
        if(!$db->openConnection()) {errorPage("EDB");exit();}
        if(!$db->setExplorable($_POST["RemoveRep"], false)){$db->closeConnection();errorPage("EDB");exit();}
        $db->closeConnection();
        header("Location: area_personale.php#reportMaster");
    }

    if( isset($_GET["ReportMaster"]) ) {    
        header("Location: ReportPage.php?ReportID=".$_GET["ReportMaster"]);
        exit();
    }

    if(isset($_GET["ReportPartecip"])) {   
        header("Location: ReportPage.php?ReportID=".$_GET["ReportPartecip"]);
        exit();
    }

    if(isset($_GET["ReportEsplora"])) { 
        header("Location: ReportPage.php?ReportID=".$_GET["ReportEsplora"]);
        exit();
    }

    if ( isset($_POST['report']) ) {
        if ($_POST['report'] == 'COMMENTA') {

            $_SESSION['RepCommentPOST'] = $_POST;
            header("Location: ReportWriteComment.php");
            exit();
        }
    }

    if(isset($_POST["espandi"]))
    {
        if($_POST["espandi"] == "masterPrecedente")
        {
            $_SESSION["vai_indietro_master"] = true;
            header("Location: area_personale.php#anchorMaster");
            exit();
        }

        if($_POST["espandi"] == "masterSuccessivo")
        {
            $_SESSION["vai_avanti_master"] = true;
            header("Location: area_personale.php#anchorMaster");
            exit();
        }

        if($_POST["espandi"] == "partecPrecedente")
        {
            $_SESSION["vai_indietro_rep"] = true;
            header("Location: area_personale.php#anchorPartecipante");
            exit();
        }

        if($_POST["espandi"] == "partecSuccessivo")
        {
            $_SESSION["vai_avanti_rep"] = true;
            header("Location: area_personale.php#anchorPartecipante");
            exit();
        }
    }

    if(isset($_GET["espandi"])){
        if($_GET["espandi"] == "esploraPrecedente")
        {
            $_SESSION["vai_indietro_esplora"] = true;
            header("Location: EsploraPage.php#anchorEsplora");
            exit();
        }
    
        if($_GET["espandi"] == "esploraSuccessivo")
        {
            $_SESSION["vai_avanti_esplora"] = true;
            header("Location: EsploraPage.php#anchorEsplora");
            exit();
        }
    }

    if(isset($_POST["eliminaCommento"])) { 
        $_SESSION['banners']="confermare_eliminazione_commento";
        $_SESSION['banners_ID'] = array("ReportID" => $_POST['ReportID'], "CommentID" => $_POST['eliminaCommento']); 
        header("Location: ReportPage.php?ReportID=".$_POST['ReportID']."#bannerID");
        exit();
    }

    if(isset($_POST["documento"]) && $_POST['documento'] == 'ELIMINA COMMENTO') { 
        
        $db = new DBinterface();
        if( $db->openConnection() ) {
            if (!$db->deleteComments($_POST["CommentID"]) ) {
                $db->closeConnection();errorPage("EDB");exit();
            }
            $db->closeConnection();
        }
        else {
            errorPage("EDB");exit();
        }
        header("Location: ReportPage.php?ReportID=".$_POST["ReportID"]."#anchorComment");
        exit();
    }

    if(isset($_GET["reportAction"]) && $_GET['reportAction'] == 'ELIMINA') {
        $_SESSION['banners']="confermare_eliminazione_report";
        $_SESSION['banners_ID'] = $_GET['ReportID']; 
        header("Location: ReportPage.php?ReportID=".$_GET['ReportID']."#bannerID");
        exit();
    }

    if (isset($_POST['documento']) && $_POST['documento'] == 'ELIMINA REPORT' ) {
            $db = new DBinterface();
            if($db->openConnection()) {
    
                $done = $db->deleteReport($_POST['ReportID']);
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
            exit();
        }

    if((isset($_GET["reportAction"]))&&($_GET["reportAction"]=="Pubblica in ESPLORA"))
    {
        $db = new DBinterface();
        if($db->openConnection()) {
            if(!$db->setExplorable($_GET['ReportID'])){$db->closeConnection();errorPage("EDB");exit();}
            $db->closeConnection();
            $_SESSION["banners"]="pubblica_esplora_eplora_confermata";
            header("Location: ReportPage.php?ReportID=".$_GET['ReportID']."#bannerID");
        }else{
            errorPage("EDB");exit();
        }
        exit();
    }
    if((isset($_GET["reportAction"]))&&($_GET["reportAction"]=="Rimuovi da ESPLORA"))
    {
        $db = new DBinterface();
        if($db->openConnection()) {
            if(!$db->setExplorable($_GET['ReportID'], 0)){$db->closeConnection();errorPage("EDB");exit();}
            $db->closeConnection();
            header("Location: ReportPage.php?ReportID=".$_GET['ReportID']."#footAction");
        }else{
            errorPage("EDB");exit();
        }
        exit();
    }

    if((isset($_GET["reportAction"]))&&($_GET["reportAction"]=="MODIFICA"))
    {
        $_SESSION['id_report_modifica'] = $_GET['ReportID'];
        header("Location: CreazioneReportPage.php");
        exit();
    }

?>
