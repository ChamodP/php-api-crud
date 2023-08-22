<?php
// Include the necessary files and configurations
include('../index.php');

// Set headers for cross-origin requests and response format
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Content-Type: application/json'); // Set response content type as JSON
header('Access-Control-Allow-Method: POST'); // Allow only POST requests
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Get the request method (GET, POST, etc.)
$requestMethod = $_SERVER["REQUEST_METHOD"];

// If the request method is POST
if ($requestMethod == "POST") {
    // Get POST data and create the student record
    $postData = json_decode(file_get_contents("php://input"), true);
    $result = createStudentRecord($postData);

    // Echo the response
    echo $result;
} else {
    // If the request method is not POST, return an error response
    $data = [
        'status' => 405,
        // Method Not Allowed
        'message' => 'Invalid request method (' . $requestMethod . ')',
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}

// Function to create a new student record
function createStudentRecord($data)
{
    global $connect; // Access the global database connection

    // Check if required fields are present in the input data
    if (!isset($data['FirstName']) || !isset($data['LastName']) || !isset($data['DateofBirth']) || !isset($data['Address']) || !isset($data['Email'])) {
        $errorData = [
            'status' => 400,
            // Bad Request
            'message' => 'Missing required fields',
        ];
        header("HTTP/1.0 400 Bad Request");
        return json_encode($errorData);
    }

    // Extract data from the POST request
    $firstName = $data['FirstName'];
    $lastName = $data['LastName'];
    $dateOfBirth = $data['DateofBirth'];
    $address = $data['Address'];
    $email = $data['Email'];

    // Check if has empty values in the input data
    if (empty($firstName) || empty($lastName) || empty($dateOfBirth) || empty($address) || empty($email)) {

        $errorData = [
            'status' => 400,
            // Bad Request
            'message' => 'Empty fields detected',
        ];
        header("HTTP/1.0 400 Bad Request");
        return json_encode($errorData);
    }

    // Sanitize and escape input data to prevent SQL injection
    $sanitizedFirstName = mysqli_real_escape_string($connect, $firstName);
    $sanitizedLastName = mysqli_real_escape_string($connect, $lastName);
    $sanitizedDateOfBirth = mysqli_real_escape_string($connect, $dateOfBirth);
    $sanitizedAddress = mysqli_real_escape_string($connect, $address);
    $sanitizedEmail = mysqli_real_escape_string($connect, $email);

    // SQL query to insert a new student record
    $query = "INSERT INTO Student (FirstName, LastName, DateofBirth, Address, Email) VALUES ('$sanitizedFirstName', '$sanitizedLastName', '$sanitizedDateOfBirth', '$sanitizedAddress', '$sanitizedEmail')";

    // Execute the query
    $query_run = mysqli_query($connect, $query);

    if ($query_run) {
        // If the record was successfully inserted
        $data = [
            'status' => 201,
            // Created
            'message' => 'Student record created successfully',
        ];
        header("HTTP/1.0 201 Created");
        return json_encode($data);
    } else {
        // If there's an error in executing the query
        $data = [
            'status' => 500,
            // Internal Server Error
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}
?>