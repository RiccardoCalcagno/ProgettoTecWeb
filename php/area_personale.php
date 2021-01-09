<?php
require_once("GeneralPurpose.php");
require_once("banners.php");

clearSession();

/*
$_SESSION["username"]="QueenAdministrator";
$_SESSION["passwd"]="1000BimbiFucsia";
$_SESSION["login"]=true;
$_SESSION["img"]="../img/icone_razze/dragonide.png";
$_SESSION["name_surname"]="HEyla";
$_SESSION["email"]="lollolooll@gmail.com";
$_SESSION["birthdate"]="1999-06-12";
*/

if(!isset($_SESSION["login"]) || !$_SESSION["login"])
{
    header("Location: login.php");
    exit();
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
            $num_report =  $_SESSION["num_report"];
            
            for($i = 0; $i < $num_report; $i++)
            {
                $_SESSION["array_num_part_rep"][$_SESSION["report_data"][$i]->get_id()] = count($db->getALLForReport($_SESSION["report_data"][$i]->get_id()));
            }

            $_SESSION["author_report_data"] = $db->getReportAuthor($_SESSION["username"]);

            for($i = 0; $i < $num_report; $i++)
            {
                $_SESSION["array_num_part_rep_master"][$_SESSION["author_report_data"][$i]->get_id()] = count($db->getALLForReport($_SESSION["author_report_data"][$i]->get_id()));
            }

            $db->closeConnection();

        }

        // calcolo numero delle pagine di report
        $numero_pag_report = ($_SESSION["num_report"]==0)? 0 : ((int)(($_SESSION["num_report"] -1) / 5) +1);
        $numero_pag_master = ($_SESSION["num_report_master"]==0)? 0 : ((int)(($_SESSION["num_report_master"] -1) / 5) +1);


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
            if($_SESSION["img"] == "")
            {
                $_SESSION["img"] = ".." . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "img_profilo_mancante.png";
            }

            $html = str_replace("../img/icone_razze/dragonide.png", $_SESSION["img"], $html);
            $html = str_replace("_user_", $_SESSION["username"], $html);
            $html = str_replace("_name_", $_SESSION["name_surname"], $html);
            $html = str_replace("_mail_", $_SESSION["email"], $html);
            $html = str_replace("_date_", $_SESSION["birthdate"], $html);


            //  ---------------------------------------------------------------------------------------------------------------------------
            //                                                  SCHEDE GIOCATORE
            // ----------------------------------------------------------------------------------------------------------------------------

            $_schede_personaggio = "";

            for($i = 0; $i < $_SESSION["num_pers"] ; $i++)                                              //   DA IMPLEMENTARE L'IMMAGINE CON UNO SWITCH SU RACE
            {
                $urlImgRace="../img/icone_razze/";
                switch($_SESSION["character_data"][$i]->get_race()){
                case 'Umano': $urlImgRace.="umano.png\" alt='volto di una giovane donna di colore ornata di gioielli'";break;
                case 'Elfo': $urlImgRace.="elfo.png\" alt='volto di elfo incappucciato con una faccia truce e scura'";break;
                case 'Nano': $urlImgRace.="nano.png\" alt='volto di una nano fantasy con capelli rossi e con un espressione arrabbiata'";break;
                case 'Halfling': $urlImgRace.="halfing.png\" alt='volto di una piccola donna che sta ridendo, ha un naso a punta e sopracciglia maliziose'";break;
                case 'Gnome': $urlImgRace.="gnomo.png\" alt='volto di un piccolo essere dai lineamenti femminili orecchie a punta capelli lunghi al vento'";break;
                case 'Tiefling': $urlImgRace.="tiefling.png\" alt='volto di donna con pelle di colore rossastro capelli lunghi neri e orecchie a punta, in abito nobile'";break;
                case 'Dragonide': $urlImgRace.="dragonide.png\" alt='essere dal volto simile a quello di un drago con squame rosse'";break;
                case 'Mezzelfo': $urlImgRace.="mezzelfo.png\" alt='volto di un umano sereno con una corona in testa e orecchie a punta non accentuata'";break;
                case 'Mezzorco': $urlImgRace.="mezzorco.png\" alt='volto di un orchessa con lunghi capelli marroni, pelle violacea, piccole zanne alla bocca e orecchie a punta'";break;
                }

                $_schede_personaggio .= "<li class=\"cardPersonaggio\"> 
                <button name=\"Personaggio\" value=\"" . $_SESSION["character_data"][$i]->get_id() . "\">
                    <img src=\"" . $urlImgRace . " />                 
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
            if($_SESSION["num_pers"]==0){
                $_schede_personaggio .= "<p class='mancanoCards' >Qui verranno inserite le schede giocatore che realizzerai</p>";
            }

                $html = str_replace("<form_personaggi/>", $_schede_personaggio, $html);

                if((isset($SESSION["espandiPers"]) && $_SESSION["espandiPers"] == true) || $_SESSION["num_pers"] <= 4)
                {
                    $html = str_replace("<nav class=\"espandi\" id='espandi_pers'>", "<nav class=\"hidden\">", $html);
                    $html = str_replace("<ul class=\"cards\" id='Personaggi'>", "<ul class=\"expanded\">", $html);

                    $_SESSION["espandiPers"] = false;
                }


                //  ---------------------------------------------------------------------------------------------------------------------------
                //                                                  REPORT MASTER
                // ----------------------------------------------------------------------------------------------------------------------------


                $_schede_report_master = "";

                for($i = ($_SESSION["count_master"]-1)*5 ; $i < $limit = ($_SESSION["num_report_master"] < $_SESSION["count_master"]*5 ? $_SESSION["num_report_master"] : 5*$_SESSION["count_master"]) ; $i++)
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
                        else
                        {
                            $_schede_report_master .= "<p class=\"lableRepPrivato\"><span xml:lang=\"en\">Report</span> condiviso a <span class=\"numCondivisioni\">" . $_SESSION["array_num_part_rep_master"][$_SESSION["author_report_data"][$i]->get_id()] . "</span> giocatori</p>";
                        }
                        $_schede_report_master .= "</footer>
                    </button>
                    <div class=\"publicazione\">";
                    if($_SESSION["author_report_data"][$i]->get_isExplorable() == false)    
                    { 
                        $_schede_report_master .= "<button name=\"PostRep\" value=\"". $_SESSION["author_report_data"][$i]->get_id() . "\">Publica in \"Esplora\"</button>";
                    }
                    else                
                    {                         
                        $_schede_report_master .="<button name=\"RemoveRep\" value=\"". $_SESSION["author_report_data"][$i]->get_id() . "\">Rimuovi da \"Esplora\"</button>";
                    }
                    
                    $_schede_report_master .= "</div>
                        </li>\n";
                }

                if($_SESSION["num_report_master"]==0){
                    $_schede_report_master .= "<p class='mancanoCards' >Qui verranno inseriti i report di sessione che realizzerai</p>";
                }

                if($_SESSION["count_master"] == 1)
                {
                    $html = str_replace("<li><label id=\"LblMasterPrecedente\" for=\"masterPrecedente\">precedente</label></li>", " ", $html);
                    $html = str_replace("<li class=\"inputMove\"><input type=\"submit\" id=\"masterPrecedente\" class=\"precedente\" name=\"espandi\" value=\"masterPrecedente\"></li>", " ", $html);
                }

                if($_SESSION["count_master"] == $numero_pag_master)
                {
                    $html = str_replace("<li class=\"inputMove\"><input type=\"submit\" id=\"masterSuccessivo\" class=\"successivo\" name=\"espandi\" value=\"masterSuccessivo\"></li>", " ", $html);
                    $html = str_replace("<li><label id=\"LblMasterSuccessivo\" for=\"masterSuccessivo\">successiva</label></li>", " ", $html);
                }

                if($numero_pag_master <= 1)
                {
                    $html = str_replace("<nav class=\"espandi\" id='report_master'>", "<nav id='report_master' class=\"hidden\">", $html);
                }

                $html = str_replace("<report_author/>", $_schede_report_master, $html);
                $html = str_replace("<numero_attuale_master/>", $_SESSION["count_master"], $html);
                $html = str_replace("<numero_di_master/>", $numero_pag_master, $html);


                //  ---------------------------------------------------------------------------------------------------------------------------
                //                                                  REPORT PARTECIPANTE
                // ----------------------------------------------------------------------------------------------------------------------------


                $_schede_report = "";

                for($i = ($_SESSION["count_rep"]-1)*5 ; $i < $limit = ($_SESSION["num_report"] <= $_SESSION["count_rep"]*5 ? $_SESSION["num_report"] : 5*$_SESSION["count_rep"]); $i++)
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
                    <footer>";
                    if($_SESSION["report_data"][$i]->get_isExplorable() == true)
                    {
                        $_schede_report .= "<p class=\"lableRepPublico\"><span xml:lang=\"en\">Report</span> publico</p>";
                    }
                    else
                    {
                        $_schede_report .= "<p class=\"lableRepPrivato\"><span xml:lang=\"en\">Report</span> condiviso a <span class=\"numCondivisioni\">" . $_SESSION["array_num_part_rep"][$_SESSION["report_data"][$i]->get_id()] . "</span> giocatori</p>";
                    }
                    $_schede_report .= "</footer>
                    </button>
                </li>\n";
                }

                if($_SESSION["num_report"]==0){
                    $_schede_report .= "<p class='mancanoCards' >Non appena verrai citato come giocatore in qualche report di sessione vedrai apparire qui quei report</p>";
                }

                if($_SESSION["count_rep"] == 1)
                {
                    $html = str_replace("<li><label id=\"LblPartecPrecedente\" for=\"partecPrecedente\">precedente</label></li>", " ", $html);
                    $html = str_replace("<li class=\"inputMove\"><input type=\"submit\" id=\"partecPrecedente\" class=\"precedente\" name=\"espandi\" value=\"partecPrecedente\"></li>", " ", $html);
                }

                if($_SESSION["count_rep"] == $numero_pag_report)
                {
                    $html = str_replace("<li class=\"inputMove\"><input type=\"submit\" id=\"partecSuccessivo\" class=\"successivo\" name=\"espandi\" value=\"partecSuccessivo\"></li>", " ", $html);
                    $html = str_replace("<li><label id=\"LblPartecSuccessivo\" for=\"partecSuccessivo\">successiva</label></li>", " ", $html);
                }

                if($numero_pag_report <= 1)
                {
                    $html = str_replace("<nav class=\"espandi\" id='report_normale'>", "<nav id='report_normale' class=\"hidden\">", $html);
                }

                $html = str_replace("<report/>", $_schede_report, $html);
                $html = str_replace("<numero_attuale_report/>", $_SESSION["count_rep"], $html);
                $html = str_replace("<numero_di_report/>", $numero_pag_report, $html);







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
