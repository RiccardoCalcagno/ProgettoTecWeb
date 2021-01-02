<?php
    require_once("DBinterface.php");
    require_once("character.php");
    require_once("GeneralPurpose.php");
    require_once("banners.php");
    use DB\DBinterface;
    
    // do you even need to check before ?
    session_start();

    $html = file_get_contents('..'.DIRECTORY_SEPARATOR.'otherHTMLs'.DIRECTORY_SEPARATOR.'character creation.html'); //forse togliere spazio nel nome

    $messaggioForm = "";
    $name = ""; $race = ""; $class = ""; $background = ""; $alignment = ""; $traits = ""; $ideals = ""; $bonds = ""; $flaws = "";

    function checkText($text) {
        return preg_match("/^.{10,}$/", $text); // clean_input dopo
    }

    if (isset($_POST['salvaPers'])) {

        $name = $_POST['cname'];    //estraggo dal post della form le informazioni contenute
        $race = $_POST['crace'];
        $class = $_POST['cclass'];
        $background = $_POST['cbackground'];
        $alignment = $_POST['calignment'];
        $traits = $_POST['ctraits'];
        $ideals = $_POST['cideals'];
        $bonds = $_POST['cbonds'];
        $flaws = $_POST['cflaws'];

        //Fare i controlli sugli input
        //Uso variabili booleane, true se la variabile che controlla passa il check, false altrimenti
        $check_name = preg_match("/^[a-z][a-z ,.'-]{2,20}$/i", $name);// trim dopo, accetta sequenze strane ,,,,---...  //preg_match("/\\S+/",$name);
        //$check_race = ;            //provengono da select, non possono essere sbagliati, no?
        //$check_class = ;
        //$check_background = ;
        //$check_alignment = ;
        $check_traits = checkText($traits);
        $check_ideals = checkText($ideals);
        $check_bonds = checkText($bonds);
        $check_flaws = checkText($flaws);

        if($check_name && $check_traits && $check_ideals && $check_bonds && $check_flaws){
            //se passo i controlli allora passo gli input alla costruzione di dati per il DB.
            $character = new Character (
                0,    // ID qui inutile, non viene considerato per inserimento DB (e poi oggetto character viene distrutto) (aggiungere valore di default?)
                $name,
                $race, $class, $background, $alignment,    // Ok, select
                clean_input($traits), 
                clean_input($ideals), 
                clean_input($bonds), 
                clean_input($flaws),
                //date da DB ?
            );

            if(isset($_SESSION[''])) {    // Inserisci

                $db = new DBinterface();
                $openConnection = $db->openConnection();

                if ($openConnection == true) {
                    $result = $db->addCharacter($character);    // TO FIX ADD Creator of character ?? 

                    if($result == true) {    // conferma ed errori con str_replace o banner_salvataggio.html ?
                        $_SESSION['banners']= "creazione_documento_confermata";
                        $name = ""; $race = ""; $class = ""; $background = ""; $alignment = ""; $traits = ""; $ideals = ""; $bonds = ""; $flaws = "";
                    }
                    else {
                        // Can't insert in DB
                        $messaggioForm = '<div id="errori"><p>Errore nella creazione del personaggio. Riprovare.</p></div>'; // (ERRORE LATO DB)
                    }
                }
                else {
                    // Can't connect to DB
                    $messaggioForm = '<div id="errori"><p>Errore nella creazione del personaggio. Riprovare</p></div>'; // (ERRORE LATO Server)
                }

                $db->closeConnection();
            }
            else {
                array_push($_SESSION['stagedPersonaggi'], $character);
                $_SESSION['banners']= "salvataggio_pendente";
            }
        }
        else{
            //se non passo i controlli allora restituisco messaggi adeguati per informare l'utente degli errori di input.
            $messaggioForm = '<div id="errori" style="text-align: center; color: red; background-color: yellow; padding: 1em; border: 3px solid black;"><ul>'; // TO FIX

            if(!$check_name) {
                $namelen = strlen($name);
                if ($namelen < 3) { 
                    $messaggioForm .= '<li>Nome personaggio deve avere almeno 3 caratteri</li>';
                }
                else if ($namelen > 20) {
                    $messaggioForm .= '<li>Nome personaggio deve avere al massimo 20 caratteri</li>';
                }
                else {
                    $messaggioForm .= '<li>Formato Nome non valido: utilizzare solo lettere, spazi, virgole, punti e hypen</li>';
                }
            }

            $charTraits = array("Carattere", "Ideali", "Legami", "Difetti");
            $checkCharTraits = array($check_traits, $check_ideals, $check_bonds, $check_flaws);
            for ($i = 0; $i < 4; $i++) {
                if(!$checkCharTraits[$i]) {
                    $messaggioForm .= '<li>Descrizione per "' . $charTraits[$i]. '" deve essere almeno 10 caratteri </li>';
                }
            }
            $messaggioForm .= '</ul></div>';
        }
    }

$html = str_replace("<messaggioForm />", $messaggioForm, $html);

$html = str_replace('<valoreNome />', $name, $html);
// restore SElECTs :)))))))))))))
// Possibile soluzione: str_replace su <selectValues /> con un ul con le varie values, con javascript leggo i valori e cambio le select, 
//        infine rimuovo la ul; :o)
// Altrimenti modificare direttamente $html aggiungendo attributo selected="selected" alla option giusta (ma molto piu' complicato e invasivo)
// :))))
$html = str_replace('<valoreNome />', $name, $html);
$html = str_replace('<valoreTraits />', $traits, $html);
$html = str_replace('<valoreIdeals />', $ideals, $html);
$html = str_replace('<valoreBonds />', $bonds, $html);
$html = str_replace('<valoreFlaws />', $flaws, $html);





/*
    QUESTO E DA METTERE ALLA FINE DI OGNI PHP PER OGNI PAGINA HTML
*/
$html = addPossibleBanner($html);


echo $html;
?>