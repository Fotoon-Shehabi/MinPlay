<?php

class User {
    private $con, $data;
    
    public function __construct($con, $username) {
        if(!$username == "") {
            $this->con = $con;
            $q = $this->con->users->findOne(["username" => (string) $username]);
            $this->data = ($q == null) ? "" : $q;
        }
        else {
            $this->data = $username;
        }
    }
    public static function isLoggedIn() {
        return isset($_SESSION["loggedIn"]) ? $_SESSION["loggedIn"] : "";
    }
    public function getUsername() {
        return (!$this->data == "") ? $this->data["username"] : null;
    }
    public function getName() {
        return $this->data["firstName"] . " " . $this->data["lastName"];
    }
    public function getFirstName() {
        return $this->data["firstName"];
    }
    public function getLastName() {
        return $this->data["lastName"];
    }
    public function getEmail() {
        return $this->data["email"];
    }
    public function getPictureID() {
        return $this->data["profilePic"];
    }
    public function getPicture() {
        try {
            $q = $this->con->files->findOne(['_id' => $this->data["profilePic"]]);
            return utf8_decode($q["content"]);
        } catch (\Exception $e) {
            return "Could not retrieve picture from database";
        }
    }
    public function getSignUpDate() {
        return $this->data["signUpDate"];
    }
    public function isSubscribedTo($user) {
        $userFrom = $this->getUsername();
        $q = $this->con->subscriptions->find([['userTo' => $user, 'userFrom' => $userFrom]]);
        return $q != null;
    }
    public function getSubscriberCount() {
        $user = $this->getUsername();
        $q = $this->con->subscriptions->aggregate([
            ['$group' => ['_id' => '$userTo', 'count' => ['$sum' => 1]]],
            ['$match' => ['userTo' => $user]],
            ['$project' => ['_id' => 0, 'count' => 1]]
        ]);
        $q = qResult($q);
        if (sizeof($q)== 0) {
            return 0;
        }
        else {
            return (int) $q['count'];
        }
    }
    public function getSubscriptions() {
        if(!User::isLoggedIn()) {
            return Array();
        }
        $username = $this->getUsername();
        $q = $this->con->subscription->find(["userFrom" => $username], ['_id' => 0, 'userTo' => 1]);
        $subs = array();
        foreach ($q as $document) {
            $user = new User($this->con, $document["userTo"]);
            array_push($subs, $user);
        }
        return $subs;
    }
}

?>