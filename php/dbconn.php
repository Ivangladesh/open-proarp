<?php
function OpenCon()
 {
    $conn = new PDO('mysql:host=localhost;dbname=proarp', "app_usr", "password");
    return $conn;
 }
?>