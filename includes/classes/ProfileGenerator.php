<?php
include_once("ProfileData.php");
class ProfileGenerator {
    private $con, $loggedInUser, $profileUsername, $profileData;
    public function __construct($con, $loggedInUser, $profileUsername) {
        $this->con = $con;
        $this->loggedInUser = $loggedInUser;
        $this->profileData = new ProfileData($con, $profileUsername);
        $this->profileUsername = $profileUsername;
    }
    public function create() {
        if(!$this->profileData->userExists()) {
            return "This user does not exist";
        }
        $coverPhotoSection = $this->createCoverPhotoSection();
        $headerSection = $this->createHeaderSection();
        $tabSection = $this->createTabSection();
        // $contentSection = $this->createContentSection();
        return "<div class='profileContainer'>
                    $coverPhotoSection
                    $headerSection
                    $tabSection
                </div>";
    }
    public function createCoverPhotoSection() {
        $coverPhotoSrc = $this->profileData->getCoverPhoto();
        $name = $this->profileData->getProfileFullName();
        return "<div class='coverPhotoContainer'>
                    <img src='$coverPhotoSrc' class='coverPhoto'>
                    <span class='channelName'>$name</span>
                </div>";
    }
    public function createHeaderSection() {
        $profileImage = $this->profileData->getProfilePic();
        $profileImage = base64_encode($profileImage);
        $name = $this->profileData->getProfileFullName();
        $subCount = $this->profileData->getSubCount();

        $button = $this->createHeaderButton();

        return "<div class='profileHeader'>
                    <div class='userInfoContainer'>
                        <img class='profileImage' src='data:image/*;base64,$profileImage'>
                        <div class='userInfo'>
                            <span class='title'>$name</span>
                            <span class='subscriberCount'>$subCount subscribers</span>
                        </div>
                    </div>
                    <div class='buttonContainer'>
                        <div class='buttonItem'>
                            $button
                        </div>
                    </div>
                </div>";
    }
    public function createTabSection() {
        return "<ul class='nav nav-tabs'>
                    <li class='nav-item'>
                        <a class='nav-link active' data-toggle='tab' href='#videos'>VIDEOS</a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' data-toggle='tab' href='#about'> ABOUT </a>
                    </li>
                </ul>";
    }
    public function createContentSection() {
        // $videos = $this->profileData->getUserVideos();
        // if(sizeof($videos)>0) {
        //     $videoGrid = new VideoGrid($this->con, $this->loggedInUser);
        //     $htmlGrid = $videoGrid->create($videos, null, false);
        // }
        // else {
        //     $htmlGrid = "<span>This user has no videos.</span>";
        // }
        
        $aboutSection = $this->createAboutSection();
        return "<div class='tab-content channel-content'>
                    <div role='tabpanel' class='tab-pane active' id='videos'>
                        <ul class='list-group media-list media-list-stream'>
                            placeholder
                        </ul>
                    </div>
                    <div role='tabpanel' class='tab-pane fade in' id='about'>
                        <ul class='list-group media-list media-list-stream'>
                            $aboutSection
                        </ul>
                    </div>
                </div>";
    }
    private function createHeaderButton() {
        if($this->loggedInUser->getUsername() == $this->profileUsername) {
            return "";
        }
        else {
            return ButtonMaker::createSubscribeButton(
                                                $this->con, 
                                                $this->profileData->getProfileUserObj(),
                                                $this->loggedInUser);
        }
    }
    private function createAboutSection() {
        $html = "<div class='section'>
                    <div class='title'>
                        <span>Details</span>
                    </div>
                    <div class='values'>";
        $details = $this->profileData->getAllUserDetails();
        foreach($details as $title=>$value) {
            $html .= "<span>$title: $value</span>";
        }

        $html .= "</div> </div>";
        return $html;
    }
}

?>