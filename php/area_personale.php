<?php
require_once("GeneralPurpose.php");
require_once("banners.php");


if(isset($_SESSION["first_logged"])&&($_SESSION["first_logged"])){
    clearSession();
    $_SESSION["first_logged"]=true;
}else{
    clearSession();
}



if(!isset($_SESSION["login"]) || !$_SESSION["login"])
{
    header("Location: login.php");
    exit();
}
else if($_SESSION["login"])
{

    require_once("DBinterface.php");
    
    $db = new DBinterface();

    try {

        if(!isset($_SESSION["first_logged"]))
        {
            if(!$db->openConnection()){errorPage("EDB");exit();}
    
            $_SESSION["vai_avanti_master"] = false;
            $_SESSION["vai_avanti_rep"] = false;
            $_SESSION["vai_indietro_master"] = false;
            $_SESSION["vai_indietro_rep"] = false;
            $_SESSION["count_rep"] = 1;
            $_SESSION["count_master"] = 1;
            $_SESSION["first_logged"] = true;
            $_SESSION["character_data"] = $db->getCharactersByUser($_SESSION["username"]);
            $_SESSION["report_data"] = $db->getReportList($_SESSION["username"]);
            $_SESSION["author_report_data"] = $db->getReportAuthor($_SESSION["username"]);

            $_SESSION["num_pers"] = count($_SESSION["character_data"]);
            $_SESSION["num_report_master"]= count($_SESSION["author_report_data"]);
            $_SESSION["num_report"] = count($_SESSION["report_data"]);
            
            for($i = 0; $i < $_SESSION["num_report"]; $i++)
            {
                $_SESSION["array_num_part_rep"][$_SESSION["report_data"][$i]->get_id()] = count($db->getALLUsernamesForReport($_SESSION["report_data"][$i]->get_id()));
            }

            for($i = 0; $i < $_SESSION["num_report_master"]; $i++)
            {
                $_SESSION["array_num_part_rep_master"][$_SESSION["author_report_data"][$i]->get_id()] = count($db->getALLUsernamesForReport($_SESSION["author_report_data"][$i]->get_id()));
            }

            $db->closeConnection();

        }
        $numero_pag_report = ($_SESSION["num_report"]==0)? 0 : ((int)(($_SESSION["num_report"] -1) / 5) +1);
        $numero_pag_master = ($_SESSION["num_report_master"]==0)? 0 : ((int)(($_SESSION["num_report_master"] -1) / 5) +1);


        if(isset($_SESSION["vai_avanti_master"]) && $_SESSION["vai_avanti_master"])
        {
            $_SESSION["count_master"] == $numero_pag_master ? $_SESSION["count_master"] = $numero_pag_master : $_SESSION["count_master"]++;
            $_SESSION["vai_avanti_master"] = false;
        }

        if(isset($_SESSION["vai_avanti_rep"]) && $_SESSION["vai_avanti_rep"])
        {
            $_SESSION["count_rep"] == $numero_pag_report ? $_SESSION["count_rep"] = $numero_pag_report : $_SESSION["count_rep"]++;
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




        $html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "AreaPersonale.html");

        $footer = file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . "html" . DIRECTORY_SEPARATOR . "Footer_Template.html");
        $html = str_replace('<footerPH />', $footer, $html);


        if(!$html) 
        {
            header("Location: 404.php");
            exit();
        }
        else
        {
            if($_SESSION["img"] == "" || !file_exists($_SESSION["img"]))
            {
                $_SESSION["img"] = "../img/img_profilo_mancante.png";
            }

            $html = str_replace("../img/icone_razze/dragonide.png", $_SESSION["img"], $html);
            $html = str_replace("_user_", $_SESSION["username"], $html);
            $html = str_replace("_name_", $_SESSION["name_surname"], $html);
            $html = str_replace("_mail_", $_SESSION["email"], $html);
            $html = str_replace("_date_", $_SESSION["birthdate"], $html);



            $_schede_personaggio = "";
            
            if ( $_SESSION["num_pers"] > 0 ) {

                $_schede_personaggio = '<ul class="cards" id="Personaggi">';
                $contaPersonaggio=0;
                for($i = 0; $i < $_SESSION["num_pers"] ; $i++)               
                {
                    $urlImgRace="../img/icone_razze/";
                    switch($_SESSION["character_data"][$i]->get_race()){
                    case 'Umano': $urlImgRace.="umano.png\" alt='volto di una giovane donna di colore ornata di gioielli'";break;
                    case 'Elfo': $urlImgRace.="elfo.png\" alt='volto di elfo incappucciato con una faccia truce e scura'";break;
                    case 'Nano': $urlImgRace.="nano.png\" alt='volto di una nano fantasy con capelli rossi e con un espressione arrabbiata'";break;
                    case 'Halfling': $urlImgRace.="halfing.png\" alt='volto di una piccola donna che sta ridendo, ha un naso a punta e sopracciglia maliziose'";break;
                    case 'Gnome': $urlImgRace.="gnomo.png\" alt='volto di un piccolo essere dai lineamenti femminili orecchie a punta capelli lunghi al vento'";break;
                    case 'Tiefling': $urlImgRace.="tiefilng.png\" alt='volto di donna con pelle di colore rossastro capelli lunghi e orecchie a punta, in abito nobile'";break;
                    case 'Dragonide': $urlImgRace.="dragonide.png\" alt='essere dal volto simile a quello di un drago con squame rosse'";break;
                    case 'Mezzelfo': $urlImgRace.="mezzelfo.png\" alt='volto di un umano sereno con una corona in testa e orecchie a punta non accentuata'";break;
                    case 'Mezzorco': $urlImgRace.="mezzorco.png\" alt='volto di orchessa con capelli marroni, pelle violacea, piccole zanne alla bocca e orecchie a punta'";break;
                    }
                    $contaPersonaggio++;
                    $_schede_personaggio .= "<li id='personJSid". $contaPersonaggio ."' class=\"cardPersonaggio phpCard\"> 
                    <div>
                        <button name=\"Personaggio\" value=\"". $_SESSION["character_data"][$i]->get_id() . "\" class=\"buttonLink\"
                            aria-label='Vedi la scheda del personaggio: ".$_SESSION["character_data"][$i]->get_name()."'>VEDI<span class=\"hidden\"> la scheda del personaggio: ".$_SESSION["character_data"][$i]->get_name()."</span></button>
                    </div>
                    <div onclick=\"visualizzaPersonaggio(" . $_SESSION["character_data"][$i]->get_id() . ");\">
                        <img src=\"" . $urlImgRace . " />                 
                        <h4 class=\"textVariable\">" . $_SESSION["character_data"][$i]->get_name() . "</h4>
                        <dl>
                            <dt>Razza</dt>
                            <dd class=\"persRazza\">" . $_SESSION["character_data"][$i]->get_race() . "</dd>       
                            <dt>Classe</dt>
                            <dd class=\"persClasse\">" . $_SESSION["character_data"][$i]->get_class() . "</dd>
                            <dt class=\"allineamento\">Allineamento</dt>
                            <dd class=\"persAllineamento\">" . $_SESSION["character_data"][$i]->get_alignment() . "</dd>
                            
                        </dl>
                    </div>
                    </li>\n";
                }

                $_schede_personaggio .= '</ul>';
            }
            else {
                $_schede_personaggio .= "<p class='mancanoCards' >Qui verranno inserite le schede giocatore che realizzerai</p>";
            }

                $html = str_replace("<form_personaggi/>", $_schede_personaggio, $html);

                if ($_SESSION["num_pers"] == 0) {
                    $html = str_replace('<charForm />', '', $html );
                    $html = str_replace('</charForm />', '', $html );
                }
                else {
                    $html = str_replace('<charForm />', '<form action="../php/action_character.php" method="get">', $html );
                    $html = str_replace('</charForm />', '</form>', $html );
                }

                if($_SESSION["num_pers"] <= 4)
                {
                    $html = str_replace('<charFormEspandi />', 'class="hidden"', $html);
                    $_SESSION["espandiPers"] = true;
                }
                else {
                    $html = str_replace('<charFormEspandi />', '', $html);
                }

                if(isset($_SESSION["espandiPers"]) && $_SESSION["espandiPers"] == true)
                {
                    $html = str_replace("<ul class=\"cards\" id=\"Personaggi\">", "<ul class=\"expanded\" id=\"Personaggi\">", $html);
                    $html = str_replace(
                        '<button type="submit" id="espandiPers" name="espandi" value="Pers" title="Espandi il box e visualizza più personaggi">Vedi di Più</button>',
                        '<button type="submit" id="espandiPers" name="riduci" value="Pers" title="Riduci il box e visualizza meno personaggi">Vedi di Meno</button>',
                        $html);
                    unset($_SESSION["espandiPers"]);
                }

                if(isset($_SESSION["espandiPers"]) && $_SESSION["espandiPers"] == false)
                {
                    $html = str_replace("<ul class=\"expanded\" id=\"Personaggi\">", "<ul class=\"cards\" id=\"Personaggi\">", $html);
                    $html = str_replace(
                        '<button type="submit" id="espandiPers" name="riduci" value="Pers" title="Riduci il box e visualizza meno personaggi">Vedi di Meno</button>',
                        '<button type="submit" id="espandiPers" name="espandi" value="Pers" title="Espandi il box e visualizza più personaggi">Vedi di Più</button>',
                         $html);

                    unset($_SESSION["espandiPers"]);
                }


                $contaReport=0;

                $_schede_report_master = "";

                if($_SESSION["num_report_master"] > 0) {

                    $_schede_report_master = '<ul class="cards">';

                    for($i = ($_SESSION["count_master"]-1)*5 ; $i < $limit = ($_SESSION["num_report_master"] < $_SESSION["count_master"]*5 ? $_SESSION["num_report_master"] : 5*$_SESSION["count_master"]) ; $i++)
                    {
                        $contaReport++;
                        $_schede_report_master .= "<li class=\"cardReport cardReportMaster\">
                        <div id='reportJSid". $contaReport ."' class=\"phpCard\" onclick=\"visualizzaReportMaster(". $_SESSION["author_report_data"][$i]->get_id() . ");\">
                            <div class=\"testoCardRep\">
                                <div>
                                    <button name=\"ReportMaster\" value=\"". $_SESSION["author_report_data"][$i]->get_id() . "\" class=\"buttonLink\" 
                                    aria-label='Vedi il tuo Report intitolato: ".$_SESSION["author_report_data"][$i]->get_title()."'>VEDI<span class=\"hidden\"> il tuo report intitolato: ".$_SESSION["author_report_data"][$i]->get_title()."</span></button>
                                </div>
                                <h4 class=\"textVariable\">" . $_SESSION["author_report_data"][$i]->get_title() . "</h4>
                                <p>". $_SESSION["author_report_data"][$i]->get_subtitle() ."</p>
                            </div>
                            <footer>";
                            $_schede_report_master .= "<p class=\"lableRepPrivato\"><span xml:lang=\"en\" lang=\"en\">Report</span> condiviso a <span class=\"numCondivisioni\">" . $_SESSION["array_num_part_rep_master"][$_SESSION["author_report_data"][$i]->get_id()] . "</span> giocatori</p>";
                            $_schede_report_master .= "</footer>
                        </div>
                        <div class=\"publicazione\">";
                        if($_SESSION["author_report_data"][$i]->get_isExplorable() == 0)    
                        { 
                            $_schede_report_master .= "<button name=\"PostRep\" value=\"". $_SESSION["author_report_data"][$i]->get_id() . "\" aria-label=\"Pubblica in Esplora il Report intitolato: ". $_SESSION["author_report_data"][$i]->get_title() . "\">Pubblica in Esplora<span class=\"hidden\"> il Report intitolato: ". $_SESSION["author_report_data"][$i]->get_title() . "</span></button>";
                        }
                        else                
                        {                         
                            $_schede_report_master .="<button class=\"InEsplora\" name=\"RemoveRep\" value=\"". $_SESSION["author_report_data"][$i]->get_id() . "\" aria-label=\"Rimuovi da esplora il Report intitolato: " . $_SESSION["author_report_data"][$i]->get_title() . "\">Rimuovi da Esplora<span class=\"hidden\"> il Report intitolato: " . $_SESSION["author_report_data"][$i]->get_title() . "</span></button>";
                        }
                        
                        $_schede_report_master .= "</div>
                            </li>\n";
                    }

                    $_schede_report_master .= '</ul>';
                }
                else{
                    $_schede_report_master .= "<p class='mancanoCards' >Qui verranno inseriti i report di sessione che realizzerai</p>";
                }

                if($_SESSION["count_master"] == 1)
                {
                    $html = str_replace('<li class="inputMove"><button type="submit" id="masterPrecedente" class="precedente" name="espandi" value="masterPrecedente" aria-label="Passa alla pagina precedente dei tuoi Report">Precedente</button></li>', '', $html);
                }

                if($_SESSION["count_master"] == $numero_pag_master)
                {
                    $html = str_replace('<li class="inputMove"><button type="submit" id="masterSuccessivo" class="successivo" name="espandi" value="masterSuccessivo" aria-label="Passa alla pagina successiva dei tuoi Report">Successiva</button></li> ', '', $html);
                }


                if ( $_SESSION["num_report_master"] == 0) {
                    $html = str_replace('<masterForm />', '', $html );
                    $html = str_replace('</masterForm />', '', $html );
                }
                else {
                    $html = str_replace('<masterForm />', '<form action="../php/action_report.php" method="get">', $html );
                    $html = str_replace('</masterForm />', '</form>', $html );
                }

                if($numero_pag_master <= 1)
                {
                    $html = str_replace('<masterFormEspandi />', 'class="hidden"', $html);
                }
                else {
                    $html = str_replace('<masterFormEspandi />', '', $html);
                }

                $html = str_replace("<report_author/>", $_schede_report_master, $html);
                $html = str_replace("<numero_attuale_master/>", $_SESSION["count_master"], $html);
                $html = str_replace("<numero_di_master/>", $numero_pag_master, $html);


                $_schede_report = "";

                if($_SESSION["num_report"] > 0) {

                    $_schede_report = '<ul class="cards">';

                    for($i = ($_SESSION["count_rep"]-1)*5 ; $i < $limit = ($_SESSION["num_report"] < $_SESSION["count_rep"]*5 ? $_SESSION["num_report"] : 5*$_SESSION["count_rep"]); $i++)
                    {
                        $contaReport++;
                        $_schede_report .= "<li class=\"cardReport cardReportPartecipante\">
                        <div id='reportJSid". $contaReport ."' class=\"phpCard\" onclick=\"visualizzaReportPartecip(". $_SESSION["report_data"][$i]->get_id() . ");\">
                            <div class=\"testoCardRep\">
                                <div>
                                    <button name=\"ReportPartecip\" value=\"". $_SESSION["report_data"][$i]->get_id() . "\" class=\"buttonLink\" 
                                    aria-label='Vedi il Report intitolato: ".$_SESSION["report_data"][$i]->get_title()."'>VEDI<span class=\"hidden\"> il Report intitolato: ".$_SESSION["report_data"][$i]->get_title()."</span></button>
                                </div>
                                <h4 class=\"textVariable\">". $_SESSION["report_data"][$i]->get_title() ."</h4>
                                <p> ". $_SESSION["report_data"][$i]->get_subtitle() . "</p>
                            </div>
                            <div class=\"badgeUtente\">
                                <h5>Autore</h5>
                                <img src=\"" . $_SESSION["report_data"][$i]->get_author_img() ."\" alt=\"\" /> 
                                <p class=\"textVariable\">" . $_SESSION["report_data"][$i]->get_author() . "</p>
                            </div>
                        <footer>";
                        $_schede_report .= "<p class=\"lableRepPrivato\"><span xml:lang=\"en\" lang=\"en\">Report</span> condiviso a <span class=\"numCondivisioni\">" . $_SESSION["array_num_part_rep"][$_SESSION["report_data"][$i]->get_id()] . "</span> giocatori</p>";
                        $_schede_report .= "</footer>
                        </div>
                    </li>\n";
                    }

                    $_schede_report .= '</ul>';
                }
                else {
                    $_schede_report .= "<p class='mancanoCards' >Non appena verrai citato come giocatore in qualche report di sessione vedrai apparire qui quei report</p>";
                }

                if($_SESSION["count_rep"] == 1)
                {
                    $html = str_replace('<li class="inputMove"><button type="submit" id="partecPrecedente" class="precedente" name="espandi" value="partecPrecedente" aria-label="Passa alla pagina precedente dei Report condivisi con te">Precedente</button></li>', '', $html);
                }

                if($_SESSION["count_rep"] == $numero_pag_report)
                {      
                    $html = str_replace('<li class="inputMove"><button type="submit" id="partecSuccessivo" class="successivo" name="espandi" value="partecSuccessivo" aria-label="Passa alla pagina successiva dei Report condivisi con te">Successiva</button></li>', '', $html);
                }
                
                if ( $_SESSION["num_report"] == 0) {
                    $html = str_replace('<partForm />', '', $html );
                    $html = str_replace('</partForm />', '', $html );
                }
                else {
                    $html = str_replace('<partForm />', '<form action="../php/action_report.php" method="get">', $html );
                    $html = str_replace('</partForm />', '</form>', $html );
                }
                if($numero_pag_report <= 1)
                {
                    $html = str_replace('<partFormEspandi />', 'class="hidden"', $html);
                }
                else {
                    $html = str_replace('<partFormEspandi />', '', $html);
                }

                $html = str_replace("<report/>", $_schede_report, $html);
                $html = str_replace("<numero_attuale_report/>", $_SESSION["count_rep"], $html);
                $html = str_replace("<numero_di_report/>", $numero_pag_report, $html);


                $html = addPossibleBanner($html, "area_personale.php");

                if((isset($_GET["Hamburger"])) && ($_GET["Hamburger"]=="yes")){
                    $html = str_replace("class=\"hideForHamburger\" ", " ", $html);
                    $html = str_replace("{RedirectHamburger}", "../php/area_personale.php?Hamburger=no", $html);
                }else{
                    $html = str_replace("{RedirectHamburger}", "../php/area_personale.php?Hamburger=yes", $html);
                }


                echo $html;

            }

        } catch(Exception $e) {
            if(isset($db)&&($db)){
            $db->closeConnection();
            }
            errorPage("EDB");
            exit();
        }
    }


?>
