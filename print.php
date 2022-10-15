<?php


require_once("functions.php");

function errorMsg() {
    if (!checkLogin()) {
        echo json_encode(["ok" => false, "msg" => "يجب تسجيل الدخول اولا", "code" => 4]);
        exit();
    }
}


function printOrder($data){
    if($data){
        $html = new PrintOrder($data);
        file_put_contents("print/temp/$data[order_id].html", $html->getHtmlString());
        exec('"print/printhtml.exe" "print/temp/' . $data["order_id"] . '.html" -p "'.((getPrinterByID(getUserByID($_SESSION["user_id"])["printer_id"])["PrinterName"]) ?? "Default").'" -l 0 -r 0 -t 0 -b 0');
        return unlink("print/temp/$data[order_id].html");
    }
}


if (key_exists("printOrder", $_POST)) {  // Print Order
    if(is_array($_POST["printOrder"])) {
        errorMsg();
        $data = printOrder($_POST["printOrder"]);
        if ($data != false) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo json_encode(["ok" => false, "msg" => "حدث خطأ"]);
        }
    }else{
        echo json_encode(["ok" => false, "msg" => "حدث خطأ"]);
    }
}