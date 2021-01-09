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
    }
    if(isset($_SESSION['banners']) && strpos($_SESSION['banners'],'elementi_salvati')){
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

            echo $_SESSION['banners'];
            exit();


            
            if((strpos($_SESSION['banners'],'elementi_salvati'))&&((isset($_SESSION['stagedReports'])&&($_SESSION['stagedReports']))||(isset($_SESSION['stagedPersonaggi'])&&($_SESSION['stagedPersonaggi'])))){
                $htmlBanner="
                    <fieldset id='bannerSalvataggio'>
                    <legend><a xml:lang='en' href='../php/" . $returnPage . "' id='chiusuraBanner'>Close</a></legend>";
                if($_SESSION['banners']="elementi_salvati_errore"){
                    $htmlBanner.="<p id='titoloAvviso'>Sono stati riscontrati errori nel salvataggio</p><ul>";  
                }else{
                    $htmlBanner.="<p id='titoloAvviso'>Sono stati salvati i seguenti documenti</p><ul>";
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
                if($_SESSION['banners']="elementi_salvati_errore"){
                    $htmlBanner .="  
                    </ul>
                    <p>Ci spiace al momento i nostri server sembrano non funzionare</p>
                    </fieldset>";
                }else{
                    $htmlBanner .="  
                    </ul>
                    <p>Li puoi trovare nella tua <a href='../php/area_personale.php'>Area Personale</a></p>
                    </fieldset>";
                }


            }else{
            $htmlBanner = "<div id='bannerPage' class='transitorio'><div>";
            switch($_SESSION['banners']){
                case "salvataggio_pendente":
                    $htmlBanner .= "        
                    <h1>Salvataggio Pendente</h1>
                    <h2>La creazione del documento è avvenuta <strong class='corretto'>correttamente</strong> ma per poter essere salvato è necessaria un' <strong class='scorretto'>autenticazione</strong> </h2>
                    <p id='PsalvataggioPendente'>Quando ti è possibile esegui l'accesso o l'iscrizione e il tuo documento 
                        verrà automaticamente salvato nella tua <a href='../php/area_personale.php'>Area Personale</a></p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='../php/login.php'>ACCESSO</a>
                        <a class='buttonLink' href='../php/register.php'>ISCRIZIONE</a>
                    </div>
                    <a class='buttonLink' href='../index.php'>HOME</a>";
                break;
                case "creazione_documento_confermata":
                    $htmlBanner .= "    
                    <div id='closeDirettamente'>
                    <a href='PLACEHOLDER' ></a>                          
                    </div>
                    <h1>Creazione Confermata</h1>
                    <p>Confermiamo che la creazione del documento è avvenuta <strong class='corretto'>correttamente</strong></p>
                    <p id='refAreaPersConf'>Quando vorrai potrai recuperare questo speciale manufatto nella tua </br><a href='../php/area_personale.php'>Area Personale</a></p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='../index.php' xml:lang='en'>HOME</a>
                    </div>";
                break;
                case "modifica_documento_confermata":
                    $htmlBanner .= "
                    <div id='closeDirettamente'>
                    <a href='PLACEHOLDER' ></a>                          
                    </div>
                        <h1>Modifica Confermata</h1>
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
                    <h1>Registrazione Confermata</h1>
                    <p>Le confermiamo che la sua registrazione è avvenuta <strong class='corretto'>correttamente</strong></p>
                    <p id='refAreaPersConf'> Scopri subito cosa può offrirti la tua personalissima </br><a href='../php/area_personale.php'>Area Personale</a></p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='../index.php' xml:lang='en'>HOME</a>
                    </div>";
                break;
                case "modifica_utente_confermata":
                    $htmlBanner .= "
                    <div id='closeDirettamente'>
                        <a href='PLACEHOLDER' ></a>                          
                    </div>
                    <h1>Modifica utente confermata</h1>
                    <p>Le confermiamo che la modifica alle informazioni di utenza è avvenuta <strong class='corretto'>correttamente</strong></p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='../php/area_personale.php'>AREA PERSONALE</a>
                        <a class='buttonLink' href='../index.php' xml:lang='en'>HOME</a>
                    </div>";
                break;
                case "confermare_eliminazione_personaggio":
                    $htmlBanner .= "
                    <h1>Confermare Eliminazione</h1>
                    <h2>Sei sicuro di voler eliminare questa scheda giocatore?</h2>
                    <p>A seguito dell'operazione non sarà più possibile recuperare il documento</p>
                    <form id='linkVelociPostConferma' method='POST' action='action_character.php'>
                        <a class='annulla' href='../php/CharacterPage.php?Personaggio=".$_SESSION['banners_ID']."'>ANNULLA</a>
                        <input type='submit' class='buttonLink' name='documento' value='ELIMINA'/>
                        <input type='hidden' id='charID' name='charID' value=".$_SESSION['banners_ID']." />
                    </form>";
                    unset($_SESSION['banners_ID']);
                break;
                case "confermare_eliminazione_report":
                    $htmlBanner .= "
                    <h1>Confermare Eliminazione</h1>
                    <h2>Sei sicuro di voler eliminare questo report di sessione?</h2>
                    <p>A seguito dell'operazione non sarà più possibile recuperare il documento e i commenti ad esso associati</p>
                    <form id='linkVelociPostConferma' method='POST' action='../php/action_report.php'>
                        <a class='annulla' href='../php/ReportPage.php'>ANNULLA</a>
                        <input type='submit' class='buttonLink' name='documento' value='ELIMINA'/>
                    </form>";
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
