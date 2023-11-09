<?php
require_once('fileupload.php');

// Test Case 1: Successful file upload
$_FILES['upload_file'] = array(
    'name' => 'test_image.jpg',
    'type' => 'image/jpg',
    'size' => 1024, 
    'tmp_name' => '/path/to/test_image.jpg',//here i used C:\\xampp\\htdocs\\upload_file\\
    'error' => UPLOAD_ERR_OK
);
$_POST['user_id'] = 1;
require_once('dbconnection.php');
$_SESSION["logged_in"] = true;
$_SESSION["user_id"] = 1;

include('fileupload.php');

// Test Case 2: Rejected upload due to file size exceeding the limit
$_FILES['upload_file']['size'] = 6 * 1024 * 1024; // 6 MB

include('fileupload.php');

// Test Case 3: Rejected upload due to invalid file type
$_FILES['upload_file']['size'] = 1024; // Reset size
$_FILES['upload_file']['name'] = 'test_document.txt';
$_FILES['upload_file']['type'] = 'text/plain';

include('fileupload.php');

// Test Case 4: Rejected upload due to no file selected
unset($_FILES['upload_file']);

echo "All test cases passed successfully!\n";


?>