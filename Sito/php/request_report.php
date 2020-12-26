<?php

    if(!isset($_SESSION))
        session_start();

    $_SESSION["report"] = $_POST["id"];

    header("Location : report.php");

?>