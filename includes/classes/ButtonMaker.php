<?php

class ButtonMaker {

    public static $signInFunction = "notSignedIn()";

    public static function createUserLink($link) {
        return User::isLoggedIn() ? $link : ButtonMaker::$signInFunction;
    }
    public static function createUserProfileButton($con, $username) {
        if(!User::isLoggedIn()) {
            return "<a href=''>
                        <img src='assets/images/profilePictures/default.png' class='profilePic'>
                    </a>";
        }
        $user = new User($con, $username);
        $picData = $user->getPicture();
        $profilePic = base64_encode($picData);
        $link = "profile.php?username=$username";

        return "<a href='$link'>
                    <img src='data:image/*;base64,$profilePic' class='profilePic'>
                </a>";
    }

    public static function createButton($text, $imgSrc, $action, $class) {
        $image = ($imgSrc == null||$imgSrc=="") ? "" : "<img src='$imgSrc'>";

        $action = ButtonMaker::createUserLink($action);

        return "<button class='$class' onclick='$action'>
            $image
            <span class='text'>$text</span>
        </button>";
    }

    public static function createHyperlinkButton($text, $imgSrc, $href, $class) {
        $image = ($imgSrc == null||$imgSrc=="") ? "" : "<img src='$imgSrc'>";

        return "
        <a href='$href'>
            <button class=$class>
                $image
                <span class='text'>$text</span>
            </button>
        </a>";
    }
    
    public static function createEditButton($videoId) {
        $href = "editVideo.php?videoId=$videoId";
        $button = ButtonMaker::createHyperlinkButton("EDIT VIDEO", null, $href, "editButton");
        return "<div class='editButtonContainer'>
                    $button
                </div>";
    }

    public static function createSubscribeButton($con, $userTo, $loggedInUser) {
        
        $usernameTo = $userTo->getUsername();
        $loggedInUsername = $loggedInUser->getUsername();
        if(USER::isLoggedIn()) {
            $isSubscribedTo = $loggedInUser->isSubscribedTo($usernameTo);
        }
        else {
            $isSubscribedTo = false;
        }
        $buttonText = $isSubscribedTo ? "SUBSCRIBED" : "SUBSCRIBE";
        $buttonText .= " " . $userTo->getSubscriberCount();

        $buttonClass = $isSubscribedTo ? "unsubscribe button" : "subscribe button";
        $action = "subscribe(\"$usernameTo\", \"$loggedInUsername\", this)";

        $button = ButtonMaker::createButton($buttonText, null, $action, $buttonClass);
        return "<div class='subscribeButtonContainer'>
                    $button
                </div>";
    }
    public static function createUserProfileNavButton($con, $username) {
        if(User::isLoggedIn()) {
            return ButtonMaker::createUserProfileButton($con, $username);
        }
        else {
            return "<a href='signIn.php'>
                        <span class='signInLink'>SIGN IN</span>
                    </a>";
        }
    }
}
?>