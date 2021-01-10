<?php
    require_once("GeneralPurpose.php");

    if( session_status() == PHP_SESSION_NONE ) {
        session_start();
    }
    if(isset($_POST["PostRep"]))
    {
        $_POST["PostRep"]->set_isExplorable(true);
        $db->openConnection();
        $db->setExplorable($_POST["PostRep"]->get_id(), true);
        $db->closeConnection();
        header("Location: area_personale.php");
    }

    if(isset($_POST["RemoveRep"]))
    {
        $_POST["RemoveRep"]->set_isExplorable(false);
        $db->openConnection();
        $db->setExplorable($_POST["RemoveRep"]->get_id(), false);
        $db->closeConnection();
        header("Location: area_personale.php");
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
        }
    }

    if(isset($_POST["espandi"]))
    {
        if($_POST["espandi"] == "masterPrecedente")
        {
            $_SESSION["vai_indietro_master"] = true;
            header("Location: area_personale.php");
        }

        if($_POST["espandi"] == "masterSuccessivo")
        {
            $_SESSION["vai_avanti_master"] = true;
            header("Location: area_personale.php");
        }

        if($_POST["espandi"] == "partecPrecedente")
        {
            $_SESSION["vai_indietro_rep"] = true;
            header("Location: area_personale.php");
        }

        if($_POST["espandi"] == "partecSuccessivo")
        {
            $_SESSION["vai_avanti_rep"] = true;
            header("Location: area_personale.php");
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
        header("Location: ReportPage.php?ReportID=".$_POST['ReportID']);
        
        
    }

    if(isset($_POST["documento"]) && $_POST['documento'] == 'ELIMINA COMMENTO') {   // Eliminazione commento, da banner conferma_eliminazine in ReportPage.php
        
        $db = new DBinterface();
        if( $db->openConnection() ) {
            if (!$db->deleteComments($_POST["CommentID"]) ) {
                errorPage("Cancellazione Commento fallita. Riprovare piu' tardi");
            }
            $db->closeConnection();
        }
        else {
            errorPage("Can't connect to DB");
        }
// feedback?
        header("Location: ReportPage.php?ReportID=".$_POST["ReportID"]);
    }

    if(isset($_GET["reportAction"]) && $_GET['reportAction'] == 'ELIMINA') {    // Eliminazione Report, da banner conferma_eliminazine in ReportPage.php
        $_SESSION['banners']="confermare_eliminazione_report";
        $_SESSION['banners_ID'] = $_GET['ReportID'];    // PASSAGGIO PER CAMPO HIDDEN
        header("Location: ReportPage.php?ReportID=".$_GET['ReportID']);
    }

    if (isset($_POST['documento']) && $_POST['documento'] == 'ELIMINA' ) {  // Eliminazione Report, da ReportPage.php
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
                    errorPage("Cancellazione Report non riuscita. Riprovare piu' tardi.");
                }
            }
            else {
                errorPage("Connessione DB non riuscita.");
            }
    
        }

    if(isset($_GET["FtAct_PublicReport"]))
    {
        $db->openConnection();
        $db->setExplorable($_SESSION["report_id"]);
        $db->closeConnection();
        header("Location: ReportPage.php");
    }

    if(isset($_GET["FtAct_ModReport"]))
    {
        $_SESSION['ModificaReport'] = true;
        //$_SESSION['report_in_creazione'] = $_SESSION["report_id"]
        header("Location: CreazioneReportPage.php");
    }

    /*
    if(isset($_GET['salvaRep']))
    {

    }
    */

?>