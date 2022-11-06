<?php


require_once("functions.php");

function errorMsg() {
    if (!checkLogin()) {
        echo json_encode(["ok" => false, "msg" => "يجب تسجيل الدخول اولا", "code" => 4]);
        exit();
    }
}
define("BASE_URL", str_replace(basename(__FILE__),"","http://" . $_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']));

function printOrder($data){
    if($data){
        $printer = getPrinterByID(getUserByID($_SESSION["user_id"])["printer_id"]);
        $paper = json_decode(getSittings("papers"),true)[$printer["size"]];
        $basic = $data;
        unset($basic['items']);

        $allItems = array_chunk($data["items"], $paper["@max_items_count"]);
        $rnd = 1;

        foreach($allItems as $sub){
        
            $html = new PrintOrder($basic, $sub, $paper, $rnd, count($allItems));
            
            file_put_contents("print/temp/$data[order_id]-$rnd.html", $html->getHTML());
        
            exec('"print/printhtml.exe" "print/temp/' . $data["order_id"] . "-" . $rnd .  '.html" -p "'.(($printer["PrinterName"]) ?? "Default").'" -l 0 -r 0 -t 0 -b 0');
            $rnd++;
        }
        for($i=1; $i<=$rnd; $i++){
            unlink("print/temp/$data[order_id]-$i.html");
        }
        return true;
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