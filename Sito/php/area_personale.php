<?php

    if(!isset($_SESSION))
    {
        header("Location: login.php");
    }
    else if($_SESSION["login"])
    {
        require_once("DBinterface.php");
    
        $db = new DBinterface();

        $character_data = null;
        $report_data = null;
        $author_report_data = null;
        $num_pers = 0;
        $num_report_master = 0;
        $num_report = 0;
        $array_num_part_rep = array();
        $array_num_part_rep_master = array();

        try {
            $db->openConnection();
    
            $character_data = $db->getCharacterByUser($_SESSION["username"]);
            $num_pers = $db->contaPersonaggi($_SESSIONE["username"]);
            $num_report = $db->countReport($_SESSION["username"]);
            $num_report_master = $db->countReportAuthor($_SESSION["username"]);
            $report_data = $db->getReportList($_SESSION["username"], $_SESSION["passwd"]);
            
            for($i = 0; $i < $num_report; $i++)
            {
                $array_num_part_rep[$report_data[$i]->get_title()] = count($db->getALLForReport($report_data[$i]));
            }

            $author_report_data = $db->getReportAuthor($_SESSION["username"]);

            for($i = 0; $i < $num_report; $i++)
            {
                $array_num_part_rep_master[$author_report_data[$i]->get_title()] = count($db->getALLForReport($report_data[$i]));
            }

            $db->closeConnection();

            $html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "AreaPersonale.html");
            if(!$html) 
            {
                // pagina di errore
            }
            else
            {

                $html = str_replace("../images/icone_razze/dragonide.png", $_SESSION["img"], $html);
                $html = str_replace("_user_", $_SESSION["username"], $html);
                $html = str_replace("_name_", $_SESSION["name_surname"], $html);
                $html = str_replace("_mail_", $_SESSION["email"], $html);
                $html = str_replace("_date_", $_SESSION["birthdate"], $html);



                $_schede_personaggio = "";

                for($i = 0; $i < $num_pers; $i++)
                {
                    $_schede_personaggio .= "<li class=\"cardPersonaggio\"> 
                    <button name=\"Personaggio\" value=\"" . $character_data[$i]->get_id() . "\">
                        <img src=\"" . $character_data[$i]->get_img() . "\" alt=\"\" /> 
                        <h4 class=\"textVariable\">" . $character_data[$i]->get_name() . "</h4>
                        <ul>
                            <li><h5>Razza </h5><p classe=\"persRazza\">" . $character_data[$i]->get_race() . "</p></li>        
                            <li><h5>Classe </h5><p classe=\"persClasse\">" . $character_data[$i]->get_class() . "</p></li>
                            <li id=\"allineamento\">
                                <fieldset><legend>Allineamento</legend>
                                    <p classe=\"persAllineamento\">" . $character_data[$i]->get_alignment() . "</p>
                                </fieldset>
                            </li>
                        </ul>
                    </button>
                    </li>\n";
                }

                $html = str_replace("{form_personaggi}", $_schede_personaggio, $html);


                $_schede_report_master = "";

                for($i = 0; $i < $num_report_master; $i++)
                {
                    $_schede_report_master .= "<li class=\"cardReport\" class=\"cardReportMaster\">
                    <button name=\"ReportMaster\" value= \"". $author_report_data[$i]->get_id() . "\">
                        <div>
                        <div class=\"testoCardRep\">
                            <h4 class=\"textVariable\">" . $author_report_data[$i]->get_title() . "</h4>
                            <p>". $author_report_data[$i]->get_subtitle() ."</p>
                        </div>
                        </div>
                        <footer>
                            <p class=\"lableRepPublico\"><span xml:lang=\"en\">Report</span> publico</p>
                            <p class=\"lableRepPrivato\"><span xml:lang=\"en\">Report</span> condiviso a <span class=\"numCondivisioni\">" . $array_num_part_rep_master[$author_report_data[$i]->get_title()] . "</span> giocatori</p>
                        </footer>
                    </button>
                    <div class=\"publicazione\">";
                    if($author_report_data[$i]->get_isExplorable() == false)    
                    { 
                        $_schede_report_master .= "<button name=\"PostRep\" value=\"". $author_report_data[$i]->get_id() . "\">Publica in \"Esplora\"</button>";
                    }
                    else                
                    {                         
                        $_schede_report_master .="<button name=\"RemoveRep\" value=\"". $author_report_data[$i]->get_id() . "\">Rimuovi da \"Esplora\"</button>";
                    }
                    
                    $_schede_report_master .= "</div>
                        </li>\n";
                }

                $html = str_replace("{report_author}", $_schede_report_master, $html);


                $_schede_report = "";

                for($i = 0; $i < $num_report; $i++)
                {
                    $_schede_report .= "<li class=\"cardReport\" class=\"cardReportPartecipante\">
                    <button name=\"ReportPartecip\" value=\"". $author_report_data[$i]->get_id() . "\">
                        <div class=\"testoCardRep\">
                            <h4 class=\"textVariable\">". $report_data[$i]->get_title() ."</h4>
                            <p> ". $report_data[$i]->get_subtitle() . "</p>
                        </div>
                        <div class=\"badgeUtente\">
                            <h5>Autore</h5>
                            <img src=\"../images/icone_razze/nano.png\" alt=\"\" /> 
                            <p class=\"textVariable\">" . $author_report_data[$i]->get_author() . "</p>
                        </div>
                    <footer>
                        <p class=\"lableRepPrivato\"><span xml:lang=\"en\">Report</span> condiviso a <span class=\"numCondivisioni\">" . $array_num_part_rep[$report_data[$i]->get_title()] . "</span> giocatori</p>
                    </footer>
                    </button>
                </li>\n";
                }

                $html = str_replace("{report}", $_schede_report, $html);

                echo $html;


            }

        } catch(Exception $e) {
            // pagina di errore 
            exit();
        }


    


        
    }
?>