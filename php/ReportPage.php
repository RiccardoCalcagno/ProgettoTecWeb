<?php

//require
require_once("DBinterface.php");
require_once("GeneralPurpose.php");
require_once("banners.php");

function hasAccess ($report, $usernameArray) {  // True IFF utente ha permessi per visualizzare report (autore, isExplorable o "taggato")

    $hasAccess = false;

    if ( $report->get_author() === $_SESSION['username'] ) {
        $hasAccess = true;
    }

    if ( !$hasAccess && $report->get_isExplorable() ) {
        $hasAccess = true;
    }

    for($i = 0; $i < count($usernameArray) && !$hasAccess; $i++) {
        if ($usernameArray[$i] === $_SESSION['username']) {
            $hasAccess = true;
        }
    }

    return $hasAccess;
}

if ( session_status() == PHP_SESSION_NONE ) {
    session_start();
}

if ( !isset($_GET['ReportID']) ) {
    errorPage('No GET');
}
else {
    //prelevo Report.html
    $html = file_get_contents('..'. DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'Report.html');
    $html = setup($html);
    unset($_SESSION["first_logged"]);
    unset($_SESSION["listaGiocatori"]);

    $dbInterface = new DBinterface();
    $connection = $dbInterface->openConnection();

    $report_info = null;
    $usernameArray = null;
    $userPic = null;
    $commentsArray = null;
    $commenterPic = null;

    if ($connection === true) {

        //prelevo l'oggetto report
        $report_info = $dbInterface->getReport($_GET['ReportID']);

        //faccio subito le richieste al DB per poter chiudere la connessione
        $usernameArray = $report_info->get_lista_giocatori(); //si tratta di un array di username, sono i giocatori collegati al report

        $userPic = array();
        for ($i = 0; $i < count($usernameArray);$i++){
            $userPic[$i] = $dbInterface->getUserPic($usernameArray[$i]);
        }

        $commentsArray = $dbInterface->getComments($report_info->get_id());

        if ( isset($commentsArray) ) {  // Se ci sono commenti
            $commenterPic = array();
            for ($i = 0; $i < count($commentsArray);$i++){
                $commenterPic[$i] = $dbInterface->getUserPic($commentsArray[$i]->get_author());
            }
        }
        
    }

    //chiudo la connessione
    $dbInterface->closeConnection();

    if ( !hasAccess($report_info, $usernameArray) ) {
        errorPage("Non hai i permessi per visualizzare questo report!");
    }

    if( !isset($report_info) ) {    // Resto non serve necessariamente ?
        
        errorPage("Can't connect to DB.");
    }
    else{

        // NO
        // if(isset($_SESSION['documento'])){
        //     header("Location: ReportPage.php");
        //     if($_SESSION['documento']=="ELIMINA"){

        //         $db = new DBinterface();
        //         $openConnection = $db->openConnection();
            
        //         if ($openConnection) {
        //             $result= $db->deleteReport($report_info.get_id());
        //             if(isset($result)){
        //                 header("Location: area_personale.php");
        //             }else{
        //                 // Can't get data from DB
        //                 // ERROR PAGE ? // (ERRORE LATO DB)       
        //             }
        //         }else{
        //             // Can't get data from DB
        //             // ERROR PAGE ? // (ERRORE LATO DB)
        //         }
        //     }
        //     exit();
        // }


        //di seguito tutti gli accorgimenti per stampare le parti prelevate da DB all'interno della pagina Report.html
        //Devo inserire titolo, sottotitolo, contenuto, autore, img_autore, giocatori collegati, commenti, ultima modifica.

            //prelevo il report desiderato, in base all'id contenuto in $selected_report_id
            //$report_info = get_report($selected_report_id);
        //ATTENZIONE, sopra è un alternativa, segue invece come se questa pagina ricevesse direttamente l'oggetto report, $report_info

        //titolo e sottotitolo
        $replacer = '<h1>'.$report_info->get_title().'</h1>'.'<p>'.$report_info->get_subtitle().'</p>';
        $html = str_replace("<TitleAndSub_placeholder/>", $replacer, $html);

        //autore e img
        $replacer = '<h2>Autore</h2>';
        $replacer .= '<div class="badgeUtente">';
        $replacer .= '<img src="'.$report_info->get_author_img().'" alt="Immagine profilo" />';
        $replacer .= '<p class="textVariable">'.$report_info->get_author().'</p>';
        $replacer .= '</div>';
        $html = str_replace("<author_placeholder/>", $replacer, $html);
        
        //ultima modifica
        $replacer = '<h2>Ultima modifica</h2>'.'<p>'.$report_info->get_last_modified().'</p>';
        $html = str_replace("<date_placeholder/>", $replacer, $html);

        //giocatori presenti
        //servirà prelevare le info degli utenti collegati con il report
        $replacer = '<h2>Giocatori presenti</h2>';
        if(count($usernameArray)>0){
            $replacer .= '<ul id="boxGiocatori">';    
            for ($i = 0; $i < count($usernameArray);$i++){// if ==0 => "non ci sono giocatori"
                $replacer .= '<li>';
                $replacer .= '<div class="badgeUtente">';
                $replacer .= '<img src="'.$userPic[$i].'" alt="Immagine profilo" />';
                $replacer .= '<p class="textVariable">'.$usernameArray[$i].'</p>';
                $replacer .= '</div>';
                $replacer .= '</li>';
            }
            $replacer .= '</ul>';
        }else{
            $replacer .= '<p>Non è stato trovato alcun giocatore associato a questo report</p>'; 
        }


        $html = str_replace("<LinkedPlayers_placeholder/>", $replacer, $html);

        //contenuto del report
        $replacer = '<h2>Descrizione della sessione</h2>';
        $replacer .= '<p>'.$report_info->get_content().'</p>';
        $html = str_replace("<content_placeholder/>", $replacer, $html);

        //aggiungi un commento/registrati per commentare
        if(isset($_SESSION["username"])) {
            $replacer = '<div id="InserimentoCommento">
                            <input type="text" placeholder="Lascia un commento.." name="contenutoCommento" />
                            <input type="submit" name="report" value="COMMENTA" class="buttonLink" />
                        </div>';
            $html = str_replace("<InsertComment_placeholder/>", $replacer, $html);
        }
        else{
            $replacer = '<div id="InserimentoCommento">
                            <p>Registrati per lasciare un commento</p>
                        </div>';
            $html = str_replace("<InsertComment_placeholder/>", $replacer, $html);
        }

        //lista dei commenti
        //devo mostrare il commento con tutti i suoi dati, oltre che l'immagine del giocatore (non è un dato del commento)
        $replacer = '<ul id="listaCommenti">';
        if ( isset($commentsArray) ) {

            for($i = 0; $i < count($commentsArray);$i++){
                $replacer .= '<li class="commento"><div class="badgeUtente">';
                $replacer .= '<img src="'.$commenterPic[$i].'" alt="Immagine profilo" />';
                $replacer .= '<p class="textVariable">'.$commentsArray[$i]->get_author().'</p></div>';
                $replacer .= '<div class="testoCommento">';
                $replacer .= '<p>'.$commentsArray[$i]->get_text().'</p>';
                $replacer .= '<p class="dateTimeCommento">'.$commentsArray[$i]->get_date().'</p></div>';      // TO FIX __________________----------------------------------------------------------------------------------------------------------------------
                if($commentsArray[$i]->get_author()==$_SESSION["username"]){
                    $replacer .= '<input title="elimina commento" type="submit" name="eliminaCommento" value="'.$commentsArray[$i]->get_id().'"/></li>';
                }
            }
        }
        /*OLD VERSION
        foreach($commentsArray as $singleComment){
            $replacer .= '<li class="commento"><div class="badgeUtente">';
            $replacer .= '<img src="'.getUserPic($singleComment.get_author()).'" alt="Immagine profilo" />';
            $replacer .= '<p class="textVariable">'.$singleComment.get_author().'</p></div>';
            $replacer .= '<div class="testoCommento">';
            $replacer .= '<p>'.$singleComment.get_text().'</p>';
            $replacer .= '<p class="dateTimeCommento">'.$singleComment.get_date().'</p></div>';
            $replacer .= '<input title="elimina commento" type="submit" name="eliminaCommento" value="IDCommento"/></li>';
            //quest'ultimo è il tasto per eliminare il commento.
            //TODO controllare quando mostrarlo e quando no.
        }
        */
        $replacer .= '</ul>';

        $html = str_replace("<comments_placeholder/>", $replacer, $html);


        //tasti footer
        ////costruisco un if per controllare se l'utente logged in è l'author, se si mostro i tasti
            //ESPLORA
            //controllo che l'utente sia il creatore come prima, ma controllo anche che il report non sia già segnato come pubblico
        $footerAction = '';
        $hiddenReportID = '';

        if($_SESSION["username"]==$report_info->get_author()){  // user e' autore report

            $footerAction = '<form method="GET" action="../php/action_report.php"> 
                            <ul id="footAction">
                            <li> <input type="submit" name="reportAction" value="ELIMINA" class="buttonLink"/> </li>';

            if(!$report_info->get_isExplorable()){

                $footerAction .= '<li> <input type="submit" name="reportAction" value="Pubblica in ESPLORA" class="buttonLink"/> </li>';              
            }      

            $footerAction .=     '<li>
                                <input type="submit" name="reportAction" value="MODIFICA" class="buttonLink"/> 
                            </li>
                        </ul>
                        <input type="hidden" id="ReportID" name="ReportID" value="'. $_GET['ReportID'] . '" />
                    </form>';

            $hiddenReportID = ' <input type="hidden" id="ReportID" name="ReportID" value="'. $_GET['ReportID'] . '" />';
        }
        else if ($report_info->get_isExplorable()) {    // commenti anche se non autore
            $hiddenReportID = ' <input type="hidden" id="ReportID" name="ReportID" value="'. $_GET['ReportID'] . '" />';
        }
        
        $html = str_replace("<footerAction_placeholder/>", $footerAction, $html);
        $html = str_replace("<hiddenReportID />", $hiddenReportID, $html);

        $html = addPossibleBanner($html, "ReportPage.php");

        echo ($html);
    }
}


?>