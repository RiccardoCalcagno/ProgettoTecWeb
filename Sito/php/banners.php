<?php

    /*
    La variabile banners può assumere i seguenti valori:

    null
    salvataggio_pendente
    creazione_documento_confermata
    modifica_documento_confermata
    creazione_utente_confermata
    modifica_utente_confermata
    elementi_salvati
    */

    function addPossibleBanner($html) {
    if($_SESSION['banners']){
        $html = str_replace('</body>', "</body>" . createPossibleBanner(), $html);
        if($_SESSION['banners']=='elementi_salvati'){
            //COSI FUNZIONA?
            if(((isset($_SESSION['stagedReports'])&&($_SESSION['stagedReports']))||(isset($_SESSION['stagedPersonaggi'])&&($_SESSION['stagedPersonaggi'])))){
                foreach ($_SESSION['stagedPersonaggi'] as $i => $value) {unset($_SESSION['stagedPersonaggi'][$i]);}
                foreach ($_SESSION['stagedReports'] as $i => $value) {unset($_SESSION['stagedReports'][$i]);}
            }
        }
        $_SESSION['banners']=null;
    }
    }

    function createPossibleBanner() {
        $htmlBanner="";
        if (isset($_SESSION['banners'])&&($_SESSION['banners'])){

            if(($_SESSION['banners']=="elementi_salvati")&&((isset($_SESSION['stagedReports'])&&($_SESSION['stagedReports']))||(isset($_SESSION['stagedPersonaggi'])&&($_SESSION['stagedPersonaggi'])))){

                $htmlBanner="        
                <fieldset id='bannerSalvataggio'>
                <legend><a xml:lang='en' id='chiusuraBanner'>Close</a></legend>
                <p id='titoloAvviso'>Sono stati salvati i seguenti documenti</p>
                <ul>";

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

                $htmlBanner .="  
                </ul>
                <p>Li puoi trovare nella tua <a href='AreaPersonale.html'>Area Personale</a></p>
                </fieldset>";

            }else{
            $htmlBanner = "<div id='bannerPage' class='transitorio'><div>";
            switch($_SESSION['banners']){
                case "salvataggio_pendente":
                    $htmlBanner .= "        
                    <h1>Salvataggio Pendente</h1>
                    <h2>La creazione del documento è avvenuta <strong class='corretto'>correttamente</strong> ma per poter essere salvato è necessaria un' <strong class='scorretto'>autenticazione</strong> </h2>
                    <p id='PsalvataggioPendente'>Quando ti è possibile esegui l'accesso o l'iscrizione e il tuo documento 
                        verrà automaticamente salvato nella tua <a href='AreaPersonale.html'>Area Personale</a></p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='login.html'>ACCESSO</a>
                        <a class='buttonLink' href=''>ISCRIZIONE</a>
                    </div>
                    <a class='buttonLink' href='../Home.html'>HOME</a>";
                break;
                case "creazione_documento_confermata":
                    $htmlBanner .= "    
                    <div id='closeDirettamente'>
                    <a href='../Home.html'></a>                          
                    </div>
                    <h1>Creazione Confermata</h1>
                    <p>Confermiamo che la creazione del documento è avvenuta <strong class='corretto'>correttamente</strong></p>
                    <p id='refAreaPersConf'>Quando vorrai potrai recuperare questo speciale manufatto nella tua </br><a href='AreaPersonale.html'>Area Personale</a></p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='../Home.html' xml:lang='en'>HOME</a>
                    </div>";
                break;
                case "modifica_documento_confermata":
                    $htmlBanner .= "
                    <div id='closeDirettamente'>
                    <a href='../Home.html'></a>                          
                    </div>
                        <h1>Modifica Confermata</h1>
                        <p>Confermiamo che la modifica del documento è avvenuta <strong class='corretto'>correttamente</strong></p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='AreaPersonale.html'>AREA PERSONALE</a>
                        <a class='buttonLink' href='Esplora.html'>ESPLORA</a>
                    </div>";
                break;
                case "creazione_utente_confermata":
                    $htmlBanner .= "
                    <div id='closeDirettamente'>
                    <a href='../Home.html'></a>                          
                    </div>
                    <h1>Registrazione Confermata</h1>
                    <p>Le confermiamo che la sua registrazione è avvenuta <strong class='corretto'>correttamente</strong></p>
                    <p id='refAreaPersConf'> Scopri subito cosa può offrirti la tua personalissima </br><a href='AreaPersonale.html'>Area Personale</a></p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='../Home.html' xml:lang='en'>HOME</a>
                    </div>";
                break;
                case "modifica_utente_confermata":
                    $htmlBanner .= "
                    <div id='closeDirettamente'>
                        <a href='../Home.html'></a>                          
                    </div>
                    <h1>Modifica utente confermata</h1>
                    <p>Le confermiamo che la modifica alle informazioni di utenza è avvenuta <strong class='corretto'>correttamente</strong></p>
                    <div id='linkVelociPostConferma'>
                        <a class='buttonLink' href='AreaPersonale.html'>AREA PERSONALE</a>
                        <a class='buttonLink' href='../Home.html' xml:lang='en'>HOME</a>
                    </div>";
                break;
            }

            $htmlBanner .= "</div></div>";
        }

        }
        return $htmlBanner;
    }

?>
