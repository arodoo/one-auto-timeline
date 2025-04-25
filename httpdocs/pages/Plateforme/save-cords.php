<?php
session_start();

if (isset($_POST['lat']) && isset($_POST['lng'])) {
    $_SESSION['lat'] = $_POST['lat'];
    $_SESSION['lng'] = $_POST['lng'];
}else{
    http_response_code(400);
    echo "error";

}
?>