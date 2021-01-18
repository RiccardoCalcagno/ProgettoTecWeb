<?php

    /*
    La variabile banners può assumere i seguenti valori:

    null
    salvataggio_pendente
    creazione_documento_confermata
    modifica_documento_confermata
    creazione_utente_confermata
    modifica_utente_confermata
    confermare_eliminazione_personaggio
    confermare_eliminazione_report
    elementi_salvati
    pubblica_esplora_eplora_confermata
    */

    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    function staged_session() { // Da chiamare a inizio codice ogni volta che si possono usare staged

        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION['stagedPersonaggi'])) {
            $_SESSION['stagedPersonaggi'] = array();
        }

        if(!isset($_SESSION['stagedReports'])) {
            $_SESSION['stagedReports'] = array();
        }
        
    }

    function addPossibleBanner($html, $returnPage) {
    $banner = createPossibleBanner($returnPage);
    if($banner!=""){
        $html = str_replace('</body>', "</body>" . $banner , $html);
        $html = str_replace('</body', "</body tabindex='-1' ", $html);
    }
    if(isset($_SESSION['banners']) && strpos($_SESSION['banners'],'lementi_salvati')){
        if(isset($_SESSION['stagedReports'])){
            $_SESSION['stagedReports']=null;
        }
        if(isset($_SESSION['stagedPersonaggi'])){
            $_SESSION['stagedPersonaggi']=null;
        }
        /*
        if(((isset($_SESSION['stagedReports'])&&($_SESSION['stagedReports']))||(isset($_SESSION['stagedPersonaggi'])&&($_SESSION['stagedPersonaggi'])))){
            foreach ($_SESSION['stagedPersonaggi'] as $i => $value) {unset($_SESSION['stagedPersonaggi'][$i]);}
            foreach ($_SESSION['stagedReports'] as $i => $value) {unset($_SESSION['stagedReports'][$i]);}
        }
        */
    }
    $_SESSION['banners']=null;
    
    return $html;
    }

    function createPossibleBanner($returnPage) {
        $htmlBanner="";

        if (isset($_SESSION['banners'])&&($_SESSION['banners'])){

            if((strpos($_SESSION['banners'],'lementi_salvati'))&&((isset($_SESSION['stagedReports'])&&($_SESSION['stagedReports']))||(isset($_SESSION['stagedPersonaggi'])&&($_SESSION['stagedPersonaggi'])))){
                $htmlBanner="
                    <fieldset id='bannerSalvataggio'>
                    <legend><a xml:lang='en' href='../php/" . $returnPage . "' id='chiusuraBanner'>Close</a></legend>";
                if($_SESSION['banners']=="elementi_salvati_errore"){
                    $htmlBanner.="<p id='titoloAvviso' tabindex='1'>Sono stati riscontrati errori nel salvataggio</p><ul>";  
                }else{
                    $htmlBanner.="<p id='titoloAvviso' tabindex='1'>Sono stati salvati i seguenti documenti</p><ul>";
                }

                if(isset($_SESSION['stagedReports'])&&($_SESSION['stagedReports'])){

                        foreach($_SESSION['stagedReports'] as &$report){
                            $htmlBanner .="<li>Report: " . $report->get_title() . "</li>";
                        }
                }
                if(isset($_SESSION['stagedPersonaggi'])&&($_SESSION['stagedPersonaggi'])){

                    foreach($_SESSION['stagedPersonaggi'] as &$personaggio){
                        $htmlBanner .="<li>Personaggio: " . $personaggio->get_name() . "</li>";
                    }
                }
                if($_SESSION['banners']=="elementi_salvati_errore"){
                    $htmlBanner .="  
                    </ul>
                    <p>Ci spiace al momento i nostri server sembrano non funzionare</p>
                    </fieldset>";
                }else{
                    $htmlBanner .="  
                    </ul>
                    <p>Li puoi trovare nella tua Area Personale</p>
                    </fieldset>";
                }


            }else{
            $htmlBanner = "<div id='bannerPage' class='transitorio'><div>";
            switch($_SESSION['banners']){
                case "salvataggio_pendente":
                    $htmlBanner .= "      
                    <div id='closeDirettamente'>
                    <a href='PLACEHOLDER' ></a>                          
                    </div>  
                    <h1 tabindex='1' aria-label='messaggio di conferma: salvataggio pendente'>Salvataggio Pendente</h1>
                    <h2>La creazione del documento è avvenuta <strong class='corretto'>correttamente</strong> ma per poter essere salvato è necessaria un' <strong class='scorretto'>autenticazione</strong> </h2>
                    <p id='PsalvataggioPendente'>Quando ti è possibile esegui l'accesso o l'iscrizione e il tuo documento 
                        verrà automaticamente salvato nella tua Area Personale</p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='../php/login.php'>ACCESSO</a>
                        <a class='buttonLink' href='../php/register.php'>ISCRIZIONE</a>
                    </div>";
                break;
                case "creazione_documento_confermata":
                    $htmlBanner .= "    
                    <div id='closeDirettamente'>
                    <a href='PLACEHOLDER' ></a>                          
                    </div>
                    <h1 tabindex='1' aria-label='messaggio di conferma: Creazione Confermata'>Creazione Confermata</h1>
                    <p>Confermiamo che la creazione del documento è avvenuta <strong class='corretto'>correttamente</strong></p>
                    <p id='refAreaPersConf'>Quando vorrai potrai recuperare questo speciale manufatto nella tua </br><a href='../php/area_personale.php'>Area Personale</a></p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='../index.php' xml:lang='en' lang='en'>HOME</a>
                    </div>";
                break;
                case "modifica_documento_confermata":
                    $htmlBanner .= "
                    <div id='closeDirettamente'>
                    <a href='PLACEHOLDER' ></a>                          
                    </div>
                        <h1 tabindex='1' aria-label='messaggio di conferma: Modifica confermata'>Modifica Confermata</h1>
                        <p>Confermiamo che la modifica del documento è avvenuta <strong class='corretto'>correttamente</strong></p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='../php/area_personale.php'>AREA PERSONALE</a>
                        <a class='buttonLink' href='../php/EsploraPage.php'>ESPLORA</a>
                    </div>";
                break;
                case "creazione_utente_confermata":
                    $htmlBanner .= "
                    <div id='closeDirettamente'>
                    <a href='PLACEHOLDER' ></a>                          
                    </div>
                    <h1 tabindex='1' aria-label='messaggio di conferma: Registrazione Confermata'>Registrazione Confermata</h1>
                    <p>Le confermiamo che la sua registrazione è avvenuta <strong class='corretto'>correttamente</strong></p>
                    <p id='refAreaPersConf'> Scopri subito cosa può offrirti la tua personalissima </br><a href='../php/area_personale.php'>Area Personale</a></p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='../index.php' xml:lang='en' lang='en'>HOME</a>
                    </div>";
                break;
                case "modifica_utente_confermata":
                    $htmlBanner .= "
                    <div id='closeDirettamente'>
                        <a href='PLACEHOLDER' ></a>                          
                    </div>
                    <h1 tabindex='1' aria-label='messaggio di conferma: Modifica utente confermata'>Modifica utente confermata</h1>
                    <p>Le confermiamo che la modifica alle informazioni di utenza è avvenuta <strong class='corretto'>correttamente</strong></p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='../php/area_personale.php'>AREA PERSONALE</a>
                        <a class='buttonLink' href='../index.php' xml:lang='en' lang='en'>HOME</a>
                    </div>";
                break;
                case "confermare_eliminazione_personaggio":
                    $htmlBanner .= "
                    <h1 tabindex='1' aria-label='messaggio di richiesta di conferma: Confermare Eliminazione'>Confermare Eliminazione</h1>
                    <h2>Sei sicuro di voler eliminare questa scheda giocatore?</h2>
                    <p>A seguito dell'operazione non sarà più possibile recuperare il documento</p>
                    <form id='linkVelociPostConferma' method='POST' action='action_character.php'>
                        <div>
                            <a class='annulla' href='../php/CharacterPage.php?Personaggio=".$_SESSION['banners_ID']."'>ANNULLA</a>
                            <input type='submit' class='buttonLink' name='documento' value='ELIMINA'/>
                            <input type='hidden' id='charID' name='charID' value=".$_SESSION['banners_ID']." />
                        </div>
                    </form>";
                    unset($_SESSION['banners_ID']);
                break;
                case "confermare_eliminazione_report":
                    $htmlBanner .= "
                    <h1 tabindex='1' aria-label='messaggio di richiesta di conferma: confermare eliminazione'>Confermare Eliminazione</h1>
                    <h2>Sei sicuro di voler eliminare questo <span xml:lang=\"en\" lang=\"en\">report</span> di sessione?</h2>
                    <p>A seguito dell'operazione non sarà più possibile recuperare il documento e i commenti ad esso associati</p>
                    <form id='linkVelociPostConferma' method='POST' action='../php/action_report.php'>
                        <div>
                            <a class='annulla' href='../php/ReportPage.php?ReportID=".$_SESSION['banners_ID']."'>ANNULLA</a>
                            <input type='submit' class='buttonLink' name='documento' value='ELIMINA'/>
                            <input type='hidden' id='ReportID' name='ReportID' value=".$_SESSION['banners_ID']." />
                        </div>
                    </form>";
                    unset($_SESSION['banners_ID']);
                break;
                case "confermare_eliminazione_commento":
                    $htmlBanner .= "
                    <h1 tabindex='1' aria-label='messaggio di richiesta di conferma: confermare eliminazione'>Confermare Eliminazione</h1>
                    <h2>Sei sicuro di voler eliminare questo commento?</h2>
                    <p>A seguito dell'operazione non sarà più possibile recuperare il contenuto del commento</p>
                    <form id='linkVelociPostConferma' method='POST' action='../php/action_report.php'>
                        <div>
                            <a class='annulla' href='../php/ReportPage.php?ReportID=".$_SESSION['banners_ID']['ReportID']."'>ANNULLA</a>
                            <input type='submit' class='buttonLink' name='documento' value='ELIMINA COMMENTO'/>
                            <input type='hidden' id='ReportID' name='ReportID' value=".$_SESSION['banners_ID']['ReportID']." />
                            <input type='hidden' id='CommentID' name='CommentID' value=".$_SESSION['banners_ID']['CommentID']." />
                        </div>
                    </form>";
                    unset($_SESSION['banners_ID']);
                break;
                case "pubblica_esplora_eplora_confermata":
                    $htmlBanner .= "
                    <div id='closeDirettamente'>
                    <a href='PLACEHOLDER' ></a>                          
                    </div>
                        <h1 tabindex='1' aria-label='messaggio di conferma: Publicazione confermata'>Pubblicazione Confermata</h1>
                        <p>Confermiamo che la pubblicazion del <span xml:lang=\"en\" lang=\"en\">report</span> nell'area Esplora è avvenuta <strong class='corretto'>correttamente</strong></p>
                        <p>Dai subito un'occhiata al tuo <span xml:lang=\"en\" lang=\"en\">Report</span> nella sezione Esplora</p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='PLACEHOLDER'>CHIUDI</a>
                        <a class='buttonLink' href='../php/EsploraPage.php'>ESPLORA</a>
                    </div>";
                    unset($_SESSION['banners_ID']);
                break;
            }

            $htmlBanner .= "</div></div>";
            //if($returnPage="index.php"){$htmlBanner =str_replace("../","",$htmlBanner);}
            $htmlBanner =str_replace("PLACEHOLDER",$returnPage,$htmlBanner);
        }

        }
        return $htmlBanner;
    }

?>
