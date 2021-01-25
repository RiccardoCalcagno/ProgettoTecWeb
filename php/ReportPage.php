<?php

require_once("DBinterface.php");
require_once("GeneralPurpose.php");
require_once("banners.php");

function hasAccess ($report, $usernameArray) {

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
    header("Location: 404.php");
    exit();
}
else {

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


        $report_info = $dbInterface->getReport($_GET['ReportID']);
        if(!$report_info){ 
            $dbInterface->closeConnection(); 
            header("Location: 404.php");
            exit();
        }

        $usernameArray = $report_info->get_lista_giocatori();

        $userPic = array();
        for ($i = 0; $i < count($usernameArray);$i++){
            $userPic[$i] = $dbInterface->getUserPic($usernameArray[$i]);
        }

        $commentsArray = $dbInterface->getComments($report_info->get_id());

        if ( isset($commentsArray) ) {  
            $commenterPic = array();
            for ($i = 0; $i < count($commentsArray);$i++){
                $commenterPic[$i] = $dbInterface->getUserPic($commentsArray[$i]->get_author());
            }
        }
        
    }else{
        errorPage("EDB");
        exit();
    }

    $dbInterface->closeConnection();

    if ( !hasAccess($report_info, $usernameArray) ) {
        errorPage("Ci spiace informarla che non siamo riusciti a verificare i suoi diritti di visualizzazione per questo Report");
        exit();
    }

    if( !isset($report_info) ) {    
        
        header("Location: 404.php");exit();
    }
    else{

        $replacer = '<h1 id="titolo" tabindex="0">'.$report_info->get_title().'</h1>'.'<p>'.$report_info->get_subtitle().'</p>';
        $html = str_replace("<TitleAndSub_placeholder/>", $replacer, $html);

        $replacer = '<h2>Autore</h2>';
        $replacer .= '<div class="badgeUtente">';
        $replacer .= '<img src="'.$report_info->get_author_img().'" alt="immagine profilo inserita da utente" />';
        $replacer .= '<p class="textVariable">'.$report_info->get_author().'</p>';
        $replacer .= '</div>';
        $html = str_replace("<author_placeholder/>", $replacer, $html);
        
        $replacer = '<h2>Ultima modifica</h2>'.'<p>'.$report_info->get_last_modified().'</p>';
        $html = str_replace("<date_placeholder/>", $replacer, $html);

        $replacer = '<h2>Giocatori presenti</h2>';
        if(count($usernameArray)>0){
            $replacer .= '<ul id="boxGiocatori">';    
            for ($i = 0; $i < count($usernameArray);$i++){
                $replacer .= '<li>';
                $replacer .= '<div class="badgeUtente">';
                $replacer .= '<img src="'.$userPic[$i].'" alt="immagine profilo inserita da utente" />';
                $replacer .= '<p class="textVariable">'.$usernameArray[$i].'</p>';
                $replacer .= '</div>';
                $replacer .= '</li>';
            }
            $replacer .= '</ul>';
        }else{
            $replacer .= '<p>Non è stato trovato alcun giocatore associato a questo <span xml:lang="en" lang="en">report</span></p>'; 
        }


        $html = str_replace("<LinkedPlayers_placeholder/>", $replacer, $html);

        $replacer = '<h2>Descrizione della sessione</h2>';
        $replacer .= '<p>'.$report_info->get_content().'</p>';
        $html = str_replace("<content_placeholder/>", $replacer, $html);

        if(isset($_SESSION["username"])) {
            $replacer = '<div id="InserimentoCommento">
                            <label for="textComment" class="AiutiNavigazione">Digita un commento</label>
                            <input type="text" id="textComment" placeholder="Lascia un commento.." name="contenutoCommento" />
                            <input type="submit" name="report" value="COMMENTA" class="buttonLink" aria-label="Commenta " />
                        </div>';
            $html = str_replace("<InsertComment_placeholder/>", $replacer, $html);
        }
        else{
            $replacer = '<div id="InserimentoCommento">
                            <p>Registrati per lasciare un commento</p>
                        </div>';
            $html = str_replace("<InsertComment_placeholder/>", $replacer, $html);
        }

        $replacer = '';
        if ( !empty($commentsArray) ) {
            $replacer = '<ul id="listaCommenti">';

            for($i = 0; $i < count($commentsArray);$i++){
                $replacer .= '<li class="commento"><div class="badgeUtente">';
                $replacer .= '<img src="'.$commenterPic[$i].'" alt="immagine profilo inserita da utente" />';
                $replacer .= '<p class="textVariable">'.$commentsArray[$i]->get_author().'</p></div>';
                $replacer .= '<div class="testoCommento">';
                $replacer .= '<p>'.$commentsArray[$i]->get_text().'</p>';
                $replacer .= '<p class="dateTimeCommento">'.$commentsArray[$i]->get_date().'</p></div>'; 
                if($commentsArray[$i]->get_author()==$_SESSION["username"]){
                    $replacer .= '<button title="elimina commento" type="submit" name="eliminaCommento" value="'.$commentsArray[$i]->get_id().'">Elimina commento: '.$commentsArray[$i]->get_text().'</button>';
                }
                $replacer .= '</li>';
            }

            $replacer .= '</ul>';
        }


        $html = str_replace("<comments_placeholder/>", $replacer, $html);

        $footerAction = '';
        $hiddenReportID = '';

        if($_SESSION["username"]==$report_info->get_author()){ 

            $footerAction = '<form method="get" action="../php/action_report.php"> 
                            <ul id="footAction">
                            <li> <input type="submit" name="reportAction" value="ELIMINA" class="buttonLink" aria-label="Elimina questo report"/> </li>';

            if(!$report_info->get_isExplorable()){

                $footerAction .= '<li> <input type="submit" name="reportAction" value="Pubblica in ESPLORA" class="buttonLink" aria-label="Pubblica in esplora il Report e rendilo pubblico"/> </li>';              
            }else{
                $footerAction .= '<li> <input type="submit" name="reportAction" value="Rimuovi da ESPLORA" class="buttonLink" aria-label="Rimuovi da esplora il Report e rendilo privato"/> </li>';
            }

            $footerAction .=     '<li>
                                <input type="submit" name="reportAction" value="MODIFICA" class="buttonLink" aria-label="Modifica questo report"/> 
                            </li>
                        </ul>
                        <div>
                            <input type="hidden" name="ReportID" value="'. $_GET['ReportID'] . '" />
                        </div>
                    </form>';
        }
        
        $hiddenReportID = ' <input type="hidden" name="ReportID" value="'. $_GET['ReportID'] . '" />';
        
        $html = str_replace("<footerAction_placeholder/>", $footerAction, $html);
        $html = str_replace("<hiddenReportID />", $hiddenReportID, $html);


        if((isset($_GET["Hamburger"])) && ($_GET["Hamburger"]=="yes")){
            $html = str_replace("class=\"hideForHamburger\" ", " ", $html);
            $html = str_replace("{RedirectHamburger}", "../php/ReportPage.php?Hamburger=no&ReportID=".$_GET['ReportID'], $html);
        }else{
            $html = str_replace("{RedirectHamburger}", "../php/ReportPage.php?Hamburger=yes&ReportID=".$_GET['ReportID'], $html);
        }

        $html = addPossibleBanner($html, "ReportPage.php?ReportID=".$_GET['ReportID']);

        echo ($html);
    }
}


?>