<?php
class FormHeaderGenerator {
    public static function generateHeader($title, $subtitle, $description, $titleColor = '', $subtitleColor = '', $descriptionColor = '') {
        return "
            <div class='text-center' style='margin-bottom: 20px;'>
                <h1 style='color: $titleColor;'>$title</h1>
                <h3 style='color: $subtitleColor;'>$subtitle</h3>
                <p style='color: $descriptionColor;'>$description</p>
            </div>
        ";
    }
}
