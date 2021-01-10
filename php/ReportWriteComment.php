<?php
require_once("DBinterface.php");
require_once("GeneralPurpose.php");
require_once("comments.php");
require_once("banners.php");

// SESSION['RepCommentPOST'] == POST

if ( !isset($_SESSION['RepCommentPOST']) ) {
    errorPage("NO RepCommnetPOST");
}
else {

    $commentText = clean_input($_SESSION['RepCommentPOST']['contenutoCommento']);
    $reportID = $_SESSION['RepCommentPOST']['ReportID'];

    $comment = new Comments($commentText, $_SESSION['username'], $reportID);

//Controlli se puo' commentare, ma in teoria se non potesse non potrebbe neanche vedrlo .......

    $db = new DBinterface();
    $conn = $db->openConnection();
    
    if ($conn) { 
        if( !$db->addComments($comment)) {

            errorPage("NO COMM");
        }

        $db->closeConnection();
    }
    else {
        errorPage("Can't connect to DB.");
    }

    
}

unset($_SESSION['RepCommentPOST']);

header("Location: ReportPage.php?ReportID=". $reportID);
exit();


?>