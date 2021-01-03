<?php
    //require
    require_once("DBinterface.php");
    require_once("report_data.php");
    require_once("GeneralPurpose.php");
    require_once("banners.php");

    // use DB\DBinterface; //SERVE A QUALCOSA?

    $html = file_get_contents('..'. DIRECTORY_SEPARATOR . 'otherHTMLs' . DIRECTORY_SEPARATOR . "creazioneReport.html");

    if(isset($_SESSION["username"]))
    {
        $html = str_replace("<input id=\"Accesso\" type=\"submit\" value=\"Accedi\">", "<input id=\"Accesso\" type=\"submit\" value=\"Esci\">", $html);
        $html = str_replace("<input id=\"Iscrizione\" type=\"submit\" value=\"Iscrizione\">", "<input id=\"Iscrizione\" type=\"submit\" value=\"Area Personale\">", $html);
    }


    /*
            IMPORTANTE !!!!!!!!!!!!!!!

            EFFETTO LATO PHP del PULSANTE MODIFICA nella visualizzazione del REPORT:                (Lo stesso vale per i personaggi)
                    - $_SESSION['report_in_creazione'] inizializzato a $_SESSION["report_id"]
            
            I QUESTO FILE PRIMA DELL' IF: if(isset($_POST['salvaRep'])) vanno messe le sostituzioni HTML per creare il giusto documento: creazione o modifica

        ----------------------------------------------------------------------------------------------------------------
    */


    /*
    HO SOTITUITO QUESTO CON QUELLO SOTTO:
    $titolo = $_POST['titolo'];
    $sottotitolo = $_POST['sottotitolo'];
    $contenuto = $_POST['contenuto'];
    $condividi = $_POST['condividi'];
    */

    // PRIMA ALLA CREAZIONE DI report_in_creazione SI È INSERITO IL CORRETTO ID e AUTOR = $_SESSION['username'] ANCHE SE È NULL
    $rep = $_SESSION['report_in_creazione'];
    $titolo = $rep->get_titolo();
    $sottotitolo = $rep->get_sottotitolo();
    $contenuto = $rep->get_contenuto();
    $condividi = $rep->get_condividi();
    $lista_giocatori = $rep->get_lista_giocatori();



    if(isset($_POST['salvaRep'])){

        //controlli
        if(strlen($titolo) != 0 && strlen($sottotitolo) != 0 && strlen($contenuto) != 0) {

            /*creo l'oggetto report  AUTOR PUO ESSERE NULL (È CORRETTO, serve anche ai salvataggi pendenti)
            $rep = new ReportData($idRep, $titolo, $sottotitolo, $contenuto, $_SESSION['username'], $condividi, $lista_giocatori);
            */
            
            if(isset($_SESSION['username'])) {

                $dbInterface = new DBinterface();
                $connection = $dbInterface->openConnection();

                if($connection){

                    if($_POST['salvaRep']=="SALVA MODIFICA"){
                        //modifico il report nel database
                        $insertionResult = $dbInterface->setReport($rep);
                        if ($insertionResult){
                            $_SESSION['banners']= "modifica_documento_confermata";
                            //azzero la form
                            $titolo = '';$sottotitolo = '';$contenuto = '';$condividi = '';
                        }
                        else{
                            //messaggi di errore lato server, non errori di compilazione
                            $message = 'Errore lato Server, Riprovare.';
                        }
                    }else{
                        //aggiungo il report nel database
                        $insertionResult = $dbInterface->addReport($rep);
                        if ($insertionResult){
                            $_SESSION['banners']= "creazione_documento_confermata";
                            //azzero la form
                            $titolo = '';$sottotitolo = '';$contenuto = '';$condividi = '';
                        }
                        else{
                            //messaggi di errore lato server, non errori di compilazione
                            $message = 'Errore lato Server, Riprovare.';
                        }
                    }
                }else{
                    $message = 'Errore lato Server, Riprovare.';
                }
            }else{
                array_push($_SESSION['stagedReports'], $rep);
                $_SESSION['banners']= "salvataggio_pendente";
            }
        }
        //altrimenti ci sono stati errori di inserimento
        else{
            $message = 'errori:';
            if (strlen($titolo) == 0) {
                $message.='titolo troppo corto';
            }
            if (strlen($sottotitolo) == 0) {
                $message .='sottotitolo troppo corto';
            }
            if (strlen($contenuto) == 0) {
                $message.='contenuto troppo corto';
            }
        }
        
    }

    //il contenuto delle textarea viene settato qui
    $html = str_replace('<valueTitle/>',$titolo,$html);
    $html = str_replace('<valueSubtitle/>',$sottotitolo,$html);
    $html = str_replace('<valueContent/>',$contenuto,$html);
    


    $html = addPossibleBanner($html, "CreazioneReportPage.php");

    echo $html;

?>