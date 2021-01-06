<?php
require_once("GeneralPurpose.php");
require_once("banners.php");

clearSession();

if(!isset($_SESSION["login"]) ||  !$_SESSION["login"])
{
    header("Location: login.php");
}
else if($_SESSION["login"])
{
    require_once("DBinterface.php");
    
    $db = new DBinterface();

        /*$character_data = null;
        $report_data = null;
        $author_report_data = null;
        $num_pers = 0;
        $num_report_master = 0;
        $num_report = 0;
        $array_num_part_rep = array();
        $array_num_part_rep_master = array();*/

    try {

        if(!isset($_SESSION["first_logged"]))
        {
            $db->openConnection();
    
            $_SESSION["vai_avanti_master"] = false;
            $_SESSION["vai_avanti_rep"] = false;
            $_SESSION["vai_indietro_master"] = false;
            $_SESSION["vai_indietro_rep"] = false;
            $_SESSION["count_rep"] = 1;
            $_SESSION["count_master"] = 1;
            $_SESSION["first_logged"] = true;
            $_SESSION["character_data"] = $db->getCharactersByUser($_SESSION["username"]);
            $_SESSION["num_pers"] = $db->contaPersonaggi($_SESSION["username"]);
            $_SESSION["num_report"] = $db->countReport($_SESSION["username"]);
            $_SESSION["num_report_master"] = $db->countReportAuthor($_SESSION["username"]);
            $_SESSION["report_data"] = $db->getReportList($_SESSION["username"], $_SESSION["passwd"]);

            //$num_report =  $_SESSION["num_report"]; TO FIX ADD ?
            
            for($i = 0; $i < $num_report; $i++)
            {
                $_SESSION["array_num_part_rep"][$_SESSION["report_data"][$i]->get_title()] = count($db->getALLForReport($_SESSION["report_data"][$i]));
            }

            $_SESSION["author_report_data"] = $db->getReportAuthor($_SESSION["username"]);

            for($i = 0; $i < $num_report; $i++)
            {
                $_SESSION["array_num_part_rep_master"][$_SESSION["author_report_data"][$i]->get_title()] = count($db->getALLForReport($_SESSION["author_report_data"][$i]));
            }

            $db->closeConnection();
        }

        // calcolo numero delle pagine di report
        $numero_pag_report = $_SESSION["num_report"] / 5;
        $numero_pag_master = $_SESSION["num_report_master"] / 5;


        /** controllo se si può andare avanti o indietro */



        if(isset($_SESSION["vai_avanti_master"]) && $_SESSION["vai_avanti_master"])
        {
            $_SESSION["count_master"] == $numero_pag_report ? $_SESSION["count_master"] = $numero_pag_report : $_SESSION["count_master"]++;
            $_SESSION["vai_avanti_master"] = false;
        }

        if(isset($_SESSION["vai_avanti_rep"]) && $_SESSION["vai_avanti_rep"])
        {
            $_SESSION["count_rep"] == $numero_pag_master ? $_SESSION["count_rep"] = $numero_pag_master : $_SESSION["count_rep"]++;
            $_SESSION["vai_avanti_rep"] = false;
        }

        if(isset($_SESSION["vai_indietro_master"]) && $_SESSION["vai_indietro_master"])
        {
            $_SESSION["count_master"] == 1 ? $_SESSION["count_master"] = 1 : $_SESSION["count_master"]--;
            $_SESSION["vai_indietro_master"] = false;
        }

        if(isset($_SESSION["vai_indietro_rep"]) && $_SESSION["vai_indietro_rep"])
        {
            $_SESSION["count_rep"] == 1 ? $_SESSION["count_rep"] = 1 : $_SESSION["count_rep"]--;
            $_SESSION["vai_indietro_rep"] = false;
        }




        /* fine controllo */

        $html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "AreaPersonale.html");
        $html = setup($html);
        if(!$html) 
        {
            header("Location: 404.php");
            exit();
        }
        else
        {

            $html = str_replace("../img/icone_razze/dragonide.png", $_SESSION["img"], $html);
            $html = str_replace("_user_", $_SESSION["username"], $html);
            $html = str_replace("_name_", $_SESSION["name_surname"], $html);
            $html = str_replace("_mail_", $_SESSION["email"], $html);
            $html = str_replace("_date_", $_SESSION["birthdate"], $html);



            $_schede_personaggio = "";

            for($i = 0; $i < $_SESSION["num_pers"] ; $i++)
            {
                $_schede_personaggio .= "<li class=\"cardPersonaggio\"> 
                <button name=\"Personaggio\" value=\"" . $_SESSION["character_data"][$i]->get_id() . "\">
                    <img src=\"" . $_SESSION["character_data"][$i]->get_img() . "\" alt=\"\" /> 
                    <h4 class=\"textVariable\">" . $_SESSION["character_data"][$i]->get_name() . "</h4>
                    <ul>
                        <li><h5>Razza </h5><p classe=\"persRazza\">" . $_SESSION["character_data"][$i]->get_race() . "</p></li>        
                        <li><h5>Classe </h5><p classe=\"persClasse\">" . $_SESSION["character_data"][$i]->get_class() . "</p></li>
                        <li id=\"allineamento\">
                            <fieldset><legend>Allineamento</legend>
                                <p classe=\"persAllineamento\">" . $_SESSION["character_data"][$i]->get_alignment() . "</p>
                            </fieldset>
                        </li>
                    </ul>
                </button>
                </li>\n";
            }

                $html = str_replace("{form_personaggi}", $_schede_personaggio, $html);

                if(isset($SESSION["espandiPers"]) && $_SESSION["espandiPers"] == true)
                {
                    $html = str_replace("<nav class=\"espandi\"> {espandi pers}", "<nav class=\"espandi\" class=\"hidden\">", $html);
                    $html = str_replace("<ul class=\"cards\" Personaggi>", "<ul class=\"expanded\">", $html);
                    $_SESSION["espandiPers"] = false;
                }


                $_schede_report_master = "";

                for($i = ($_SESSION["count_master"]-1)*5 ; $i < $limit = $_SESSION["num_report_master"] < $numero_pag_master ? $limit = $_SESSION["num_report_master"] : $limit = 5*$_SESSION["count_master"] ; $i++)
                {
                    $_schede_report_master .= "<li class=\"cardReport\" class=\"cardReportMaster\">
                    <button name=\"ReportMaster\" value= \"". $_SESSION["author_report_data"][$i]->get_id() . "\">
                        <div>
                        <div class=\"testoCardRep\">
                            <h4 class=\"textVariable\">" . $_SESSION["author_report_data"][$i]->get_title() . "</h4>
                            <p>". $_SESSION["author_report_data"][$i]->get_subtitle() ."</p>
                        </div>
                        </div>
                        <footer>";
                        if($_SESSION["author_report_data"][$i]->get_isExplorable() == true)
                        {
                            $_schede_report_master .= "<p class=\"lableRepPublico\"><span xml:lang=\"en\">Report</span> publico</p>";
                        }
                        $_schede_report_master .= "<p class=\"lableRepPrivato\"><span xml:lang=\"en\">Report</span> condiviso a <span class=\"numCondivisioni\">" . $_SESSION["array_num_part_rep_master"][$_SESSION["author_report_data"][$i]->get_title()] . "</span> giocatori</p>
                        </footer>
                    </button>
                    <div class=\"publicazione\">";
                    if($_SESSION["author_report_data"][$i]->get_isExplorable() == false)    
                    { 
                        $_schede_report_master .= "<button name=\"PostRep\" value=\"". $_SESSION["author_report_data"][$i] . "\">Publica in \"Esplora\"</button>";
                    }
                    else                
                    {                         
                        $_schede_report_master .="<button name=\"RemoveRep\" value=\"". $_SESSION["author_report_data"][$i] . "\">Rimuovi da \"Esplora\"</button>";
                    }
                    
                    $_schede_report_master .= "</div>
                        </li>\n";
                }

                if($_SESSION["count_rep"] == 1)
                {
                    $html = str_replace("<li><label id=\"LblPartecPrecedente\" for=\"partecPrecedente\">precedente</label></li>
                    <li class=\"inputMove\"><input type=\"submit\" id=\"partecPrecedente\" class=\"precedente\" name=\"espandi\" value=\"partecPrecedente\"></li>", " ", $html);
                }

                if($_SESSION["count_rep"] == $numero_pag_report)
                {
                    $html = str_replace("<li class=\"inputMove\"><input type=\"submit\" id=\"partecSuccessivo\" class=\"successivo\" name=\"espandi\" value=\"partecSuccessivo\"></li>  
                    <li><label id=\"LblPartecSuccessivo\" for=\"partecSuccessivo\">successiva</label></li>", " ", $html);
                }

                if($_SESSION["count_rep"] <= 5)
                {
                    $html = str_replace("<nav class=\"espandi\"> {report normali}", "<nav class=\"espandi\" class=\"hidden\">", $html);
                }

                $html = str_replace("{report_author}", $_schede_report_master, $html);
                $html = str_replace("{numero attuale master}", $_SESSION["count_master"], $html);
                $html = str_replace("{numero di master}", $numero_pag_master, $html);


                $_schede_report = "";

                for($i = ($_SESSION["count_rep"]-1)*5 ; $i < $limit = $_SESSION["num_report"] < $numero_pag_master ? $limit = $_SESSION["num_report"] : $limit = 5*$_SESSION["count_master"] ; $i++)
                {
                    $_schede_report .= "<li class=\"cardReport\" class=\"cardReportPartecipante\">
                    <button name=\"ReportPartecip\" value=\"". $_SESSION["report_data"][$i]->get_id() . "\">
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
                        <p class=\"lableRepPrivato\"><span xml:lang=\"en\">Report</span> condiviso a <span class=\"numCondivisioni\">" . $_SESSION["array_num_part_rep"][$_SESSION["report_data"][$i]->get_title()] . "</span> giocatori</p>
                    </footer>
                    </button>
                </li>\n";
                }

                $html = str_replace("{report}", $_schede_report, $html);
                $html = str_replace("{numero attuale report}", $_SESSION["count_rep"], $html);
                $html = str_replace("{numero di report}", $numero_pag_report, $html);

                if($_SESSION["count_master"] == 1)
                {
                    $html = str_replace("<li><label id=\"LblMasterPrecedente\" for=\"masterPrecedente\">precedente</label></li>
                    <li class=\"inputMove\"><input type=\"submit\" id=\"masterPrecedente\" class=\"precedente\" name=\"espandi\" value=\"masterPrecedente\"></li>", " ", $html);
                }

                if($_SESSION["count_master"] == $numero_pag_master)
                {
                    $html = str_replace("<li class=\"inputMove\"><input type=\"submit\" id=\"masterSuccessivo\" class=\"successivo\" name=\"espandi\" value=\"masterSuccessivo\"></li> 
                    <li><label id=\"LblMasterSuccessivo\" for=\"masterSuccessivo\">successiva</label></li>", " ", $html);
                }

                if($_SESSION["count_master"] <= 5)
                {
                    $html = str_replace("<nav class=\"espandi\"> {report master}", "<nav class=\"espandi\" class=\"hidden\">", $html);
                }


                $html = addPossibleBanner($html, "area_personale.php");



                echo $html;

                /*$character_data->free();
                $report_data->free();
                $author_report_data->free();
                $num_pers->free();
                $num_report_master->free();
                $num_report->free();
                $array_num_part_rep->free();
                $array_num_part_rep_master->free();*/

            }

        } catch(Exception $e) {
            header("Location: Errore.php");
            exit();
        }
    }


?>