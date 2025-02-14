<?php

session_start();

require 'dbconnect.php';

// INPUT FIELD VALIDATION
function validate($inputData)
{
    global $conn;
    // Sanitize and trim input data
    $validatedData = mysqli_real_escape_string($conn, $inputData);
    return trim($validatedData);
}

// REDIRECT FROM ONE PAGE TO ANOTHER PAGE
function redirect($url, $status)
{
    // Store status in session and redirect
    $_SESSION['status'] = $status;
    header('Location: ' . $url);
    exit(0);
}

// DISPLAY STATUS ANY PROCESS
function alertMessage()
{
    // Display status message if set
    if (isset($_SESSION['status'])) {
        // Removed the $ from 'status'
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <p>' .
            $_SESSION['status'] .
            '</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        unset($_SESSION['status']);
    }
}

// INSERT RECORD
function insert($tableName, $data)
{
    global $conn;

    // Validate table name
    $table = validate($tableName);

    // Prepare columns and values for insertion
    $columns = array_keys($data);
    $values = array_values($data);

    $finalColumn = implode(',', $columns);
    $finalValues = "'" . implode("', '", $values) . "'";

    // Construct and execute SQL query
    $query = "INSERT INTO $table ($finalColumn) VALUES ($finalValues)";
    $result = mysqli_query($conn, $query);
    return $result;
}

// UPDATE RECORD
function update($tableName, $id, $data)
{
    global $conn;

    // Validate table name and ID
    $table = validate($tableName);
    $id = validate($id);

    // Prepare data for update
    $updateDataString = '';
    foreach ($data as $column => $value) {
        $updateDataString .= $column . '=' . "'$value',";
    }
    $finalUpdateData = substr(trim($updateDataString), 0, -1);

    // Construct and execute SQL query
    $query = "UPDATE $table SET $finalUpdateData WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    return $result;
}

function getAll($tableName, $status = null)
{
    global $conn;

    // Validate table name and status
    $table = validate($tableName);
    $status = validate($status);

    // Construct and execute SQL query
    if ($status == 'status') {
        $query = "SELECT * FROM $table WHERE $status='0'";
    } else {
        $query = "SELECT * FROM $table";
    }
    return mysqli_query($conn, $query);
}

function getById($tableName, $id)
{
    global $conn;

    // Validate table name and ID
    $table = validate($tableName);
    $id = validate($id);

    // Construct and execute SQL query
    $query = "SELECT * FROM $table WHERE id= '$id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    // Check and return the result
    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $response = [
            'status' => 200,
            'data' => $row,
            'message' => 'Record Found',
        ];
        return $response;
    } else {
        $response = [
            'status' => 404,
            'message' => 'No Data Found',
        ];
        return $response;
    }
}

// DELETE RECORD
function delete($tableName, $id)
{
    global $conn;

    // Validate table name and ID
    $table = validate($tableName);
    $id = validate($id);

    // Construct and execute SQL query
    $query = "DELETE FROM $table WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    return $result;
}

function checkParamId($type)
{

    if (isset($_GET[$type])) {
        if ($_GET[$type] != '') {

            return $_GET[$type];
        } else {
            return '<h5>No ID FOUND</h5>';
        }
    } else {
        return '<h5>No ID GIVEN</h5>';
    }
}



function jsonResponse($status, $status_type, $message)
{
    echo json_encode([
        'status' => $status,
        'status_type' => $status_type,
        'message' => $message
    ]);
    exit; // Ensure the script stops after outputting the JSON
}


function getCount($tableName)
{
    global $conn;

    $table = validate($tableName);

    $query = "SELECT * FROM $table";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $totalCount = mysqli_num_rows($query_run);
        return $totalCount;
    } else {
        return 'Something WEnt Worng';
    }
}
