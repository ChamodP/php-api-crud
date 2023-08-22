<?php
    // Include the necessary files and configurations
    include('../index.php');

    // Set headers for cross-origin requests and response format
    header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
    header('Content-Type: application/json'); // Set response content type as JSON
    header('Access-Control-Allow-Method: PUT'); // Allow only PUT requests
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    // Get the request method (GET, POST, etc.)
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    // If the request method is PUT
    if ($requestMethod == "PUT") {
        // Get PUT data and update the student record
        $putData = json_decode(file_get_contents("php://input"), true);
        
        if (isset($_GET['StudentID'])) {
            $studentID = $_GET['StudentID'];


            if (!empty($putData)) {
                $result = updateStudentRecord($studentID, $putData);
                echo $result;
            } else {
                // If JSON data is missing, return an error response
                $data = [
                    'status' => 400, // Bad Request
                    'message' => 'Missing JSON data for update',
                ];
                header("HTTP/1.0 400 Bad Request");
                echo json_encode($data);
            }

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
        // If the request method is not PUT, return an error response
        $data = [
            'status' => 405, // Method Not Allowed
            'message' => 'Invalid request method (' . $requestMethod . ')',
        ];
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode($data);
    }

    // Function to update a student record
    function updateStudentRecord($studentID, $data) {
        global $connect; // Access the global database connection

        // Construct the SET clause for the UPDATE query
        $setClause = '';
        foreach ($data as $field => $value) {
            $sanitizedValue = mysqli_real_escape_string($connect, $value);
            $setClause .= "$field = '$sanitizedValue', ";
        }
        $setClause = rtrim($setClause, ', ');

        // SQL query to update the student record
        $query = "UPDATE Student SET $setClause WHERE StudentID = '$studentID'";

        // Execute the query
        $query_run = mysqli_query($connect, $query);

        if ($query_run) {
            // If the record was successfully updated
            $responseData = [
                'status' => 200, // Success
                'message' => 'Student record updated successfully',
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
