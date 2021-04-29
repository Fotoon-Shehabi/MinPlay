<?php
include_once("includes/header.php");
require_once("includes/classes/ProfileGenerator.php");

if(isset($_GET["username"])) {
    $profileUsername = $_GET["username"];
}
else {
    echo "Channel not found.";
    exit();
}
$profileGenerator = new ProfileGenerator($con, $loggedInUser, $profileUsername);
echo $profileGenerator->create();
?>

<?php
include_once("includes/footer.php");
?>