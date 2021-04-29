<?php 
require_once("includes/header.php");
require_once("includes/classes/FileProcessor.php");


if(isset($_POST["uploadBookButton"])) {
    $FileProcessor = new FileProcessor($con);
    $details = [
        'usage' => 'book',
        'title' => $_POST['titleInput'],
        'author' => $_POST['authorInput'],
        'description' => $_POST['descriptionInput'],
        'username' => $loggedInUser->getUsername()
    ];
    $success = $FileProcessor->upload($_FILES['fileInput'], $details);
    if($success) {
        echo "Upload success!";
    }
    else { 
        echo "Failed somewhere.";
        echo (string) $success;
    }
}
else {
    echo "No file sent to page.";
}
exit();

?>

<?php require_once("includes/footer.php"); ?>