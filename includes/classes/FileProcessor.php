<?php 

class FileProcessor {
    public function __construct($con) {
        $this->con = $con;
    }
    public function upload($file, $details) {
        if ($details['usage'] == "book") {
            $success = $this->uploadBook($file, $details);
            return $success;
        }
    }
    private function uploadBook($file, $details) {
        $data = $this->getFileData($file);
        $compressed = utf8_encode(gzcompress($data, 6));
        if ($this->validateSize($compressed) == false) {
            return false;
        }
        $fileData = [
            'content' => $compressed,
            'username' => $details['username'],
            'usage' => $details['usage']
        ];
        $result = $this->insertFile($fileData);
        if ($result == false) {
            return false;
        }
        $details['file'] = $result;
        $result = $this->insertBook($details);
        if ($result == false) {
            return false;
        }
        return true;
    }
    private function insertFile($file) {
        try {
            $q = $this->con->files->insertOne($file);
            return $q->getInsertedId();
        } catch (\Exception $e) {
            echo $e;
            return false;
        }
    }
    private function insertBook($file) {
        try {
            $q = $this->con->books->insertOne($file);
            return $q->getInsertedId();
        } catch (\Exception $e) {
            echo $e;
            return false;
        }
    }
    private function validateSize($file) {
        if(strlen($file) > 17000000) { //bytes
            // array_push($this->errors, Constants::$imageTooBig);
            return false;
        }
        return true;
    }
    private function getFileData($file) {
        return file_get_contents($file["tmp_name"]);
    }
}
?>