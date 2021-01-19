<?php


require_once("DBinterface.php"); 
require_once("GeneralPurpose.php");
require_once("banners.php");
require_once("report_data.php");




$db = new DBinterface();
$connection = $db->openConnection();

unset($_SESSION["first_logged"]);
unset($_SESSION["listaGiocatori"]);

if($connection == false){
    errorPage("EDB");
    exit();
}
else{

    $html = file_get_contents('..'. DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'Esplora.html');

    $temp = 1;
    if(isset($_SESSION["count_esplora"])){
        $temp=$_SESSION["count_esplora"];
    }
    $html = setup($html);
    $_SESSION["count_esplora"] = $temp;

    $_SESSION["report_data"] = $db->getReportExplorable();// getReportExplorable();     // DA METTERE
    $_SESSION["num_report_esplora"] = count($_SESSION["report_data"]);


    //echo "<!DOCTYPE html><html lang='it' ><head>  </head> <body><h1>" .$_SESSION["num_report_esplora"]." -rep: ".$_SESSION['report_data']."</h1></body></html>";
    //exit();


    for($i = 0; $i < $_SESSION["num_report_esplora"]; $i++)
    {
        $_SESSION["array_num_part_rep_esplora"][$_SESSION["report_data"][$i]->get_id()] = count($db->getALLUsernamesForReport($_SESSION["report_data"][$i]->get_id()));
    }
    $db->closeConnection();
    $numero_pag_esplora = ($_SESSION["num_report_esplora"]==0)? 0 : ((int)(($_SESSION["num_report_esplora"] -1) / 5) +1);


    /*
    $html = file_get_contents('..'. DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'Esplora.html');
    $html = setup($html);
    $_SESSION["vai_avanti_esplora"] = false;
    $_SESSION["vai_indietro_esplora"] = false;
    $_SESSION["count_esplora"] = 1;
    $_SESSION["num_report_esplora"] = 1;      // DA METTERE
    $_SESSION["report_data"] = array(new ReportData(1,'Iniziare una Locanda','Il modo più classico di iniziare una campagna può essere inaspettato?','Potete Appena Ci siamolocanda alla fine è rimasto ben poco LOL','Grog',true,['hey','gio','come','stai']));
    $numero_pag_esplora = 2;

    for($i = 0; $i < $_SESSION["num_report_esplora"]; $i++)
    {
        $_SESSION["array_num_part_rep_esplora"][$_SESSION["report_data"][$i]->get_id()] = 3;
    }
*/




    /** controllo se si può andare avanti o indietro */
    if(isset($_SESSION["vai_avanti_esplora"]) && $_SESSION["vai_avanti_esplora"])
    {
        $_SESSION["count_esplora"] == $numero_pag_esplora ? $_SESSION["count_esplora"] = $numero_pag_esplora : $_SESSION["count_esplora"]++;
        $_SESSION["vai_avanti_esplora"] = false;
    }
    if(isset($_SESSION["vai_indietro_esplora"]) && $_SESSION["vai_indietro_esplora"])
    {
        $_SESSION["count_esplora"] == 1 ? $_SESSION["count_esplora"] = 1 : $_SESSION["count_esplora"]--;
        $_SESSION["vai_indietro_esplora"] = false;
    }

    $_schede_report_esplora = "";

    for($i = ($_SESSION["count_esplora"]-1)*5 ; $i < $limit = ($_SESSION["num_report_esplora"] < $_SESSION["count_esplora"]*5 ? $_SESSION["num_report_esplora"] : 5*$_SESSION["count_esplora"]) ; $i++)
        {
        $_schede_report_esplora .=   
        "<li class=\"cardReport cardReportEsplora\">
        <button name=\"ReportEsplora\" value=\"". $_SESSION["report_data"][$i]->get_id() . "\">
            <div class=\"testoCardRep\">
                <h4 class=\"textVariable\">". $_SESSION["report_data"][$i]->get_title() ."</h4>
                <p> ". $_SESSION["report_data"][$i]->get_subtitle() . "</p>
            </div>
            <div class=\"badgeUtente\">
                <h5>Autore</h5>
                <img src=\"" . $_SESSION["report_data"][$i]->get_author_img() ."\" alt=\"\" /> 
                <p class=\"textVariable\">" . $_SESSION["report_data"][$i]->get_author() . "</p>
            </div>
        <footer>
            <p class=\"lableRepPublico\"><span xml:lang=\"en\" lang=\"en\">Report</span> condiviso a <span class=\"numCondivisioni\">" . $_SESSION["array_num_part_rep_esplora"][$_SESSION["report_data"][$i]->get_id()] . "</span> giocatori</p>
        </footer>
        </button>
        </li>\n";
        }  
        
    if($_SESSION["count_esplora"] == 1)
        {
        $html = str_replace("<li><label id=\"LblEsploraPrecedente\" for=\"esploraPrecedente\" aria-label=\"passa alla pagina precedente della dashboard di Report\">precedente</label></li>", " ", $html);
        $html = str_replace("<li class=\"inputMove\"><input type=\"submit\" id=\"esploraPrecedente\" class=\"precedente\" name=\"espandi\" value=\"esploraPrecedente\"></li>", " ", $html);
        }

    if($_SESSION["count_esplora"] == $numero_pag_esplora)
        {
        $html = str_replace("<li class=\"inputMove\"><input type=\"submit\" id=\"esploraSuccessivo\" class=\"successivo\" name=\"espandi\" value=\"esploraSuccessivo\"></li>", " ", $html); 
        $html = str_replace("<li><label id=\"LblEsploraSuccessivo\" for=\"esploraSuccessivo\" aria-label=\"passa alla pagina successiva della dashboard di Report\">successiva</label></li>", " ", $html); 
        }

    if($numero_pag_esplora <= 1)
        {
        $html = str_replace("<nav class=\"espandi\">", "<nav class=\"hidden\">", $html);
        }


    $html = str_replace("<reports/>", $_schede_report_esplora, $html);
    $html = str_replace("<currentPageReport/>", $_SESSION["count_esplora"], $html);
    $html = str_replace("<totPagReport/>", $numero_pag_esplora, $html);


    $html = addPossibleBanner($html, "EsploraPage.php");

    echo $html;
    }

?>