<?php
    //require
    require_once("DBinterface.php");
    require_once("report_data.php");
    require_once("GeneralPurpose.php");
    require_once("banners.php");

//-------------------------- UTILITY
    
    //prepara la pagina. Se e' un report da modificare e non da creare da zero, cambieranno alcuni elementi dell'html
    function preparePage($html, $toModify){
        
        $headTitle = ''; $header = ''; $p = ''; $button = '';
        
        if($toModify){
            $headTitle = '<title>Modifica Report di Sessione</title>
            <meta name="title" content="Modifica Report di Sessione" />
            <meta name="description" content="Modifica il tuo report di sessione" />
            <meta name="keywords" content="modifica, report, Dungeons and Dragons, sessione" />';

            $header = '<header id="intestazionePagina">
            <h1>Modifica Report di Sessione <span> <a class="puntoInterrogativo" 
                href="../otherHTMLs/Approfondimenti/approfondimento_Report.html">?</a></span></h1>
            </header>';

            $p = '<p>Concludi la modifica salvando la nuova versione del report nella tua Area Personale</p>';

            $button = '<input class="buttonLink" type="submit" name="salvaRep" value="SALVA MODIFICA"/>';
        }
        else {
            $headTitle = '<title>Creazione Report di Sessione</title>
            <meta name="title" content="Creazione Report di Sessione" />
            <meta name="description" content="Crea il tuo report di sessione" />
            <meta name="keywords" content="creazione, report, Dungeons and Dragons, sessione" />';

            $header = '<header id="intestazionePagina">
            <h1>Creazione Report di Sessione <span> <a class="puntoInterrogativo" href="../otherHTMLs/Approfondimenti/approfondimento_Report.html">?</a></span></h1>
            <p>Sei qui per realizzare il tuo primo report di sessione? Non temere, segui questi semplici 
                passaggi e in breve il tuo ricordo sarà condensato in un report da mostrare a chi vorrai. </p>
            <p class="attenzioneP">(<strong class="Attenzione">Attenzione</strong>: per effettuare il salvataggio del report sarà necessaria una tua autenticazione)</p>
            </header>';

            $p = '<p>Concludi la creazione salvando il nuovo report nella tua Area Personale</p>';

            $button = '<input class="buttonLink" type="submit" name="salvaRep" value="SALVA REPORT"/>';
        }

        $html = str_replace('<headTitle_placeholder />',$headTitle,$html);
        $html = str_replace('<header_placeholder />',$header,$html);
        $html = str_replace('<p_placeholder />',$p,$html);
        $html = str_replace('<button_placeholder />',$button,$html);

        return $html;
    }

// -------------------------------------------------------

    staged_session();

    $html = file_get_contents('..'.DIRECTORY_SEPARATOR.'otherHTMLs'.DIRECTORY_SEPARATOR.'creazioneReport.html');
    $html = setup($html);
    $toModify = isset( $_SESSION['ModificaReport']) &&  $_SESSION['ModificaReport'];
    $html = preparePage($html,$toModify);



    /*
            IMPORTANTE !!!!!!!!!!!!!!!

            EFFETTO LATO PHP del PULSANTE MODIFICA nella visualizzazione del REPORT:                (Lo stesso vale per i personaggi)
                    - $_SESSION['report_in_creazione'] inizializzato a $_SESSION["report_id"]
            
            I QUESTO FILE PRIMA DELL' IF: if(isset($_POST['salvaRep'])) vanno messe le sostituzioni HTML per creare il giusto documento: creazione o modifica

        ----------------------------------------------------------------------------------------------------------------
    */

    $titolo = ''; $sottotitolo = ''; $contenuto = ''; $condividi = false; $lista_giocatori = array();



    if(isset($_POST['salvaRep'])){

        /*
        HO SOTITUITO QUESTO CON QUELLO SOTTO:
        $titolo = $_POST['titolo'];
        $sottotitolo = $_POST['sottotitolo'];
        $contenuto = $_POST['contenuto'];
        $condividi = $_POST['condividi'];
        array_push($_POST['lista_giocatori'],$lista_giocatori);
        */

        // PRIMA ALLA CREAZIONE DI report_in_creazione SI È INSERITO IL CORRETTO ID e AUTOR = $_SESSION['username'] ANCHE SE È NULL
        $rep = $_SESSION['report_in_creazione'];
        $titolo = $rep->get_titolo();
        $sottotitolo = $rep->get_sottotitolo();
        $contenuto = $rep->get_contenuto();
        $condividi = $rep->get_condividi();
        $lista_giocatori = $rep->get_lista_giocatori();

        //controlli
        if(strlen($titolo) != 0 && strlen($sottotitolo) != 0 && strlen($contenuto) != 0) {

            //creo l'oggetto report  AUTOR PUO ESSERE NULL (È CORRETTO, serve anche ai salvataggi pendenti)
            //$rep = new ReportData($idRep, $titolo, $sottotitolo, $contenuto, $_SESSION['username'], $condividi, $lista_giocatori);
            //assegno il report così come creato alla variabile che ne tiene conto per ri-riempire la form ad un eventuale ricaricamento
            //$_SESSION['report_in_creazione'] = $rep;

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