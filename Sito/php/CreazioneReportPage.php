<?php
    //require
    require_once("DBinterface.php");
    require_once("report_data.php");
    require_once("GeneralPurpose.php");
    require_once("banners.php");

    // use DB\DBinterface; //SERVE A QUALCOSA?

    $html = file_get_contents("../otherHTMLs/creazioneReport.html");

    if(isset($_SESSION["username"]))
    {
        str_replace("<input id=\"Accesso\" type=\"submit\" value=\"Accedi\">", "<input id=\"Accesso\" type=\"submit\" value=\"Esci\">", $html);
        str_replace("<input id=\"Iscrizione\" type=\"submit\" value=\"Iscrizione\">", "<input id=\"Iscrizione\" type=\"submit\" value=\"Area Personale\">", $html);
    }



    if(isset($_POST['submit'])){
        //controllo se esiste già il report, se si completo la form con i dati già esistenti
        $titolo = $_POST['titolo'];
        $sottotitolo = $_POST['sottotitolo'];
        $contenuto = $_POST['contenuto'];
        $condividi = $_POST['condividi'];

        //controlli
        if(strlen($titolo) != 0 && strlen($sottotitolo) != 0 && strlen($contenuto) != 0) {

            //creo l'oggetto report  AUTOR PUO ESSERE NULL (È CORRETTO, serve anche ai salvataggi pendenti)
            $rep = new ReportData(getLatestRep()+1, $title, $subtitle, $content, $_SESSION['username'], $condividi);
            
            if(isset($_SESSION['username'])) {

                $dbInterface = new DBinterface();
                $connection = $dbInterface->openConnection();

                if($connection){
                    //aggiungo il report nel database
                    $insertionResult = $dbInterface->addReport($rep);

                    if ($insertionResult){

                        $_SESSION['banners']= "creazione_documento_confermata";

                        //azzero la form
                        $titolo = '';
                        $sottotitolo = '';
                        $contenuto = '';
                        $condividi = '';
                    }
                    else{
                        //messaggi di errore lato server, non errori di compilazione
                        $message = 'Errore lato Server, Riprovare.';
                    }
                }
            }else{
                array_push($_SESSION['stagedReports'], $rep);
                $_SESSION['banners']= "salvataggio_pendente";
            }
        }
        //altrimenti ci sono stati errori di inserimento
        else{
            $message = 'errori:';
            if (strlen($title) == 0) {
                $message.='titolo troppo corto';
            }
            if (strlen($subtitle) == 0) {
                $message .='sottotitolo troppo corto';
            }
            if (strlen($descrizione) == 0) {
                $message.='contenuto troppo corto';
            }
        }
        
    }

    //il contenuto delle textarea viene settato qui
    $html = str_replace('<valueTitle/>',$title,$html);
    $html = str_replace('<valueSubtitle/>',$subtitle,$html);
    $html = str_replace('<valueContent/>',$content,$html);
    
    /*
    QUESTO E DA METTERE ALLA FINE DI OGNI PHP PER OGNI PAGINA HTML
    */
    $html = addPossibleBanner($html);

    echo $html;

?>