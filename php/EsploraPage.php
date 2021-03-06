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

    $_SESSION["report_data"] = $db->getReportExplorable();
    $_SESSION["num_report_esplora"] = count($_SESSION["report_data"]);

    for($i = 0; $i < $_SESSION["num_report_esplora"]; $i++)
    {
        $_SESSION["array_num_part_rep_esplora"][$_SESSION["report_data"][$i]->get_id()] = count($db->getALLUsernamesForReport($_SESSION["report_data"][$i]->get_id()));
    }
    $db->closeConnection();
    $numero_pag_esplora = ($_SESSION["num_report_esplora"]==0)? 0 : ((int)(($_SESSION["num_report_esplora"] -1) / 5) +1);

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
    $contaReport=0;

    for($i = ($_SESSION["count_esplora"]-1)*5 ; $i < $limit = ($_SESSION["num_report_esplora"] < $_SESSION["count_esplora"]*5 ? $_SESSION["num_report_esplora"] : 5*$_SESSION["count_esplora"]) ; $i++)
        {
        $contaReport++;
        $_schede_report_esplora .=   
        "<li class=\"cardReport cardReportEsplora\">
        <div id='reportJSid". $contaReport ."' class=\"phpCard\" onclick=\"visualizzaReportEsplora(". $_SESSION["report_data"][$i]->get_id() .");\">
            <div class=\"testoCardRep\">
                <div>
                    <button name=\"ReportEsplora\" value=\"". $_SESSION["report_data"][$i]->get_id() . "\" class=\"buttonLink\" 
                    aria-label='Vedi il Report intitolato: ".$_SESSION["report_data"][$i]->get_title()."'>VEDI<span class=\"hidden\"> il Report intitolato: ".$_SESSION["report_data"][$i]->get_title()."</span></button>
                </div>
                <h3 class=\"textVariable\">". $_SESSION["report_data"][$i]->get_title() ."</h3>
                <p> ". $_SESSION["report_data"][$i]->get_subtitle() . "</p>
            </div>
            <div class=\"badgeUtente\">
                <h4>Autore</h4>
                <img src=\"" . $_SESSION["report_data"][$i]->get_author_img() ."\" alt=\"immagine profilo inserita da utente\" /> 
                <p class=\"textVariable\">" . $_SESSION["report_data"][$i]->get_author() . "</p>
            </div>
        <footer>
            <p class=\"lableRepPublico\"><span xml:lang=\"en\" lang=\"en\">Report</span> condiviso a <span class=\"numCondivisioni\">" . $_SESSION["array_num_part_rep_esplora"][$_SESSION["report_data"][$i]->get_id()] . "</span> giocatori</p>
        </footer>
        </div>
        </li>\n";
        }  
        
    if($_SESSION["count_esplora"] == 1)
        {
            $html = str_replace('<li class="inputMove"><button type="submit" id="esploraPrecedente" class="precedente" name="espandi" value="esploraPrecedente" aria-label="Visualizza la pagina di Report precedente">Precedente</button></li>', '', $html);
        }

    if($_SESSION["count_esplora"] == $numero_pag_esplora)
        {
            $html = str_replace('<li class="inputMove"><button type="submit" id="esploraSuccessivo" class="successivo" name="espandi" value="esploraSuccessivo" aria-label="Visualizza la pagina di Report Successiva">Successiva</button></li> ', '', $html); 
        }

    if($numero_pag_esplora <= 1)
        {
            $html = str_replace("<nav class=\"espandi\"", "<nav class=\"hidden\"", $html);
        }


    $html = str_replace("<reports/>", $_schede_report_esplora, $html);
    $html = str_replace("<currentPageReport/>", $_SESSION["count_esplora"], $html);
    $html = str_replace("<totPagReport/>", $numero_pag_esplora, $html);

    if((isset($_GET["Hamburger"])) && ($_GET["Hamburger"]=="yes")){
        $html = str_replace("class=\"hideForHamburger\" ", " ", $html);
        $html = str_replace("{RedirectHamburger}", "../php/EsploraPage.php?Hamburger=no", $html);
    }else{
        $html = str_replace("{RedirectHamburger}", "../php/EsploraPage.php?Hamburger=yes", $html);
    }

    $html = addPossibleBanner($html, "EsploraPage.php");

    echo $html;
    }

?>