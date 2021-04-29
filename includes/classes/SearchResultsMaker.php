<?php
class SearchResultsMaker {
    private $con, $loggedInUser;
    public function __construct($con, $loggedInUser)
    {
        $this->con = $con;
        $this->loggedInUser = $loggedInUser;
    }
    public function getVideos($term, $orderBy, $order) {
        $q = $this->con->prepare("SELECT * FROM videos WHERE title LIKE CONCAT('%', :term, '%')
                                    OR uploadedBy LIKE CONCAT('%', :term, '%') ORDER BY $orderBy $order");
        $q->bindParam(":term", $term);
        $q->execute();
        $videos = array();
        while($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $video = new Video($this->con, $row, $this->loggedInUser);
            array_push($videos, $video);
        }
        return $videos;
    }
}


?>