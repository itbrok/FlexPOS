<?php

// Change the line below to your timezone!
date_default_timezone_set('Asia/Baghdad');


// Declear error log file
define("ERROR_LOG_FILE_NAME", "error.log");

// {@default variables}
$flex = [
    "version" => "1.1.2",
    "update_link" => "https://github.com/itbrok/Flex/raw/main/update.json",
    "installed" => false,
];



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
    ];
    file_put_contents(ERROR_LOG_FILE_NAME, json_encode($err, JSON_PRETTY_PRINT). "\n", FILE_APPEND);
}

// Set user-defined error handler function
set_error_handler("ErrorHandler");

