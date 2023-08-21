<?php
    $host = "mysql_db";
    $username = "root";
    $password = "root";
    $db_name = "StudentDB";

    // Create connection
    $connect = mysqli_connect($host,$username, $password,$db_name);
    
    //chechking correctly connected or not
    if(!$connect){
        die("Connection failed: ".mysqli_connect_error());
    }
    // else{
    //     echo "Succesfully connected to the DB";
    // }
?>