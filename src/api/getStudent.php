<?php
    // Include the necessary files and configurations
    include('../index.php');

    // Set headers for cross-origin requests and response format
    header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
    header('Content-Type: application/json'); // Set response content type as JSON
    header('Access-Control-Allow-Method: GET'); // Allow only GET requests
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    // Get the request method (GET, POST, etc.)
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    // If the request method is GET
    if ($requestMethod == "GET") {
        // Check if the studentID parameter is provided in the query string
        if (isset($_GET['studentID'])) {
            $studentID = $_GET['studentID'];
            $studentRecord = getStudentRecord($studentID);
            echo $studentRecord;
        } else {
            // If studentID parameter is not provided, return an error response
            $data = [
                'status' => 400, // Bad Request
                'message' => 'Missing studentID parameter',
            ];
            header("HTTP/1.0 400 Bad Request");
            echo json_encode($data);
        }
    } 
    else {
        // If the request method is not GET, return an error response
        $data = [
            'status' => 405, // Method Not Allowed
            'message'=> 'Invalid request method (' .$requestMethod .')',
        ];
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode($data);
    }

    // Function to fetch a particular student's record by student ID
    function getStudentRecord($studentID) {
        global $connect; // Access the global database connection

        // Sanitize the studentID to prevent SQL injection
        $sanitizedStudentID = mysqli_real_escape_string($connect, $studentID);

        // SQL query to select a specific student by their ID
        $query = "SELECT * FROM Student WHERE studentID = '$sanitizedStudentID'";

        // Execute the query
        $query_run = mysqli_query($connect, $query);

        if ($query_run) {
            if (mysqli_num_rows($query_run) > 0) {
                // Fetch the row as an associative array
                $studentData = mysqli_fetch_assoc($query_run);

                // Create a response data structure for successful case
                $data = [
                    'status' => 200, // Success
                    'message' => 'Student record fetched successfully',
                    'data' => $studentData // The student's record data
                ];
                header("HTTP/1.0 200 Success");
                return json_encode($data); // Return the response as JSON

            } else { // No student record found
                $data = [
                    'status' => 404, // Not Found
                    'message' => 'Student id '.$sanitizedStudentID.' not found',
                ];
                header("HTTP/1.0 404 Not Found");
                return json_encode($data);
            }
        } else {
            // If there's an error in executing the query
            $data = [
                'status' => 500, // Internal Server Error
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }
?>
