<?php
    $host = "mysql_db";
    $username = "root";
    $password = "root";
    $db_name = "StudentDB";

    // Create connection
    $conn = mysqli_connect($host,$username, $password,$db_name);
    //$con = new mysqli('mysql_db','root','root','studentdb');
    if(!$conn){
        die("Connection failed: ".mysqli_connect_error());
    }
    else{
        echo "Connected Succesfully to the database";
    }
?>