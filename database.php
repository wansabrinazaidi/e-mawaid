<?php
    $server = "localhost";
    $name = "root";
    $password = "";
    $db_name = "e-tempahan";

    $conn = mysqli_connect($server, $name, $password, $db_name);

    if(!$conn){
        die("Connection Failed". mysqli_connect_error());
    }
?>