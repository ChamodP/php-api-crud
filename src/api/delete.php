<?php
    // Include the necessary files and configurations
    include('../index.php');

    // Set headers for cross-origin requests and response format
    header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
    header('Content-Type: application/json'); // Set response content type as JSON
    header('Access-Control-Allow-Method: DELETE'); // Allow only DELETE requests
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    // Get the request method (GET, POST, etc.)
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    // If the request method is DELETE
    if ($requestMethod == "DELETE") {
        // Get studentID from the URL parameter
        if (isset($_GET['StudentID']) && !empty($_GET['StudentID'])) {
            $studentID = $_GET['StudentID'];
            $result = deleteStudentRecord($studentID);
            echo $result;
        } else {
            // If studentID parameter is not provided, return an error response
            $data = [
                'status' => 400, // Bad Request
                'message' => 'Missing StudentID parameter',
            ];
            header("HTTP/1.0 400 Bad Request");
            echo json_encode($data);
        }
    } else {
        // If the request method is not DELETE, return an error response
        $data = [
            'status' => 405, // Method Not Allowed
            'message' => 'Invalid request method (' . $requestMethod . ')',
        ];
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode($data);
    }

    // Function to delete a student record
    function deleteStudentRecord($studentID) {
        global $connect; // Access the global database connection

        // Check if the student ID exists in the database
        $checkQuery = "SELECT COUNT(*) as count FROM Student WHERE StudentID = '$studentID'";
        $checkResult = mysqli_query($connect, $checkQuery);
        $checkRow = mysqli_fetch_assoc($checkResult);

        if ($checkRow['count'] == 0) {
            // If the student ID doesn't exist, return an error response
            $errorData = [
                'status' => 404, // Not Found
                'message' => 'Student record not found',
            ];
            header("HTTP/1.0 404 Not Found");
            return json_encode($errorData);
        }

        // SQL query to delete the student record
        $query = "DELETE FROM Student WHERE StudentID = '$studentID'";

        // Execute the query
        $query_run = mysqli_query($connect, $query);

        if ($query_run) {
            // If the record was successfully deleted
            $responseData = [
                'status' => 200, // Success
                'message' => 'Student record deleted successfully',
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($responseData);
        } else {
            // If there's an error in executing the query
            $errorData = [
                'status' => 500, // Internal Server Error
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($errorData);
        }
    }
?>
