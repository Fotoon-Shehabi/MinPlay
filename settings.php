<?php
require_once("includes/header.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/SettingsForm.php");

if(!User::isLoggedIn()) {
    require_once("includes/footer.php");
    echo "<script>notSignedIn();</script>";
    exit();
}
$detailsMessage = "";
$passwordMessage= "";
$pictureMessage = "";
$formProvider = new SettingsForm();
if(isset($_POST["saveDetailsButton"])) {
    $account = new Account($con);
    $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
    $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
    $email = FormSanitizer::sanitizeFormString($_POST["email"]);
    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
    
    if($account->updateDetails($firstName, $lastName, $email, $loggedInUser->getUsername(), $password)) {
        $detailsMessage .= "<div class='alert alert-success'>
                                <strong>Details updated successfully!</strong>
                            </div>";
    }
    else {
        $errorMessage = $account->getFirstError();

        if($errorMessage=="") $errorMessage = "Something went wrong!";
        $detailsMessage .= "<div class='alert alert-danger'>
                                <strong>Failed!</strong>
                                <p>$errorMessage</p>
                            </div>";
    }
}
if(isset($_POST["savePasswordButton"])) {
    $account = new Account($con);
    $oldPassword = FormSanitizer::sanitizeFormPassword($_POST["oldPassword"]);
    $newPassword = FormSanitizer::sanitizeFormPassword($_POST["newPassword"]);
    $newPasswordC = FormSanitizer::sanitizeFormPassword($_POST["newPasswordc"]);
    
    if($account->updatePassword($oldPassword, $newPassword, $newPasswordC, $loggedInUser->getUsername())) {
        $passwordMessage .= "<div class='alert alert-success'>
                                <strong>Password updated successfully!</strong>
                            </div>";
    }
    else {
        $errorMessage = $account->getFirstError();

        if($errorMessage=="") $errorMessage = "Something went wrong!";
        $passwordMessage .= "<div class='alert alert-danger'>
                                <strong>Failed!</strong>
                                <p>$errorMessage</p>
                            </div>";
    }
}
if(isset($_POST["savePictureButton"])) {
    $account = new Account($con);
    $newPicture = $_FILES["profilePic"];
    if($account->updatePicture($newPicture, $loggedInUser->getUsername())) {
        $pictureMessage .= "<div class='alert alert-success'>
                                <strong>Picture updated successfully!</strong>
                            </div>";
    }
    else {
        $errorMessage = $account->getFirstError();

        if($errorMessage=="") $errorMessage = "Something went wrong!";
        $passwordMessage .= "<div class='alert alert-danger'>
                                <strong>Failed!</strong>
                                <p>$errorMessage</p>
                            </div>";
    }
}
if(isset($_POST["deletePictureButton"])) {
    $account = new Account($con);
    if($account->deleteOldPicture($loggedInUser->getUsername())) {
        $pictureMessage .= "<div class='alert alert-success'>
                                <strong>Picture deleted successfully!</strong>
                            </div>";
    }
    else {
        $errorMessage = $account->getFirstError();

        if($errorMessage=="") $errorMessage = "Something went wrong!";
        $passwordMessage .= "<div class='alert alert-danger'>
                                <strong>Failed!</strong>
                                <p>$errorMessage</p>
                            </div>";
    }
}
?>

<div class='settingsContainer column'>
    <div class='formSection'>
        <div class='message'>
            <?php echo $detailsMessage?>
        </div>
        <?php echo $formProvider->createUserDetailsForm(
            isset($_POST["firstName"]) ? $_POST["firstName"] : $loggedInUser->getFirstName(),
            isset($_POST["lastName"]) ? $_POST["lastName"] : $loggedInUser->getLastName(),
            isset($_POST["email"]) ? $_POST["email"] : $loggedInUser->getEmail(),
        );?>
    </div>
    <div class='formSection'>
        <div class='message'>
            <?php echo $passwordMessage?>
        </div>
        <?php echo $formProvider->createPasswordsForm();?>
    </div>
    <div class='formSection'>
        <div class='message'>
            <?php echo $pictureMessage?>
        </div>
        <?php echo $formProvider->createProfilePicForm();?>
    </div>
</div>
