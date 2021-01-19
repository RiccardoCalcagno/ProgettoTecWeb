<?php
    require_once("report_data.php");
    require_once("character.php");
    require_once("DBinterface.php");

    function clean_input($var) {   
        $var = htmlentities($var);

        $var = trim($var);
     
        return $var;
    }

    function saveStaged(){
        $presenti=0;
        $db = new DBinterface();
        $openConnection = $db->openConnection();
        if ($openConnection == false) {
            return false;
        }else{
            if((isset($_SESSION['stagedPersonaggi'])) &&(!empty($_SESSION['stagedPersonaggi']))){
                foreach ($_SESSION['stagedPersonaggi'] as &$personaggio){
                    $personaggio->set_author($_SESSION['username']);
                    $result = $db->addCharacter($personaggio);  
                    if(!$result){
                        $personaggio->set_name("Errore di Salvataggio");
                        return -1;
                    }else{
                        $presenti=1;
                    }
                } 
            }
            if((isset($_SESSION['stagedReports'])) &&(!empty($_SESSION['stagedReports']))){
                foreach ($_SESSION['stagedReports'] as &$report){
                    $report->set_author($_SESSION['username']);
                    $result = $db->addReport($report);
                    if(!$result){
                        $report->set_title("Errore di Salvataggio");
                        return -1;
                    }else{
                        $presenti=1;
                    }
                } 
            }
            $db->closeConnection();
        }
        return $presenti;
    }

    function setup($html) { // Setup generico per tutte le pagine
        
        if( session_status() == PHP_SESSION_NONE ) {
            session_start();
        }else if ( !isset($_SESSION['errorMessage']) ) {
            $_SESSION['errorMessage'] = 'Errore: Nessun Errore :)';
        }

        if(isset($_SESSION["login"])&&($_SESSION["login"])) {
            $html = str_replace('<input id="Accesso" type="submit" name="accesso" value="Accedi" aria-label="Accedi alla tua area personale">', '<input id="Accesso" name="accesso" type="submit" value="Esci" aria-label="Disconnettiti dalla area personale e torna in Home">', $html);
            $html = str_replace('<input id="Iscrizione" type="submit" name="accesso" value="Iscrizione" aria-label="Iscriviti">', '<input id="Iscrizione" name="accesso" type="submit" value="Area Personale" aria-label="Vai alla tua area personale">', $html);
        }
        unset($_SESSION['id_report_modifica']);
        unset($_SESSION["count_esplora"]);

        $footer = '';
        if (strpos($html, '<footerPH />')) {

            $footer = file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . "html" . DIRECTORY_SEPARATOR . "Footer_Template.html"); 
        }
        $html = str_replace('<footerPH />', $footer, $html);

        return $html;
    }
    
    function clearSession() {   // Clear di variabili session utili solo a specifiche pagine
            //unset OK anche su null
        if( session_status() == PHP_SESSION_NONE ) {
            session_start();
        }else if ( !isset($_SESSION['errorMessage']) ) {
            $_SESSION['errorMessage'] = 'Errore: Nessun Errore :)';
        }
        
        unset($_SESSION['id_report_modifica']);
        unset($_SESSION["listaGiocatori"]);
        unset($_SESSION["first_logged"]);
        unset($_SESSION["character_id"]);
        unset($_SESSION['modificaChar']);
        unset($_SESSION["count_esplora"]);
    }

    function setup_clear($html) { // Setup generico e clearSession
        
        clearSession();
        return setup($html);
    }

    function errorPage($errorMessage) {
        if( session_status() == PHP_SESSION_NONE ) {
            session_start();
        }
        $_SESSION['errorMessage'] = $errorMessage;
        header("Location: Errore.php");
        exit();
    }

    function redirect_GET($path, $get) {   // Dato il path, esegue il redirect come se action fosse su quella pagina

        $path .= '?';

        foreach($get as $key => $value) {
            $path .= $key . '=' . $value . '&';
        }
        $path = rtrim($path, '&');

        header("Location: $path");
        exit();
    }

    function validate_img($img, $path)
    {
//echo " entrato in  validateImg";
        $uploadOk = true;
        /*if($img["size"] > 1000000) 
        {
            $uploadOk = false;
        }*/
          
        $imageFileType = strtolower( pathinfo( $path,PATHINFO_EXTENSION));
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) 
        {
            $uploadOk = false;
        }

//    echo "controllo esistenza file";
        return $uploadOk;
    }

    function check_file_name($img, $name)
    {
    $i = 1;
    while(file_exists($img))
    {
        $name_arr = explode('.', $name);
        $name_arr[0] .= $i;
        $name = implode('.', $name_arr);
        $img = ".." . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "immagini_profilo" . DIRECTORY_SEPARATOR . $name;
        $i++; 
    }
    return $img;

    }

?>
