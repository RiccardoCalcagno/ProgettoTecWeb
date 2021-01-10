<?php
    //require
    require_once("DBinterface.php");
    require_once("report_data.php");
    require_once("GeneralPurpose.php");
    require_once("banners.php");

    unset($_SESSION["first_logged"]);

//-------------------------- UTILITY
    
    //prepara la pagina. Se e' un report da modificare e non da creare da zero, cambieranno alcuni elementi dell'html
    function preparePage($html, $toEdit){
        
        $headTitle = ''; $header = ''; $p = ''; $button = '';
        
        if($toEdit){
            $headTitle = '<title>Modifica Report di Sessione</title>
            <meta name="title" content="Modifica Report di Sessione" />
            <meta name="description" content="Modifica il tuo report di sessione" />
            <meta name="keywords" content="modifica, report, Dungeons and Dragons, sessione" />';

            $header = '<header id="intestazionePagina">
            <h1>Modifica Report di Sessione <span> <a class="puntoInterrogativo" 
                href="../php/Approf_report.php">?</a></span></h1>
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
            <h1>Creazione Report di Sessione <span> <a class="puntoInterrogativo" href="../php/Approfondimenti/approfondimento_Report.html">?</a></span></h1>
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

    $toEdit = false;

    if ( isset($_GET['reportAction']) && $_GET['reportAction'] == 'MODIFICA' ) {
        $toEdit =  true;
    }

    $html = file_get_contents('..'.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'creazioneReport.html');
    $html = setup($html);
    $html = preparePage($html,$toEdit);



    /*
            IMPORTANTE !!!!!!!!!!!!!!!

            EFFETTO LATO PHP del PULSANTE MODIFICA nella visualizzazione del REPORT:                (Lo stesso vale per i personaggi)
                    - $_SESSION['report_in_creazione'] inizializzato a $_SESSION["report_id"]
            
            I QUESTO FILE PRIMA DELL' IF: if(isset($_GET['salvaRep'])) vanno messe le sostituzioni HTML per creare il giusto documento: creazione o modifica

        ----------------------------------------------------------------------------------------------------------------
    */

    $titolo = ''; $sottotitolo = ''; $contenuto = ''; $condividi = false; $lista_giocatori = array();



$_GET['salvaRep']="SALVA REPORT";



    if(   (isset($_GET['salvaRep']))  ||  (isset($_GET['aggiungiGiocatore']))  ||  (isset($_GET['deletePlayer']))   ){

        if(isset($_GET['salvaRep'])){

        
            /*
            $titolo = $_GET['titolo'];
            $sottotitolo = $_GET['sottotitolo'];
            $contenuto = $_GET['contenuto'];
            $condividi = (isset($_GET['condividi']));
            if($_SESSION['report_in_creazione']){
                $lista_giocatori = $_SESSION['report_in_creazione']->get_lista_giocatori();
            }
            */

            $titolo = 'Daiiiiii'; $sottotitolo = 'ad wd awdawdawddawdawdaww awd awda '; $contenuto = 'w dawdawdahdawd awd aw dawdawdawdaw'; 
            $condividi = false; $lista_giocatori = array();






        
            /*
            // PRIMA ALLA CREAZIONE DI report_in_creazione SI È INSERITO IL CORRETTO ID e AUTOR = $_SESSION['username'] ANCHE SE È NULL
            $rep = $_SESSION['report_in_creazione'];
            $titolo = $rep->get_titolo();
            $sottotitolo = $rep->get_sottotitolo();
            $contenuto = $rep->get_contenuto();
            $condividi = $rep->get_condividi();
            $lista_giocatori = $rep->get_lista_giocatori();
            */

            if(  (strlen($titolo) != 0) && (strlen($sottotitolo) != 0) && (strlen($contenuto) != 0)  ){

            //creo l'oggetto report  AUTOR PUO ESSERE NULL (È CORRETTO, serve anche ai salvataggi pendenti)
            $rep = new ReportData($_SESSION['report_id'], $titolo, $sottotitolo, $contenuto, $_SESSION['username'], $condividi, $lista_giocatori);
            //assegno il report così come creato alla variabile che ne tiene conto per ri-riempire la form ad un eventuale ricaricamento
            $_SESSION['report_in_creazione'] = $rep;

            if(isset($_SESSION['username'])) {
                
                $rep->set_author($_SESSION['username']); 

                $dbInterface = new DBinterface();
                $connection = $dbInterface->openConnection();

                if($connection){
                    $result = $toEdit ? $dbInterface->setReport($rep) : $dbInterface->addReport($rep);

                    if($result){
                        $_SESSION['banners']= $toEdit ? "modifica_documento_confermata" : "creazione_documento_confermata";
                        //azzero la form
                        $titolo = ''; $sottotitolo = ''; $contenuto = ''; $condividi = false; $lista_giocatori = array();
                        unset($_SESSION['salvaRep']);
                    }else{
                        //messaggi di errore inserimento nel DB
                        $message = '<div id="errori"><p>Errore nella creazione del report. Riprovare.</p></div>';
                    }
                }
                else{
                    //errore di connessione al DB
                    $message = '<div id="errori"><p>Errore nella creazione del report. Riprovare.</p></div>';
                }
                $dbInterface->closeConnection();
            }else{
                array_push($_SESSION['stagedReports'], $rep);
                $_SESSION['banners']= "salvataggio_pendente";
            }

            }else{
            $message = '<div id="errori" style="text-align: center; color: red; background-color: yellow; padding: 1em; border: 3px solid black;"><ul>'; // TO FIX
            if (strlen($titolo) == 0) {
                $message.='<li>titolo troppo corto</li>';
            }
            if (strlen($sottotitolo) == 0) {
                $message .='<li>sottotitolo troppo corto</li>';
            }
            if (strlen($contenuto) == 0) {
                $message.='<li>contenuto troppo corto</li>';
            }
            $message .= '</ul></div>';
            }
        unset($_SESSION['salvaRep']);
        }
    
        if(isset($_GET['aggiungiGiocatore'])){
            //se ho cliccato su AGGIUNGI per inserire un giocatore nel report, 
            //ora prendo il valore inserito, lo cerco nel database e lo inserisco se esiste,
            //riportando poi il caricamento della form
        
            //devo tenere anche i dati già inseriti, if any
            $titolo = $_GET['titolo'];
            $sottotitolo = $_GET['sottotitolo'];
            $contenuto = $_GET['contenuto'];
            $condividi = (isset($_GET['condividi']));
            if($_SESSION['report_in_creazione']){
                $lista_giocatori = $_SESSION['report_in_creazione']->get_lista_giocatori();
            }


            $dbInterface = new DBinterface();
            $connection = $dbInterface->openConnection();

            if($connection){
                if($dbInterface->existUser($_GET['usernameGiocatore']) && array_search($_GET['usernameGiocatore'],$lista_giocatori) === false){
                    //aggiungo il giocatore alla lista
                    array_push($lista_giocatori,$_GET['usernameGiocatore']);
                    $_SESSION['report_in_creazione']->set_lista_giocatori($lista_giocatori);

                    $feedback_message = '<p id="feedbackAddGiocatore">Il giocatore è stato aggiunto <span class="corretto">correttamente</span> alla lista</p>';
                }
                else if(!(array_search($_GET['usernameGiocatore'],$lista_giocatori) === false)){
                    $feedback_message = '<p id="feedbackAddGiocatore"><span class="scorretto">Il giocatore è già stato aggiunto precedentemente</span></p>';
                }
                else{
                    $feedback_message = '<p id="feedbackAddGiocatore"><span class="scorretto">Non è stato trovato nessun giocatore con questo username</span></p>';
                }
                $html = str_replace('<feedback_placeholder />',$feedback_message,$html);
            }
            else {
                $message = '<div id="errori"><p>Errore nella connessione. Riprovare.</p></div>';
            }

            $dbInterface->closeConnection();

        }
    
        if(isset($_GET['deletePlayer'])){
            //se ho cliccato sulla X che elimina un giocatore devo toglierlo dalla lista
        
            //devo tenere anche i dati già inseriti, if any
            $titolo = $_GET['titolo'];
            $sottotitolo = $_GET['sottotitolo'];
            $contenuto = $_GET['contenuto'];
            $condividi = (isset($_GET['condividi']));
            if($_SESSION['report_in_creazione']){
                $lista_giocatori = $_SESSION['report_in_creazione']->get_lista_giocatori();
            }
        

            //rimuovo dalla lista dei giocatori il giocatore
            $key = array_search($_GET['deletePlayer'],$lista_giocatori);
            unset($lista_giocatori[$key]);

            //aggiorno la lista nell'oggetto report_in_creazione
            $_SESSION['report_in_creazione']->set_lista_giocatori($lista_giocatori);
        }

    }else{
        if ($toEdit) {   // Effettuato solo la prima volta, poi $_GET['salvaPers'] avra' valore

        $dbInterface = new DBinterface();
        $connection = $dbInterface->openConnection();

        if ($connection) {
            $rep = $_SESSION['report_in_creazione'];
            if($rep) {
                $titolo = $rep->get_titolo();
                $sottotitolo = $rep->get_sottotitolo();
                $contenuto = $rep->get_contenuto();
                $condividi = $rep->get_condividi();
                //stampare tutta la lista, foreach
                $lista_giocatori = $rep->get_lista_giocatori();
            }
            else {
                // ERROR PAGE ?
            }
        }
        else {
            // ERROR PAGE ?
        }

        $dbInterface->closeConnection();
    }
    }

    //--------------------------------------------------------------------
    //il contenuto della pagina viene settato qui
    $html = str_replace('<valueTitle />',$titolo,$html);
    $html = str_replace('<valueSubtitle />',$sottotitolo,$html);
    $html = str_replace('<valueContent />',$contenuto,$html);

    $dbInterface = new DBinterface();
    $connection = $dbInterface->openConnection();
    //controllo if($connection) TODO

    $stringa_giocatori = '';
    foreach($lista_giocatori as $singleUser){
        $stringa_giocatori .= '<li>
                                    <div class="badgeUtente">
                                        <div>
                                            <img src="'.$dbInterface->getUserPic($singleUser).'" alt="Immagine di Profilo" />
                                            <p class="textVariable">'.$singleUser.'</p>
                                        </div>
                                        <button title="rimuovi giocatore" class="deleteButton" name="deletePlayer" value="'.$singleUser.'">X</button>
                                    </div>
                                </li>
                                <listaGiocatori />';
    }

    $dbInterface->closeConnection();

    $html = str_replace('<listaGiocatori />',$stringa_giocatori,$html);

    //creo l'oggetto report  AUTOR PUO ESSERE NULL (È CORRETTO, serve anche ai salvataggi pendenti)
    $rep = new ReportData($_SESSION['report_id'], $titolo, $sottotitolo, $contenuto, $_SESSION['username'], $condividi, $lista_giocatori);
    //assegno il report così come creato alla variabile che ne tiene conto per ri-riempire la form ad un eventuale ricaricamento
    $_SESSION['report_in_creazione'] = $rep;

    //modifico il checkbox    
    if($condividi){
        $html = str_replace('{check_placeholder}','checked="checked"',$html);
    }
    else{
        $html = str_replace('{check_placeholder}','',$html);
    }

    $html = addPossibleBanner($html, "CreazioneReportPage.php");

    echo $html;

?>