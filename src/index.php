<?php
    // Database connection parameters
    $host = "mysql_db"; // Hostname of the MySQL server
    $username = "root"; // MySQL username
    $password = "root"; // MySQL password
    $db_name = "StudentDB"; // Name of the database to connect to

    // Create a database connection
    $connect = mysqli_connect($host,$username, $password,$db_name);
    
     // Check if the connection was successful
    if(!$connect){
        die("Connection failed: ".mysqli_connect_error());
    }
    // else{
    //     echo "Succesfully connected to the DB";
    // }
?>