<?php
require_once("ButtonMaker.php");  // we are already in the classes folder when this file is called by Comment.php
class CommentControls {
    private $con, $comment, $loggedInUser;

    public function __construct($con, $comment, $loggedInUser) {
        $this->con = $con;
        $this->comment = $comment;
        $this->loggedInUser = $loggedInUser;
    }

    public function create() {
        $replyButton = $this->createReplyButton();
        $likesCount = $this->createLikesCount();
        $likeB = $this->createLikeButton();
        $dislikeB = $this->createDislikeButton();
        $replySection = $this->createreplySection();
        return "<div class='controls'>
                    $replyButton
                    $likesCount
                    $likeB
                    $dislikeB
                </div>
                $replySection";
    }

    private function createReplyButton() {
        $text = "REPLY";
        $action = "toggleReply(this)";

        return ButtonMaker::createButton($text, null, $action, null);
    }

    private function createLikesCount() {
        $text = $this->comment->getLikes();
        if($text == 0) $text = "";

        return "<span class='likesCount'>$text</span>";
    }

    private function createreplySection() {
        $postedBy = $this->loggedInUser->getUsername(); // for the composing line
        $videoId = $this->comment->getVideoId();
        $commentId = $this->comment->getId();

        $profileButton = ButtonMaker::createUserProfileButton($this->con, $postedBy);

        $cancelAction = "toggleReply(this)";
        $cancelButton = ButtonMaker::createButton("Cancel", null, $cancelAction, "cancelComment");

        $postButtonAction = "postComment(this, \"$postedBy\", $videoId, $commentId, \"repliesSection\")";
        $postButton = ButtonMaker::createButton("Reply", null, $postButtonAction, "postComment");

        // get comments html
        return "<div class='commentForm hidden'>
                    $profileButton
                    <textArea class='commentBodyClass' placeholder='Add a public comment'></textArea>
                    $cancelButton
                    $postButton
                </div>";
    }

    private function createLikeButton() {
        $commentId = $this->comment->getId();
        $videoId = $this->comment->getVideoId();
        $action = "likeComment($commentId, this, $videoId)";
        $class = "likeButton";
        $imgSrc = "assets/images/icons/thumb-up.png";

        if($this->comment->wasLikedBy()) {
            $imgSrc = "assets/images/icons/thumb-up-active.png";
        }

        return ButtonMaker::createButton("", $imgSrc, $action, $class);
    }
    private function createDislikeButton() {
        $commentId = $this->comment->getId();
        $videoId = $this->comment->getVideoId();
        $action = "dislikeComment($commentId, this, $videoId)";
        $class = "dislikeButton";
        $imgSrc = "assets/images/icons/thumb-down.png";

        if($this->comment->wasDislikedBy()) {
            $imgSrc = "assets/images/icons/thumb-down-active.png";
        }

        return ButtonMaker::createButton("", $imgSrc, $action, $class);
    }

}

?>