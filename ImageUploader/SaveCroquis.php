<?php
if (isset($_POST['imgData'])) {
    $imgData = $_POST['imgData'];
    $imgData = str_replace('data:image/png;base64,', '', $imgData);
    $imgData = str_replace(' ', '+', $imgData);
    $data = base64_decode($imgData);
    $file = '../images/croquis/sketch_' . uniqid() . '.png';
    if (file_put_contents($file, $data)) {
        echo 'Success';
    } else {
        echo 'Failed';
    }
} else {
    echo 'No image data';
}
?>