<?php
    include('../index.php');
    
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: GET');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-with');

    // Get the request method (GET, POST, etc.)
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    // If the request method is GET
    if($requestMethod == "GET"){
        // Call the function to get the student list and echo the response
        $studentList = getStudentList();
        echo $studentList;
    }
    else{
        //print error message
        $data = [
            'status' => 405,
            'message'=> 'Invalid request method (' .$requestMethod .')',
        ];
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode($data);
    }

    // taking the student details 
    function getStudentList(){
        global $connect ;
    
        // Call the function to get the student list and echo the response
        $query = "SELECT * FROM Student";

        // Execute the query
        $query_run = mysqli_query($connect, $query);
        
        
        if($query_run){
    
            if(mysqli_num_rows($query_run) > 0){
                
                // Fetch all the rows as an associative array
                $response  = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
    
                $data = [
                    'status' => 200,
                    'message'=> 'Student list fetched successsfully',
                    'data' => $response
                ];
                header("HTTP/1.0 200 Success");
                return json_encode($data);
    
            }
            else{
                //no student records found
                $data = [
                    'status' => 404,
                    'message'=> 'No student found',
                ];
                header("HTTP/1.0 404 No student records");
                return json_encode($data);
            }
        }
        else{
            // If there's an error in executing the query
            $data = [
                'status' => 500,
                'message'=> 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }


?>