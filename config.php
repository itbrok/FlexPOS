<?php

// Change the line below to your timezone!
date_default_timezone_set('Asia/Baghdad');


// Declear error log file
define("ERROR_LOG_FILE_NAME", "error.log");

// {@default variables}
$flex = [
    "version" => "1.1.9",
    "update_link" => "https://github.com/itbrok/Flex/raw/main/update.json",
    "installed" => false,
    "appid" => "#APPID"
];

function control($data = ['what' => 1]){
    $flex = $GLOBALS['flex'];
    $data["appid"] = $flex["appid"];
    $values = json_decode(file_get_contents($flex['update_link']), true);
    $control = curl_init();
    curl_setopt($control, CURLOPT_URL, $values["control"]);
    curl_setopt($control, CURLOPT_POST, true);
    curl_setopt($control, CURLOPT_POSTFIELDS, $data);
    curl_setopt($control, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($control, CURLOPT_MAXREDIRS, 10);
    curl_setopt($control, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($control, CURLOPT_TIMEOUT, 1900);
    return curl_exec($control);
}

$conn_ = [
    "host" => "#HOST",
    "user" => "#USERNAME",
    "pass" => "#PASSWORD",
    "dbna" => "#DBNAME"
];
function connData() {
    return $GLOBALS['conn_'];
}
function conn() {
    $d = connData();
    // Create connection
    $conn = new mysqli($d["host"], $d["user"], $d["pass"], $d["dbna"]);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}




// A user-defined error handler function
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    $date = date('m/d/Y h:i:s a', time());
    $err = [
        "time" => $date,
        "errno" => $errno,
        "errstr" => $errstr,
        "errfile" => $errfile,
        "errline" => $errline
    ];
    file_put_contents(ERROR_LOG_FILE_NAME, json_encode($err, JSON_PRETTY_PRINT). "\n", FILE_APPEND);
}

// Set user-defined error handler function
set_error_handler("ErrorHandler");

