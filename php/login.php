<?php
    require_once("GeneralPurpose.php");

    if(isset($_SESSION['login']) && $_SESSION['login'])
    {
        header("Location: area_personale.php");
        exit();
    }

    $username = ""; 

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "login.html");
    $html = setup_clear($html);   //setup() ?

    if(isset($_SESSION['login']) && !$_SESSION['login'])
    {
    //echo "errore trovato";
        $username = $_SESSION["tmp"];
        $html = str_replace("<p id=\"loginError\" class=\"hidden\">","<p id=\"loginError\" class=\"text-errore\">", $html);
	$html = str_replace("name=\"username\"", "name=\"username\" class=\"input-errore\"", $html);
	$html = str_replace("name=\"password\"", "name=\"password\" class=\"input-errore\"", $html);
        session_destroy();
    }

    $html = str_replace("value=\"\" <!--username-->", "value=\"" . $username . "\"", $html);

    echo $html;
?>
