<?php
require_once("DBinterface.php");
require_once("GeneralPurpose.php");
require_once("comments.php");
require_once("banners.php");

// SESSION['RepCommentPOST'] == POST

echo var_dump($_SESSION['RepCommentPOST']);
exit();
if ( !isset($_SESSION['RepCommentPOST']) ) {
    errorPage("NO RepCommnetPOST");
}

$commentText = clean_input($_SESSION['RepCommentPOST']['contenutoCommento']);
$reportID = $_SESSION['RepCommentPOST']['ReportID'];
unset($_SESSION['RepCommentPOST']);

// Controllare se user non e' ne' autore ne taggato, e report non isExplorable ...

if ( !(trim($commentText) == '') ) {

    $comment = new Comments($commentText, $_SESSION['username'], $reportID);

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

header("Location: ReportPage.php?ReportID=". $reportID."#anchorComment");
exit();


?>