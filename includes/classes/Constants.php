<?php

class Constants {
    public static $nameLength = "Your first/last name must be 2-25 characters!";
    public static $usernameLength = "Your username name must be 2-50 characters!";
    public static $emailLength = "Your email name must be 8-255 characters!";
    public static $usernameTaken = "This username already exists!";
    public static $emailsMismatch = "The confirm email doesn't match!";
    public static $passwordsMismatch = "The confirm password doesn't match!";
    public static $emailInvalid = "Email address is invalid!";
    public static $emailTaken = "This email already exists!";
    public static $passwordCharacters = "Password must be 8-25 characters including\n at least one letter, one number, and one special character from [?!@#$%^&*()]";
    public static $loginFailed = "Username or password is incorrect.";
    public static $passwordInvalid = "The password is not correct!";
    public static $imageTooBig = "The image file cannot be larger than 10 mb!";
    public static $uploadError = "Failed to upload your file to our mongoDB";
}

?>