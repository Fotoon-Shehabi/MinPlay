<?php
class NavigationMenuProvider {
    private $con, $loggedInUser;

    public function __construct($con, $loggedInUser) {
        $this->con = $con;
        $this->loggedInUser = $loggedInUser;
    }
    public function create() {
        $menuHtml = $this->createNavItem("Home", "assets/images/icons/home.png", "index.php");
        // $menuHtml .= $this->createNavItem("Trending", "assets/images/icons/trending.png", "trending.php");
        $menuHtml .= $this->createNavItem("Subscriptions", "assets/images/icons/subscriptions.png", "subscriptions.php");
        // $menuHtml .= $this->createNavItem("Liked Videos", "assets/images/icons/thumb-up.png", "likedVideos.php");

        if(User::isLoggedIn()){
            $menuHtml .= $this->createNavItem("Settings", "assets/images/icons/settings.png", "settings.php");
            $menuHtml .= $this->createNavItem("Log Out", "assets/images/icons/logout.png", "logout.php");
            $menuHtml .= $this->createSubscriptionsSection();
        }
        
        return "<div class='navigationItems'>$menuHtml</div>";

    }
    private function createNavItem($text, $icon, $link) {
        return "<div class='navigationItem'>
                    <a href='$link'>
                        <img src='$icon'>
                        <span>$text</span>
                    </a>
                </div>";
    }
    private function createSubscriptionsSection() {
        $subscribtions = $this->loggedInUser->getSubscriptions();

        $html = "<span class='heading'>Subscriptions</span>";
        foreach($subscribtions as $sub) {
            $subUsername = $sub->getUsername();
            $pp = $sub->getPicture();
            $html .= $this->createNavItem($subUsername, $pp, "profile.php?username=$subUsername");
        }
        return $html;
    }
}




?>