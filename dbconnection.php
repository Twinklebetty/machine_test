<?php
    //database configuration
    $host       = "localhost";
    $user       = "root";
    $pass       = "";
    $database   = "machinetest";
    $connect = new mysqli($host, $user, $pass, $database);

    if (!$connect) {
        die ("connection failed: " . mysqli_connect_error());
    } else {
        //echo "connection successful";
    }
	

?>