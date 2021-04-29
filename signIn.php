<?php 
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php"); 
require_once("includes/classes/Constants.php"); 
require_once("includes/classes/Account.php"); 
$account = new Account($con);
if(isset($_POST["submitButton"])) {
    $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
    $success = $account->login($username,$password);
    if($success) {
        $_SESSION["loggedIn"] = $username;
        header("Location: index.php");
    }
}

function getInputValue($name) {
    if(isset($_POST[$name])) {
        echo $_POST[$name];
    }
}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Minplay login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="assets/js/commonActions.js"></script>
</head>
<body>
    <div class="signInContainer">
        <div class="column">
            <div class="header">
                <a class="logoContainer" href="index.php">
                    <img src="assets/images/icons/ITubeLogo.png" title="Home" alt="Site logo">
                </a>
                <hr>
                <h3>Sign In</h3>
                <span>to continue to Minplay</span>
            </div>

            <div class="loginForm">
                <form action="signIn.php" method="POST">
                    <?php echo $account->getError(Constants::$loginFailed); ?>
                    <input type="text" name="username" value="<?php getInputValue('username'); ?>" placeholder="Username" required autocomplete="off">
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="submit" name="submitButton" value="SUBMIT">
                </form>
            </div>

            <a class="signInMessage" href="signUp.php">Need an account? Sign up here!</a>
        </div>
    </div>
</body>
</html>