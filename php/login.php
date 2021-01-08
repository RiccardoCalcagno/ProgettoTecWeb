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
        $username = $_POST["username"];
        str_replace("<p id=\"loginError\" class=\"hidden\">","<p id=\"loginError\">", $html);
        session_destroy();
    }

    $html = str_replace("value=\"<username>\"", "value=\"" . $username . "\"", $html);

    echo $html;
?>