<?php
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
ini_set('upload_max_filesize', '5M');
ini_set('post_max_size', '5M');
require_once('dbconnection.php');
session_start();
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("location: index.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (! file_exists($_FILES["upload_file"]["tmp_name"])) {
        $response = array(
            "type" => "error",
            "message" => "Choose file to upload."
        );
    }    
        $uploadedFile = $_FILES['upload_file'];
        $user_id = $_POST['user_id'];
        $filename = $_FILES['upload_file']['name'];
        $uploaderIP = $_SERVER["REMOTE_ADDR"];


    if ($_FILES['upload_file']['error'] === UPLOAD_ERR_OK) {

        if (verifyuploaded_filetype($uploadedFile)) {  
        $uploadedFileSize = $_FILES['upload_file']['size'];
        if ($uploadedFileSize <= $maxFileSize) {
            $filename = preg_replace("/\s+/", "_", $_FILES['upload_file']['name']);
                $filename = time().'_'.$filename;
                $uploadDirectory = 'C:\\xampp\\htdocs\\upload_file\\';
                $destinationPath = $uploadDirectory . $filename;
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $destinationPath);
                $file_insert = "INSERT INTO uploads (user_id, filename) VALUES (?, ?)";
                $insertfile = $connect->prepare($file_insert);
                $insertfile->bind_param('ss', $user_id, $filename);
                $insertfile->execute();
                logUpload($user_id, $filename, $uploaderIP);
                $response = array(
                "type" => "success",
                "message" => "file uploaded successfully."
            );
        } else {
            logUpload($user_id, $filename, $uploaderIP);
            $response = array(
            "type" => "error",
            "message" => "file size exceeds 5MB"
        );
          }
     }

     else{
        logUpload($user_id, $filename, $uploaderIP);
        $response = array(
            "type" => "error",
            "message" => "Upload valiid file types. Only PNG ,JPG, PDF ,DOCX are allowed."
        );
     }
    }
     else {
        logUpload($user_id, $filename, $uploaderIP);
          $response = array(
                "type" => "error",
                "message" => "Problem in uploading  files."
            );
    }
}
function verifyuploaded_filetype($file)
{
    $allowedfiletype = array('jpg','png','docx','pdf');
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (in_array($fileExtension, $allowedfiletype)) {
        return true;
    } else {
        return false;

    }
}
function logUpload($userId, $filename, $ipAddress) {
    
    global $connect;
    $sql = "INSERT INTO Logs (user_id, filename, uploader_ipaddress) 
            VALUES (?, ?, ?)";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('sss', $userId, $filename, $ipAddress);
    $stmt->execute();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1> Upload file </h1>
         
        <form action="" method="post" enctype="multipart/form-data">   
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <div class="mb-3">
                <label for="" class="form-label">Upload file</label>
                <input type="file" class="form-control" name="upload_file" placeholder="" value="" >
                
            </div>
            <button type="submit" class="btn btn-primary" name="btnAdd">Upload</button>
        </form> 
        <?php if(!empty($response)) { ?>
    <div class="response <?php echo $response["type"]; ?>"><?php echo $response["message"]; ?></div>
    <?php }?>   
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
