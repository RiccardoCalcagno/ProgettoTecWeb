<?php
    require_once("GeneralPurpose.php");

    if( session_status() == PHP_SESSION_NONE ) {
        session_start();
    }

if(isset($_GET["Hamburger"])){
    clearSession();
    $html = file_get_contents("..". DIRECTORY_SEPARATOR ."html". DIRECTORY_SEPARATOR . "Home.html");
    if($_GET["Hamburger"]=="yes"){
        $html = str_replace("<ul id=\"menu\"", "<ul id=\"menu\" style=\"display:block;\"", $html);
        $html = str_replace("../php/action.php?Hamburger=yes", "../php/action.php?Hamburger=no", $html);
    }
    echo $html;
    exit();
}

if(isset($_GET["accesso"]))
{

    if($_GET["accesso"] == "Accedi")
    {
        header("Location: login.php");
        exit();
    }

    if($_GET["accesso"] == "Esci")
    {
        session_start();
        
        session_destroy();
        
        header('Location: ../index.php');
        
        die();
    }

    if($_GET["accesso"] == "Iscrizione")
    {
        
        header("Location: register.php");
        exit();
    }

    if($_GET["accesso"] == "Area Personale")
    {
        header("Location: area_personale.php");
        exit();
	
    }
}

header("Location: ../index.php");


?>
