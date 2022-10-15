<?php

function generateRandomString($length = 6) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        die("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

if (@$_POST) {
    if (strlen(@$_POST['host'] ?? "") > 0 && strlen(@$_POST['username'] ?? "") > 0) {

# Generate database name
        $db_name = "flexPosApp_" . generateRandomString();

# Create database

        // Create connection
        $conn = mysqli_connect($_POST['host'], $_POST['username'], $_POST['password']);
        // Check connection
        if (!$conn) {
            die(json_encode(["ok" => false, "msg" => "فشل الاتصال بالسيرفر, تاكد من المعلومات"]));
        }

        // Create database
        $sql = "CREATE DATABASE $db_name";
        if (!mysqli_query($conn, $sql)) {
            die(json_encode(["ok" => false, "msg" => mysqli_error($conn)]));
        }
        // close connection
        mysqli_close($conn);

# Import default database
        include_once("functions.php");
        $conn = mysqli_connect($_POST['host'], $_POST['username'], $_POST['password'], $db_name);
        restoreMysqlDB('install/database.sql', $conn);
        // close connection
        mysqli_close($conn);

# Edit config file
        // get config script from its file
        $config_data = file_get_contents("config.php");

        // replace default values with new values
        $config_data = str_replace(["#HOST", "#USERNAME", "#PASSWORD", "#DBNAME", '"installed" => false'], [$_POST['host'], $_POST['username'], $_POST['password'], $db_name, '"installed" => true'], $config_data);

        // save config file with new values
        file_put_contents("config.php", $config_data);

# Create a backUp
        
        if($conn_['host'] != "#HOST"){
            $backUp = getBackUp();
            $backup_file_name = 'Flex_backup_' . time() . '.sql';
            file_put_contents($backup_file_name, $backUp);
            $conn_ = [
                "host" => $_POST['host'],
                "user" => $_POST['username'],
                "pass" => $_POST['password'],
                "dbna" => $db_name
            ];
            $conn = mysqli_connect($_POST['host'], $_POST['username'], $_POST['password'], $db_name);
            restoreMysqlDB($backup_file_name, $conn);
            // close connection
            mysqli_close($conn);
        }

# Save the new config file
        $d = connData();
        $search = ['"host" => "'.$d['host'].'",', '"user" => "'.$d['user'].'",', '"pass" => "'.$d['pass'].'",', '"dbna" => "'.$d['dbna'].'"'];
        $replace = ['"host" => "'.$_POST['host'].'",', '"user" => "'.$_POST['username'].'",', '"pass" => "'.$_POST['password'].'",', '"dbna" => "'.$db_name.'"'];
        $config_data = str_replace($search,$replace,$config_data);
        file_put_contents("config.php", $config_data);

# Add printers to database
        $conn_ = [
            "host" => $_POST['host'],
            "user" => $_POST['username'],
            "pass" => $_POST['password'],
            "dbna" => $db_name
        ];
        if(! addAvailablePrintersToDB()){
            echo json_encode(["ok" => false, "msg" => "حدثت مشكلة"]);
            exit();
        }

# Login as admin
        if(!checkLogin()){
            $admin = getUserByID(1);
            $_SESSION["user_id"] = $admin['id'];
            $_SESSION['password'] = $admin['password'];
        }

# Return ok 
        echo json_encode(["ok" => true]);

# Delete 'install' folder
        $dirname = "install";
        deleteDir($dirname);
    } else {
        echo json_encode(["ok" => false, "msg" => "<div><strong> !خطا </strong>يرجى ملئ كافة الحقول</div>"]);
    }
} else {
    if ($flex["installed"] == false) {
        include("install/index.html");
    }
}
