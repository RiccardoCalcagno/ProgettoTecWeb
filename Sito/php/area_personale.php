<?php

    if(!isset($_SESSION))
    {
        header("Location: login.php");
    }
    else if($_SESSION["login"])
    {
        require_once("DBinterface.php");
    
        $db = new DBinterface();

        $user_data = null;
        $character_data = null;
        $report_data = null;
        $author_report_data = null;
        $num_pers = 0;
        $num_report_master = 0;
        $num_report = 0;
        $array_num_part_rep = array();

        try {
            $db->openConnection();
    

            $user_data = $db->getUser($_SESSION["username"], $_SESSION["passwd"]);
            if($user_data)
            {
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
            }
            else
            {
                $db->closeConnection();
                // errore
            }

            $db->closeConnection();

            $html = file_get_contents("../otherHTMLs/AreaPersonale.html");
            if(!$html) 
            {
                // pagina di errore
            }
            else
            {
                /**
                * replace per l'utente
                */
                $html = str_replace("../images/icone_razze/dragonide.png", $user_data->get_img_path(), $html);
                $html = str_replace("MasterAlessandro", $user_data->get_username(), $html);
                $html = str_replace("Alessandro Pirolo", $user_data->get_name_surname(), $html);
                $html = str_replace("piroloalessandro81@gmail.com", $user_data->get_email(), $html);
                $html = str_replace("xx mese xxxx", $user_data->get_birthdate(), $html);


                /**
                * replace per i personaggi
                */
                $_schede_personaggio = "";

                for($i = 0; $i < $num_pers; $i++)
                {
                    $_schede_personaggio .= "<li class=\"cardPersonaggio\">
                    <button name=\"Personaggio\" value=\"$i+1\">
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

                /**
                * replace per i report master
                */
                $_schede_report_master = "";

                for($i = 0; $i < $num_report_master; $i++)
                {
                    $_schede_report_master .= "<li class=\"cardReport\" class=\"cardReportMaster\">
                    <button name=\"ReportMaster\" value=\"$i+1\">
                        <div>
                        <div class=\"testoCardRep\">
                            <h4 class=\"textVariable\">" . $author_report_data[$i]->get_title() . "</h4>
                            <p>". $author_report_data[$i]->get_subtitle() ."</p>
                        </div>
                        </div>
                        <footer>
                            <p class=\"lableRepPublico\"><span xml:lang=\"en\">Report</span> publico</p>
                            <p class=\"lableRepPrivato\"><span xml:lang=\"en\">Report</span> condiviso a <span class=\"numCondivisioni\">4</span> giocatori</p>
                        </footer>
                    </button>
                    <div class=\"publicazione\">                     
                        <button name=\"PostRep\" value=\"$i+1\">Publica in \"Esplora\"</button>                      
                        <button name=\"RemoveRep\" value=\"$i+1\">Rimuovi da \"Esplora\"</button>
                    </div>
                </li>\n";
                }

                $html = str_replace("{report_author}", $_schede_report_master, $html);

                /**
                * replace per i report parteciapente
                */
                $_schede_report = "";

                for($i = 0; $i < $num_report; $i++)
                {
                    $_schede_report .= "<li class=\"cardReport\" class=\"cardReportPartecipante\">
                    <button name=\"ReportPartecip\" value=\"$i+1\">
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