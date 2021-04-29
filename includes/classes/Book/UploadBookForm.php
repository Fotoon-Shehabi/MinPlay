<?php

class UploadBookForm {
    public function __construct($con) {
        $this->con = $con;
    }
    public function createUploadForm() {
        $fileInput = $this->createFileInput();
        $titleInput = $this->createTitleInput();
        $authorInput = $this->createAuthorInput();
        $descInput = $this->createDescriptionInput();
        $coverInput = $this->createCoverInput();
        $uploadButton = $this->createUploadButton();
        return "<form action='processing.php' method='POST' enctype='multipart/form-data'>
                    $fileInput
                    $titleInput
                    $authorInput
                    $descInput
                    $coverInput
                    $uploadButton
                </form>";
    }
    private function createFileInput() 
    {   return "<div class='form-group'>
                    <label for='exampleFormControlFile1' class='uploadFileLabel'>Upload your file</label>
                    <input type='file' class='form-control-file' name='fileInput' id='exampleFormControlFile1' required>
                </div>";
    }
    private function createCoverInput()
    {   return "<div class='form-group'>
                    <label for='exampleFormControlFile1' class='uploadFileLabel'>Upload your cover photo</label>
                    <input type='file' class='form-control-file' name='coverInput' id='exampleFormControlFile1' accept='image/*' required>
                </div>";

    }
    private function createTitleInput() 
    {   return "<div class='form-group'>
                    <input class='form-control' type='text' placeholder='Title' name='titleInput'>
                </div>";
    }
    private function createAuthorInput() 
    {   return "<div class='form-group'>
                    <input class='form-control' type='text' placeholder='Author' name='authorInput'>
                </div>";
    }
    private function createDescriptionInput() 
    {   return "<div class='form-group'>
                    <textarea class='form-control' placeholder='A few words about the book' name='descriptionInput' style='resize: vertical;'></textarea>
                </div>";
    }
    private function createUploadButton()
    {
        return "<button type='submit' class='btn btn-primary' name='uploadBookButton'>Upload</button>";
    }
}


?>