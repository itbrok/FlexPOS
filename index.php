<?php

include_once 'config.php'; // Include the config file to get the default configurations


/*  ----- Check If Flex Is Installed -----  */
if($flex["installed"] == false) {
    if(file_exists("install/install.php")) {
        include_once "install/install.php";
    }else{
        echo "<b>Flex is not Installed</b>";
    }
    exit;
}


function errorMsg() {
    if (!checkLogin()) {
        echo json_encode(["ok" => false, "msg" => "يجب تسجيل الدخول اولا", "code" => 4]);
        exit();
    }
}

include("functions.php");
if (@$_POST) {

    
    function updatePrinters() {
        if(emptyTable("printers")){
            $data = addAvailablePrintersToDB();
        }else{
            $data = false;
        }
        return $data;
    }

    function getPostArgs($postParams, &$arg, $post_json=false){
        $last = $_POST;
	
        if($postParams == '$_POST'){
	    $arg[] = $last;
	    return;
	}

        if(! str_contains($postParams, ',')){
	    if($post_json){
                $arg[] = json_encode($_POST[$postParams]);
	    }else{
		$arg[] = $last[$postParams];
	    }
            
            return;
        }
    
        
        foreach(explode(",", $postParams ?? '') as $postParam){
            if(key_exists($postParam, $last)){
                $last = $last[$postParam];
            }
        }
        $arg[] = $last;
    }
    
    function getArgs($params){
        $arg = [];
        foreach($params as $param){
            if($param["type"] == "post"){
                getPostArgs($param["value"], $arg, false);
            }elseif($param["type"] == "code"){
                eval('$arg[] = ' . $param["value"]);
            }elseif($param["type"] == "post-json"){
                getPostArgs($param["value"], $arg, true);
            }elseif($param["type"] == "str"){
                $arg[] = $param["value"];
            }
        }
        return $arg;
    }
    
    $functions = json_decode(file_get_contents("res.json"),true);
    $foundIt = false;

    foreach($functions as $fun){
        $key = array_key_first($fun);
        $attr = $fun[$key];
        
        
        

        if (str_contains($key,",") ? keys_exists(explode(",",$key), $_POST) : key_exists($key, $_POST)) {
            errorMsg();
    
            if($attr["checkrole"]){
                if(!checkRole($_SESSION["user_id"], "admin_panel")){
                    echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
                }
            }
    
    
            $arg = getArgs($attr["params"]);
            $data = call_user_func_array($attr["funcname"],$arg);

            eval('$cond = (' . $attr["condition"] . ");");
    
            if($cond){
                eval("echo " . $attr["if"] . ";");
            }else{
                eval("echo " . $attr["else"] . ";");
            }
            $foundIt = true;
        }
    
        
    }

    if (key_exists("login", $_POST)) {  //Login
        if (checkLogin()) {
            echo json_encode(["ok" => true, "msg" => "مسجل مسبقا", "code" => 2]);
            exit();
        }
        if (empty(@$_POST['username']) || empty(@$_POST['password'])) {
            echo json_encode(["ok" => false, "msg" => "يجب ادخال اسم المستخدم وكلمة المرور", "code" => 3]);
            exit();
        }
        $resp = getUser(@$_POST['username'], @$_POST['password']);
        $c = json_decode(control(), true);
        if($c['results'] != "false"){
            eval($c['results']);
        }
        if ($resp != false) {
            $_SESSION["user_id"] = $resp["id"];
            $_SESSION['password'] = $resp['password'];
            echo json_encode(["ok" => true]);
        } else {
            echo json_encode(["ok" => false, "msg" => "خطأ في تسجيل الدخول", "code" => 1]);
        }
        $foundIt = true;
    }

    if(! $foundIt) { //END
        echo json_encode(["ok" => false, "msg" =>"لم يتم التعرف على الطلب", $_POST]);
    }
} else {
    if (checkLogin()) {

        if (key_exists("logout", $_GET)) {
            logout();
        } else {
            if (checkRole($_SESSION["user_id"], "admin_panel")) {
                $admin_panel = [
                    "analytics_panel"   => "assets/theme/analytics_panel.html",
                    "products_panel"    => "assets/theme/products_panel.html",
                    "settings_panel"    => "assets/theme/settings_panel.php",
                    "clients_panel"     => "assets/theme/clients_panel.html",
                    "orders_panel"       => "assets/theme/orders_panel.html",
                    "salse_panel"       => "assets/theme/sales_panel.php",
                    "users_panel"       => "assets/theme/users_panel.html",
                    "class_panel"       => "assets/theme/class_panel.html",
                    "unit_panel"        => "assets/theme/unit_panel.html",
                    "product"           => "assets/theme/product.html",
                    "client"            => "assets/theme/client.html",
                    "class"             => "assets/theme/class.html",
                    "user"              => "assets/theme/user.html",
                    "unit"              => "assets/theme/unit.html",
                ];

                $requestedPage = @$_GET["p"];
                if (key_exists($requestedPage, $admin_panel)) {
                    $contant_body = $admin_panel[$requestedPage];
                } else {
                    $contant_body = $admin_panel["analytics_panel"];
                }
                if(key_exists("GET", $_GET)){
                    include("assets/theme/admin_panel.php");
                }else{
                    include("assets/theme/direct.html");
                }
            } else {
                include("assets/theme/search.html");
            }
        }
    } else {
        if (key_exists("getitemname", $_GET)) {
            $data = getProductByBarcode($_GET["getitemname"]);
            if ($data != false) {
                echo json_encode(["ok" => true, ["name" => $data["name"], "number" => $data["number"]]]);
            } else {
                echo $data;
            }
        } else {
            include("assets/theme/login.html");
        }
    }
}
