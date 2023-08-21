<?php
    include('../index.php');

    $id = $_POST['index'];
    $statement = "SELECT * FROM Students WHERE id = '$id' ";
    $result = mysqli_query($conn, $statement);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $firstName = $row['firstName'];
            $lastName = $row['lastName'];
        

            // ?Display or use the retrieved student details
            echo "Student first name: $studentName<br>";
            echo "Student last name: $studentAge<br>";
            // echo "Student Grade: $studentGrade<br>";
            // You can display other details here as well
        } else {
            echo "No student found with the provided ID.";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);

?>