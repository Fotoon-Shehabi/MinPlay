<?php
require_once("ButtonMaker.php");
require_once("CommentControls.php");
class Comment {
    private $con, $data, $loggedInUser, $videoId;
    public function __construct($con, $input, $loggedInUser, $videoId)
    {
        if(!is_array($input)) {
            $q = $con->prepare("SELECT * FROM comments WHERE id=:id");
            $q->bindParam(":id", $input);
            $q->execute();

            $input = $q->fetch(PDO::FETCH_ASSOC);
        }
        $this->data = $input;
        $this->con = $con;
        $this->loggedInUser = $loggedInUser;
        $this->videoId = $videoId;
    }

    public function create() {
        $id = $this->data['id'];
        $videoId = $this->getVideoId();
        $body = $this->data["body"];
        $postedBy = $this->data["postedBy"];
        $profileButton = ButtonMaker::createUserProfileButton($this->con, $postedBy);
        $timespan = $this->time_elapsed_string($this->data["datePosted"]);

        $commentControlsObj = new CommentControls($this->con, $this, $this->loggedInUser);
        $commentControls = $commentControlsObj->create();

        $numResponses = $this->getNumOfReplies();
        if($numResponses > 0) {
            $viewRepliesText = "<span class='repliesSection viewReplies' onclick='getReplies($id, this, $videoId)'>
                                    View all $numResponses replies</span>";
        }
        else {
            $viewRepliesText = "<div class='repliesSection'></div>";
        }
        return "<div class='itemContainer'>
                    <div class='comment'>
                        $profileButton

                        <div class='mainContainer'>
                            <div class='commentHeader'>
                                <a href='profile.php?username=$postedBy'>
                                    <span class='username'>$postedBy</span>
                                </a>
                                <span class='timestamp'>$timespan</span>
                            </div>

                            <div class='body'>
                                $body
                            </div>
                        </div>
                    </div>
                    $commentControls
                    $viewRepliesText
                </div>";
        
    }

    public function getNumOfReplies() {
        $id = $this->data['id'];
        $q = $this->con->prepare("SELECT count(*) FROM comments WHERE responseTo=:responseTo");
        $q->bindParam(":responseTo", $id);
        $q->execute();

        return $q->fetchColumn();
    }

    function time_elapsed_string($datetime, $full = false) {
        // from https://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
    public function getId() {
        return $this->data["id"];
    }
    public function getVideoId() {
        return $this->data["videoId"];
    }
    public function wasLikedBy() {
        $id = $this->getId();
        $un = $this->loggedInUser->getUsername();
        $q = $this->con->prepare("SELECT * FROM likes WHERE username=:un AND commentId=:id");
        $q->bindParam(":un", $un);
        $q->bindParam(":id", $id);
        $q->execute();

        return $q->rowCount() > 0;
    }
    public function wasDislikedBy() {
        $id = $this->getId();
        $un = $this->loggedInUser->getUsername();
        $q = $this->con->prepare("SELECT * FROM dislikes WHERE username=:un AND commentId=:id");
        $q->bindParam(":un", $un);
        $q->bindParam(":id", $id);
        $q->execute();

        return $q->rowCount() > 0;
    }
    public function getLikes() {
        $commentId = $this->getId();
        $q = $this->con->prepare("SELECT count(*) as 'count' FROM likes WHERE commentId=:id");
        $q->bindParam(":id", $commentId);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $likesCount = $data['count'];
        
        $q = $this->con->prepare("SELECT count(*) as 'count' FROM dislikes WHERE commentId=:id");
        $q->bindParam(":id", $commentId);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $dislikesCount = $data['count'];
        return $likesCount - $dislikesCount;
    }
    public function like() {
        $id = $this->getId();
        $un = $this->loggedInUser->getUsername();
        if($this->wasLikedBy()) {
            // liked
            $q = $this->con->prepare("DELETE FROM likes WHERE username=:un AND commentId=:id");
            $q->bindParam(":un", $un);
            $q->bindParam(":id", $id);
            $q->execute();
            return -1;

        }
        else {
            // not liked
            $q = $this->con->prepare("DELETE FROM dislikes WHERE username=:un AND commentId=:id");
            $q->bindParam(":un", $un);
            $q->bindParam(":id", $id);
            $q->execute();
            $count = $q->rowCount();
            $q = $this->con->prepare("INSERT INTO likes(username, commentId) VALUES(:un, :id)");
            $q->bindParam(":un", $un);
            $q->bindParam(":id", $id);
            $q->execute();
            return 1+$count;
        }
    }

    public function dislike() {
        $id = $this->getId();
        $un = $this->loggedInUser->getUsername();
        if($this->wasDislikedBy()) {
            // disliked
            $q = $this->con->prepare("DELETE FROM dislikes WHERE username=:un AND commentId=:id");
            $q->bindParam(":un", $un);
            $q->bindParam(":id", $id);
            $q->execute();
            return 1;

        }
        else {
            // not disliked
            $q = $this->con->prepare("DELETE FROM likes WHERE username=:un AND commentId=:id");
            $q->bindParam(":un", $un);
            $q->bindParam(":id", $id);
            $q->execute();
            $count = $q->rowCount();
            $q = $this->con->prepare("INSERT INTO dislikes(username, commentId) VALUES(:un, :id)");
            $q->bindParam(":un", $un);
            $q->bindParam(":id", $id);
            $q->execute();
            return -1 -$count;
        }
    }
    public function getReplies(){
        $id = $this->getId();
        $q = $this->con->prepare("SELECT * FROM comments WHERE responseTo=:commentId ORDER BY datePosted ASC");
        $q->bindParam(":commentId", $id);
        $q->execute();
        $comments = "";
        $videoId = $this->getVideoId();
        while($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $comment = new Comment($this->con, $row, $this->loggedInUser, $videoId);
            $comments .= $comment->create();
        }
        return $comments;
    }

}

?>