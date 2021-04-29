<?php 
require_once("includes/config.php"); 
require_once("includes/classes/User.php"); 
require_once("includes/classes/ButtonMaker.php"); 
require_once("includes/classes/NavigationMenuProvider.php"); 


$loggedInUsername = User::isLoggedIn() ? $_SESSION["loggedIn"] : "";
$loggedInUser = new User($con, $loggedInUsername);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ITube</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="assets/js/commonActions.js"></script>
    <script src="assets/js/userActions.js"></script>
</head>
<body>
    <div id="pageContainer">

        <div id="mastHeadContainer">
            <button class="navShowHide">
                <img src="assets/images/icons/menu.png" alt="">
            </button>

            <a class="logoContainer" href="index.php">
                <img src="assets/images/icons/logo.png" title="MinPlay" alt="Site logo">
            </a>

            <div class="searchBarContainer">
                <form action="search.php" method="GET">
                    <input type="text" class="searchBar" name="term" placeholder="Search...">
                    <button class="searchButton" style="padding-bottom: 6px;">
                        <img src="assets/images/icons/search.png" title="seach" alt="search">
                    </button>
                </form>
            </div>

            <div class="userInfo">
                <a href="upload.php">
                    <img class="upload" src="assets/images/icons/upload.png" alt="">
                </a>
                <?php 
                    echo ButtonMaker::createUserProfileNavButton($con, $loggedInUser->getUsername());
                ?>
            </div>

        </div>
        <div id="sideNavContainer" style="display: none;">
            <?php 
            $navigationProvider = new NavigationMenuProvider($con, $loggedInUser);
            echo $navigationProvider->create();
            ?>
        </div>
        <div id="mainSectionContainer">
            <div id="mainContentContainer">
                