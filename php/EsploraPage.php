<?php

//require
require_once("DBinterface.php");
echo "Error: " . $sql . "hey what fuck" . mysqli_error($con);
require_once("GeneralPurpose.php");
require_once("banners.php");

//prelevo Esplora.html

$dbInterface = new DBinterface();
$connection = $dbInterface->openConnection();

if($connection == false){
    header("Location: Errore.php");
    exit();
}
else{

    $html = file_get_contents('..'. DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'Esplora.html');
    $html = setup($html);

    $_SESSION["vai_avanti_esplora"] = false;
    $_SESSION["vai_indietro_esplora"] = false;
    $_SESSION["count_esplora"] = 1;
    $_SESSION["num_report_esplora"] = $db->countReport($_SESSION["username"]);      // DA METTERE
    $_SESSION["report_data"] = $db->getReportList($_SESSION["username"], $_SESSION["passwd"]);     // DA METTERE
    $numero_pag_esplora = $_SESSION["num_report_esplora"] / 5;

    for($i = 0; $i < $numero_pag_esplora; $i++)
    {
        $_SESSION["array_num_part_rep_esplora"][$_SESSION["report_data"][$i]->get_id()] = count($db->getALLForReport($_SESSION["report_data"][$i]));
    }

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

    for($i = ($_SESSION["count_esplora"]-1)*5 ; $i < $limit = $_SESSION["num_report_esplora"] < $numero_pag_esplora ? $limit = $_SESSION["num_report_esplora"] : $limit = 5*$_SESSION["count_esplora"] ; $i++)
        {
        $_schede_report_esplora .=   
        "<li class=\"cardReport\" class=\"cardReportEsplora\">
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
            <p class=\"lableRepPublico\"><span xml:lang=\"en\">Report</span> condiviso a <span class=\"numCondivisioni\">" . $_SESSION["array_num_part_rep_esplora"][$_SESSION["report_data"][$i]->get_id()] . "</span> giocatori</p>
        </footer>
        </button>
        </li>\n";
        }  
        
    if($_SESSION["count_esplora"] == 1)
        {
        $html = str_replace("<li><label id=\"LblEsploraPrecedente\" for=\"esploraPrecedente\">precedente</label></li>
        <li class=\"inputMove\"><input type=\"submit\" id=\"esploraPrecedente\" class=\"precedente\" name=\"espandi\" value=\"esploraPrecedente\"></li>", " ", $html);
        }

    if($_SESSION["count_esplora"] == $numero_pag_esplora)
        {
        $html = str_replace("<li class=\"inputMove\"><input type=\"submit\" id=\"esploraSuccessivo\" class=\"successivo\" name=\"espandi\" value=\"esploraSuccessivo\"></li> 
        <li><label id=\"LblEsploraSuccessivo\" for=\"esploraSuccessivo\">successiva</label></li>", " ", $html);
        }

    if($_SESSION["count_esplora"] <= 5)
        {
        $html = str_replace("<nav class=\"espandi\">", "<nav class=\"espandi\" class=\"hidden\">", $html);
        }


    $html = str_replace("<reports/>", $_schede_report_esplora, $html);
    $html = str_replace("<currentPageReport/>", $_SESSION["count_esplora"], $html);
    $html = str_replace("<totPagReport/>", $numero_pag_esplora, $html);


$html = addPossibleBanner($html, "EsploraPage.php");

echo $html;

?>