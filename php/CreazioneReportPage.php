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
            $headTitle = '<title>Modifica Report di Sessione - D&Diary</title>
            <meta name="title" content="Modifica Report di Sessione" />
            <meta name="description" content="Modifica il report di sessione che precedentemente hai creato" />';

            $header = '<header id="intestazionePagina">
            <h1 id="content" tabindex="0">Modifica <span xml:lang="en" lang="en">Report</span> di Sessione <span> <a class="puntoInterrogativo" aria-label="Cosa è un Report di Sessione?"  
                href="../php/Approf_report.php">?</a></span></h1>
            </header>';

            $p = '<p>Concludi la modifica salvando la nuova versione del <span xml:lang="en" lang="en">report</span> nella tua Area Personale</p>';

            $button = '<input id="buttonPartecip" class="buttonLink" type="submit" name="salvaRep" value="SALVA MODIFICA" aria-label="Salva modifica effettuata al tuo Report"/>';
        }
        else {
            $headTitle = '<title>Creazione Report di Sessione - D&Diary</title>
            <meta name="title" content="Creazione Report di Sessione" />
            <meta name="description" content="Crea il tuo report di sessione!" />';

            $header = '<header id="intestazionePagina">
            <h1 id="content" tabindex="0">Creazione <span xml:lang="en" lang="en">Report</span> di Sessione <span> <a class="puntoInterrogativo" aria-label="Cosa è un Report di Sessione?"  href="../php/Approf_report.php">?</a></span></h1>
            <p>Sei qui per realizzare il tuo primo <span xml:lang="en" lang="en">report</span> di sessione? Non temere, segui questi semplici 
                passaggi e in breve il tuo ricordo sarà condensato in un <span xml:lang="en" lang="en">report</span> da mostrare a chi vorrai. </p>
            <p class="attenzioneP">(<strong class="Attenzione">Attenzione</strong>: per effettuare il salvataggio del <span xml:lang="en" lang="en">report</span> sarà necessaria una tua autenticazione)</p>
            </header>';

            $p = '<p>Concludi la creazione salvando il nuovo <span xml:lang="en" lang="en">report</span> nella tua Area Personale</p>';

            $button = '<input id="buttonPartecip" class="buttonLink" type="submit" name="salvaRep" value="SALVA REPORT" aria-label="Salva report appena compilato"/>';
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
    $titolo = ''; $sottotitolo = ''; $contenuto = ''; $condividi = 0; $message = ''; $feedback_message = ''; $aiutiNav = '';
    if ( isset($_SESSION['id_report_modifica']) ) {
        $toEdit =  true;
        $id_report = $_SESSION['id_report_modifica'];
    }else{
        $id_report=null;
    }


    $html = file_get_contents('..'.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'creazioneReport.html');
    $html = setup($html);
    $html = preparePage($html,$toEdit);
    if(isset($id_report)&&($id_report!==null))
        $_SESSION['id_report_modifica']=$id_report;

    if(isset($_SESSION['listaGiocatori'])){

        if(   (isset($_GET['salvaRep']))  ||  (isset($_GET['aggiungiGiocatore']))  ||  (isset($_GET['deletePlayer']))   ){

            $titolo = $_GET['titolo'];
            $sottotitolo = $_GET['sottotitolo'];
            $contenuto = $_GET['contenuto'];
            $condividi = (int)(isset($_GET['condividi']));
    
 

            if(isset($_GET['salvaRep'])){
    
                if( strlen($titolo) <= 30 && strlen($titolo)>=3 && strlen($sottotitolo) <= 120 && strlen($sottotitolo)>=3 && strlen($contenuto) >=3  ){
    
                if(isset($_SESSION['username'])) {

                    $rep = new ReportData($id_report, $titolo, $sottotitolo, $contenuto, $_SESSION['username'], $condividi, $_SESSION['listaGiocatori']);

                    $dbInterface = new DBinterface();
                    $connection = $dbInterface->openConnection();
    
                    if($connection){
                        $result = $toEdit ? $dbInterface->setReport($rep) : $dbInterface->addReport($rep);
                        $dbInterface->closeConnection();

                        if($result){
                            $_SESSION['banners']= $toEdit ? "modifica_documento_confermata" : "creazione_documento_confermata";
                            //azzero la form
                            $titolo = ''; $sottotitolo = ''; $contenuto = ''; $condividi = 0; unset($_SESSION['listaGiocatori']);
                        }else{
                            errorPage("EDB");exit();
                        }
                    }
                    else{
                        errorPage("EDB");exit();
                    }
                }else{
                    $rep = new ReportData($id_report, $titolo, $sottotitolo, $contenuto, null, $condividi, $_SESSION['listaGiocatori']);
                    array_push($_SESSION['stagedReports'], $rep);
                    $_SESSION['banners']= "salvataggio_pendente";
                }
    
                }else{
                $message = '<div id="errori" class="" tabindex="10" aria-label="sono stati riscontrati alcuni errori. ti trovi all\' inizio della lista di input"><ul>'; // TO FIX
                if ( (strlen($titolo) > 30 || strlen($titolo)<3)) {
                    $message.='<li role=\"alert\">Titolo non valido! Il titolo deve avere una lunghezza compresa tra i 3 e 30 caratteri</li>';
                }
                if ((strlen($sottotitolo) > 120 || strlen($sottotitolo)<3)) {
                    $message .='<li role=\"alert\">Sottotitolo non valido! Il sottotitolo deve avere una lunghezza compresa tra i 3 e 120 caratteri</li>';
                }
                if (strlen($contenuto) < 3) {
                    $message.='<li role=\"alert\">Contenuto non valido! Il contenuto deve avere almeno 3 caratteri</li>';
                }
                $message .= '</ul></div>';
                }
            }
    


            if(isset($_GET['aggiungiGiocatore'])){

                $dbInterface = new DBinterface();
                $connection = $dbInterface->openConnection();
    
                if($connection){
                    if( ($dbInterface->existUser($_GET['usernameGiocatore'])) && (array_search($_GET['usernameGiocatore'],$_SESSION['listaGiocatori']) === false) ){
                        //aggiungo il giocatore alla lista
                        array_push($_SESSION['listaGiocatori'],$_GET['usernameGiocatore']);
    
                        $feedback_message = '<p id="feedbackAddGiocatore" role=\"alert\">Il giocatore è stato aggiunto <span class="corretto">correttamente</span> alla lista</p>';
                    }
                    else if(!(array_search($_GET['usernameGiocatore'],$_SESSION['listaGiocatori']) === false)){
                        $feedback_message = '<p id="feedbackAddGiocatore" role=\"alert\"><span class="scorretto">Il giocatore è già stato aggiunto precedentemente</span></p>';
                    }
                    else{
                        $feedback_message = '<p id="feedbackAddGiocatore" role=\"alert\"><span class="scorretto">Non è stato trovato nessun giocatore con questo username</span></p>';
                    }
                    $aiutiNav = '<a href="../php/CreazioneReportPage.php#writeUsername">torna all\' aggiunta di giocatori</a>';
                    $dbInterface->closeConnection();
                }
                else {
                    errorPage("EDB");exit();
                }
    
            }



            if(isset($_GET['deletePlayer'])){

                $key = array_search($_GET['deletePlayer'],$_SESSION['listaGiocatori']);
                unset($_SESSION['listaGiocatori'][$key]);
            }

        }

        ////wd awdhawhjdg djh abdbah bdjhawbdhjawbdhjbadjhba hjdbahbwdh d
        // wdh jwdja djbaw dbawjhbd ahdhbawhdb ajwhd awdjabhdj abdhahbwd awd 

        //a wkjdjkaw bdab bawdkba bdabw baw bdaw daw anw dnabnb

    }else{
        $_SESSION['listaGiocatori']= array();

        if ($toEdit) {  

        $dbInterface = new DBinterface();
        $connection = $dbInterface->openConnection();

        if ($connection) {
            $rep = $dbInterface->getReport($id_report);
            $dbInterface->closeConnection();

            if($rep) {

                $titolo = $rep->get_title();
                $sottotitolo = $rep->get_subtitle();
                $contenuto = $rep->get_content();
                $condividi = $rep->get_isExplorable();

                
                $_SESSION['listaGiocatori']= $rep->get_lista_giocatori();
            }
            else {
                errorPage("EDB");exit();
            }
        }
        else {
            errorPage("EDB");exit();
        }
    }
    }


    //--------------------------------------------------------------------
    //il contenuto della pagina viene settato qui
    $html = str_replace("<messaggioForm />", $message, $html);
    $html = str_replace('<valueTitle />',$titolo,$html);
    $html = str_replace('<valueSubtitle />',$sottotitolo,$html);
    $html = str_replace('<valueContent />',$contenuto,$html);
    $html = str_replace('<feedback_placeholder />',$feedback_message,$html);
    $html = str_replace('<altriAiutiDiNavigazione/>',$aiutiNav, $html);

    $dbInterface = new DBinterface();
    if(!$dbInterface->openConnection()){errorPage("EDB");exit();}

    $stringa_giocatori = '';
    if ( !empty($_SESSION['listaGiocatori']) ) {

        $stringa_giocatori = '';

        foreach($_SESSION['listaGiocatori'] as $singleUser){

            $pic = $dbInterface->getUserPic($singleUser);
            if(!$pic){$dbInterface->closeConnection(); errorPage("EDB");exit();}
            $stringa_giocatori .= '<li>
                                        <div class="badgeUtente">
                                            <div>
                                                <img src="'.$pic.'" alt="immagine profilo inserita da utente" />
                                                <p class="textVariable">'.$singleUser.'</p>
                                            </div>
                                            <button class="deleteButton" name="deletePlayer" aria-label="Rimuovi '. "$singleUser". ' dai partecipanti al Report"value="'.$singleUser.'">X</button>
                                        </div>
                                    </li>';
        }
    }
    

    $dbInterface->closeConnection();

    $html = str_replace('<li>Qui verranno visualizzati i giocatori inseriti</li>',$stringa_giocatori,$html);

    /*
    //creo l'oggetto report  AUTOR PUO ESSERE NULL (È CORRETTO, serve anche ai salvataggi pendenti)
    $rep = new ReportData($_SESSION['report_id'], $titolo, $sottotitolo, $contenuto, $_SESSION['username'], $condividi, $_SESSION['listaGiocatori']);
    //assegno il report così come creato alla variabile che ne tiene conto per ri-riempire la form ad un eventuale ricaricamento
    $_SESSION['report_in_creazione'] = $rep;
    */

    //modifico il checkbox    
    if($condividi){
        $html = str_replace('{check_placeholder}','checked="checked"',$html);
    }
    else{
        $html = str_replace('{check_placeholder}','',$html);
    }

    if((isset($_GET["Hamburger"])) && ($_GET["Hamburger"]=="yes")){
        $html = str_replace("class=\"hideForHamburger\" ", " ", $html);
        $html = str_replace("{RedirectHamburger}", "../php/CreazioneReportPage.php?Hamburger=no", $html);
    }else{
        $html = str_replace("{RedirectHamburger}", "../php/CreazioneReportPage.php?Hamburger=yes", $html);
    }


    $html = addPossibleBanner($html, "CreazioneReportPage.php");

    echo $html;

?>