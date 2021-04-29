<?php
class ProfileData {
    private $con, $profileUser;
    public function __construct($con, $profileUsername) {
        $this->con = $con;
        $this->profileUser = new User($con, $profileUsername);
    }
    public function getProfileUserObj() {
        return $this->profileUser;
    }
    public function getProfileUsername() {
        return $this->profileUser->getUsername();
    }
    public function userExists() {
        $profileUsername = $this->getProfileUsername();
        $q = $this->con->users->findOne(['username' => $profileUsername]);
        return $q != null;
    }
    public function getCoverPhoto() {
        return "assets/images/coverPhotos/defaultCover.jpg";
    }
    public function getProfileFullName() {
        return $this->profileUser->getName();
    }
    public function getProfilePic() {
        return $this->profileUser->getPicture();
    }
    public function getSubCount() {
        return $this->profileUser->getSubscriberCount();
    }

    public function getAllUserDetails() {
        return array(
            "Name" => $this->getProfileFullName(),
            "Username" => $this->getProfileUsername(),
            "Subscribers" => $this->getSubCount(),
            "Sign up date" => $this->getProfileSignupDate(),
        );
    }
    public function getProfileSignupDate() {
        $date = $this->profileUser->getSignUpDate();
        return Date("F j, Y",strtotime($date));
    }
    
}

?>