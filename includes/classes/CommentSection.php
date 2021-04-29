<?php

class CommentSection {
    private $video, $con, $loggedInUser;

    public function __construct($video, $con,$loggedInUser) {
        $this->video = $video;
        $this->con = $con;
        $this->loggedInUser = $loggedInUser;
    }

    public function create() {
        return $this->createCommentSection();
    }

    private function createCommentSection() {
        $CommentsCount = $this->video->getCommentsCount();
        $postedBy = $this->loggedInUser->getUsername(); // for the composing line
        $videoId = $this->video->getId();

        $profileButton = ButtonMaker::createUserProfileButton($this->con, $postedBy);
        $commentAction = "postComment(this, \"$postedBy\", $videoId, null, \"comments\")";
        $commentButton = ButtonMaker::createButton("COMMENT", null, $commentAction, "postComment");

        $comments = $this->video->getComments();
        $commentItems = "";
        foreach($comments as $comment) {
            $commentItems .= $comment->create();
        }
        return "<div class='commentSection'>
                    <div class='header'>
                        <span class='commentCount'>$CommentsCount Comments</span>

                        <div class='commentForm'>
                            $profileButton
                            <textArea class='commentBodyClass' placeholder='Add a public comment'></textArea>
                            $commentButton
                        </div>
                    </div>

                    <div class='comments'>
                        $commentItems
                    </div>
                </div>";
    }
}

?>