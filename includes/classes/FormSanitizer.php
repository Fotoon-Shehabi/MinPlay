<?php 
class FormSanitizer {
    // The redundancy in the number of functions is to
    // have more control over details ofsanitizing
    public static function sanitizeFormString($inputText) {
        $inputText = strip_tags($inputText);
        $inputText = trim($inputText);
        $inputText = strtolower($inputText);
        $inputText = ucfirst($inputText);
        return $inputText;
    }
    public static function sanitizeFormUsername($inputText) {
        $inputText = strip_tags($inputText);
        $inputText = trim($inputText);
        return $inputText;
    }
    public static function sanitizeFormPassword($inputText) {
        $inputText = strip_tags($inputText);
        return $inputText;
    }
    public static function sanitizeFormEmail($inputText) {
        $inputText = strip_tags($inputText);
        $inputText = trim($inputText);
        return $inputText;
    }
}
?>