<?php
    require_once("GeneralPurpose.php");

    if( session_status() == PHP_SESSION_NONE ) {
        session_start();
    }

    if ( isset($_GET["PostRep"])) {    // Pubblica in Esplora, da banner conferma_pubblica in AreaPersonale.php
        $db = new DBinterface();
        if( $db->openConnection() ) {
            if (!$db->setExplorable($_GET["PostRep"]) ) {
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
    if ( isset($_GET["RemoveRep"])) {    // Pubblica in Esplora, da banner conferma_pubblica in AreaPersonale.php
        $db = new DBinterface();
        if( $db->openConnection() ) {
            if (!$db->setExplorable($_GET["RemoveRep"],0) ) {
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
        if(!$db->setExplorable($_POST["RemoveRep"], false)){errorPage("EDB");exit();}
        $db->closeConnection();
        header("Location: area_personale.php#reportMaster");
    }

    if( isset($_GET["ReportMaster"]) ) {    // Report creato da user, Da AreaPersonale.php
        header("Location: ReportPage.php?ReportID=".$_GET["ReportMaster"]);
        exit();
    }

    if(isset($_GET["ReportPartecip"])) {    // Report in cui user partecipa, Da AreaPersonale.php
        header("Location: ReportPage.php?ReportID=".$_GET["ReportPartecip"]);
        exit();
    }

    if(isset($_GET["ReportEsplora"])) { // Report condiviso in esplora, Da EsploraPage.php
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



    // if(isset($_GET["contenutoCommento"]))
    // {
    //     //create comment from data
    //     $comment = new Comments(0,$_GET["contenutoCommento"],0,$_SESSION["username"],$_SESSION["report_id"]);
    //     $db->openConnection();
    //     $db->addComments($comment);
    //     $db->closeConnection();
    //     header("Location: ReportPage.php");
    // }

    if(isset($_POST["eliminaCommento"])) {  // Eliminazione commento, da ReportPage.php
        $_SESSION['banners']="confermare_eliminazione_commento";
        $_SESSION['banners_ID'] = array("ReportID" => $_POST['ReportID'], "CommentID" => $_POST['eliminaCommento']);    // PASSAGGIO PER CAMPO HIDDEN
        header("Location: ReportPage.php?ReportID=".$_POST['ReportID']."#anchorComment");
        exit();
    }

    if(isset($_POST["documento"]) && $_POST['documento'] == 'ELIMINA COMMENTO') {   // Eliminazione commento, da banner conferma_eliminazine in ReportPage.php
        
        $db = new DBinterface();
        if( $db->openConnection() ) {
            if (!$db->deleteComments($_POST["CommentID"]) ) {
                errorPage("EDB");exit();
            }
            $db->closeConnection();
        }
        else {
            errorPage("EDB");exit();
        }
// feedback?
        header("Location: ReportPage.php?ReportID=".$_POST["ReportID"]."#anchorComment");
        exit();
    }

    if(isset($_GET["reportAction"]) && $_GET['reportAction'] == 'ELIMINA') {    // Eliminazione Report, da ReportPage.php 
        $_SESSION['banners']="confermare_eliminazione_report";
        $_SESSION['banners_ID'] = $_GET['ReportID'];    // PASSAGGIO PER CAMPO HIDDEN
        header("Location: ReportPage.php?ReportID=".$_GET['ReportID']."#footAction");
        exit();
    }

    if (isset($_POST['documento']) && $_POST['documento'] == 'ELIMINA' ) {  // Eliminazione Report, da banner conferma_eliminazine in ReportPage.php
        // Serve una pagina solo per questo ? o anche solo una function ? .
            $db = new DBinterface();
            if($db->openConnection()) {
    
                $done = $db->deleteReport($_POST['ReportID']);
                $db->closeConnection();
        
                if ($done) {
                    // FEEDBACK? like $_SESSION['AP_message'] = 'Cancellazione Riuscita.'; // e poi metterlo in AreaP
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
            if(!$db->setExplorable($_GET['ReportID'])){errorPage("EDB");exit();}
            $db->closeConnection();
            $_SESSION["banners"]="pubblica_esplora_eplora_confermata";
            header("Location: ReportPage.php?ReportID=".$_GET['ReportID']."#footAction");
        }else{
            errorPage("EDB");exit();
        }
        exit();
    }
    if((isset($_GET["reportAction"]))&&($_GET["reportAction"]=="Rimuovi da ESPLORA"))
    {
        $db = new DBinterface();
        if($db->openConnection()) {
            if(!$db->setExplorable($_GET['ReportID'], 0)){errorPage("EDB");exit();}
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