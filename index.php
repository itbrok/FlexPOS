<?php

include_once 'config.php'; // Include the config file to get the default configurations


/*  ----- Check If Flex Is Installed -----  */
if($flex["installed"] == false) {
    if(file_exists("install/install.php")) {
        include_once "install/install.php";
    }else{
        echo "<div>Flex is not Installed</div>";
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
    if (key_exists("barcode", $_POST)) {
        errorMsg();
        $client_id = key_exists("_client_id", $_POST) ? $_POST["_client_id"] : null;
        $data = getProductByBarcode($_POST["barcode"], $client_id);
        if ($data != false) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo $data;
        }
    } elseif (key_exists("getwholesale_price", $_POST)) {
        errorMsg();
        if (getSittings("wholesale_password") == $_POST["getwholesale_price"]["password"]) {
            $data = getProductByBarcode($_POST["getwholesale_price"]["barcode"]);
            if ($data != false) {
                echo json_encode(["ok" => true, $data["wholesale_price"]]);
            } else {
                echo $data;
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "كلمة المرور غير صحيحة"]);
        }
    } elseif (key_exists("defult_product_img", $_POST)) {
        errorMsg();
        $data = getSittings("defult_product_img");
        if ($data != false) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo json_encode(["ok" => false, "msg" => "حدث خطا اثناء تحميل صورة المنتج"]);
        }
    } elseif (key_exists("search", $_POST)) {  //Search
        errorMsg();
        $data = getProductBySearch($_POST["search"]);
        if ($data != false) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo $data;
        }
    } elseif (key_exists("unit", $_POST)) {  //Unit
        errorMsg();
        $data = getUnitByID($_POST["unit"]);
        if ($data != false) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo $data;
        }
    } elseif (key_exists("getclass", $_POST)) {  //Class
        errorMsg();
        $data = getClassByID($_POST["getclass"]);
        if ($data != false) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo $data;
        }
    } elseif (key_exists("canIuseThisOrder", $_POST)) {  //searchOrder
        errorMsg();
        $data = getOrder($_POST["canIuseThisOrder"]);
        if (@$data != false) {
            echo json_encode(["ok" => false]);
        } else {
            echo json_encode(["ok" => true]);
        }
    } elseif (key_exists("getorder", $_POST)) {  //get Order data
        errorMsg();
        $data = getOrder($_POST["getorder"]);
        if (@$data != false) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo json_encode(["ok" => false]);
        }
    } elseif (key_exists("saveOrder", $_POST)) {  //saveOrder
        errorMsg();
        $data = saveOrder($_POST["saveOrder"]);
        if (@$data != false) {
            echo json_encode(["ok" => true]);
        } else {
            echo json_encode(["ok" => false]);
        }
    } elseif (key_exists("getUnFinishedOrders", $_POST)) {  //unfinished Orders
        errorMsg();
        $data = getUnFinishedOrders();
        if (@$data != false) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo json_encode(["ok" => false, "msg" => "لاتوجد فواتير معلقه"]);
        }
    } elseif (key_exists("deleteOrder", $_POST)) {  //Delete Orders
        errorMsg();
        $data = deleteOrder($_POST["deleteOrder"]);
        if (@$data != false) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo json_encode(["ok" => false, "msg" => "حدثت مشكله اثناء محاولة حذف الفاتورة"]);
        }
    } elseif (key_exists("getOrderDetails", $_POST)) {  // Orders Details
        errorMsg();
        $data = getOrderDetails($_POST["getOrderDetails"], 0);
        if (@$data != false) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo json_encode(["ok" => false, "msg" => "لا يوجد مواد متعلقه بهذا الطلب"]);
        }
    } elseif (key_exists("getallOrders", $_POST)) {  //get Orders for admin
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getOrders($_POST["getallOrders"]);
            if (@$data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لا توجد فواتير"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getAllOrdersByDate", $_POST)) {  //get Orders for admin by date range
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getAllOrdersByDate($_POST["getAllOrdersByDate"]);
            if (@$data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لا توجد فواتير"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getAllOrdersByBarcode", $_POST)) {  //get Orders for admin by date range
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getAllOrdersByBarcode($_POST["getAllOrdersByBarcode"]);
            if (@$data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لا توجد فواتير"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getAllOrdersByClientName", $_POST)) {  //get Orders for admin by client name
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getAllOrdersByClientName($_POST["getAllOrdersByClientName"]);
            if (@$data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لا توجد فواتير"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getAllClientsOrders", $_POST)) {  //get Orders for admin by one client id
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getAllClientsOrders($_POST["getAllClientsOrders"]);
            if (@$data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لا توجد فواتير"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getAllClientsItems", $_POST)) {  //get Orders for admin by one client id
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getAllClientsItems($_POST["getAllClientsItems"]);
            if (@$data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لا توجد فواتير"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getOrderDetailsForAdmin", $_POST)) {  // Get Orders Details For Admin
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getOrderDetails($_POST["getOrderDetailsForAdmin"]);
            if (@$data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لا يوجد مواد متعلقه بهذا الطلب"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("newconsumer",$_POST)) {  //newconsumer
        errorMsg();
        $data = addNewConsumer($_POST["name"], @$_POST["phone_number"], @$_POST["address"], @$_POST["notes"]);
        if (@$data["ok"] != false) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo json_encode($data);
        }
    } elseif (key_exists("updateconsumer",$_POST)) {  //update Consumer
        errorMsg();
        $data = updateConsumer($_POST["id"], @$_POST["name"], @$_POST["phone_number"], @$_POST["address"], @$_POST["notes"]);
        if (@$data["ok"] != false) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo json_encode($data);
        }
    } elseif (key_exists("searchconsumer", $_POST)) {  //search consumer
        errorMsg();
        $data = getConsumer($_POST["searchconsumer"]);
        if ($data) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo json_encode(["ok" => false]);
        }
    } elseif (key_exists("searchConsumerWithAddBy", $_POST)) {  //search consumer
        errorMsg();
        $data = getConsumerWithAddBy($_POST["searchConsumerWithAddBy"]);
        if ($data) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo json_encode(["ok" => false]);
        }
    } elseif (key_exists("getconsumer", $_POST)) {  //get one consumer data
        errorMsg();
        $data = getConsumer($_POST["getconsumer"], false, true);
        if ($data) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo json_encode(["ok" => false]);
        }
    } elseif (key_exists("getLastAddedClients", $_POST)) {  //get last inserted clients
        errorMsg();
        $data = getLastAddedClients();
        if ($data) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo json_encode(["ok" => false]);
        }
    } elseif (key_exists("getdebtvalue", $_POST)) {  //get consumer's debt value
        errorMsg();
        $data = getDebtValue($_POST["getdebtvalue"]);
        if ($data) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo json_encode(["ok" => false, "msg" => "هذا العميل لاتوجد عليه ديون سابقه"]);
        }
    } elseif (key_exists("paydebt", $_POST)) {  //pay consumer's debt
        errorMsg();
        $data = payDebt($_POST["paydebt"]["consumer_id"], $_POST["paydebt"]["value"]);
        if ($data) {
            echo json_encode(["ok" => true]);
        } else {
            echo json_encode(["ok" => false]);
        }
    } elseif (key_exists("change_my_password", $_POST)) {  //pay consumer's debt
        errorMsg();
        $data = change_my_pass($_POST["change_my_password"]);
        if ($data) {
            echo json_encode(["ok" => true]);
        } else {
            echo json_encode(["ok" => false]);
        }
    } elseif (key_exists("iqd", $_POST)) {  //iqd
        errorMsg();
        $data = getIQD();
        if ($data != false) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo $data;
        }
    } elseif (key_exists("login", $_POST)) {  //Login
        if (checkLogin()) {
            echo json_encode(["ok" => true, "msg" => "مسجل مسبقا", "code" => 2]);
            exit();
        }
        if (empty(@$_POST['username']) || empty(@$_POST['password'])) {
            echo json_encode(["ok" => false, "msg" => "يجب ادخال اسم المستخدم وكلمة المرور", "code" => 3]);
            exit();
        }
        $resp = getUser(@$_POST['username'], @$_POST['password']);
        if ($resp != false) {
            $_SESSION["user_id"] = $resp["id"];
            $_SESSION['password'] = $resp['password'];
            echo json_encode(["ok" => true]);
        } else {
            echo json_encode(["ok" => false, "msg" => "خطأ في تسجيل الدخول", "code" => 1]);
        }
    } elseif (key_exists("getProductsCount", $_POST)) {
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) { // done
            $data = getProductsCount();
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    }  elseif (key_exists("change_iqd_value", $_POST)) { // CHANGE IQD VALUE
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = setIQD($_POST["change_iqd_value"]);
            if ($data != false) {
                echo json_encode(["ok" => true]);
            } else {
                echo json_encode(["ok" => false, "msg" => "حدث خطا"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getClientsCount", $_POST)) {
        if (checkRole($_SESSION["user_id"], "admin_panel")) { // done
            $data = getClientsCount();
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getClientsList", $_POST)) {  // get client list
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getClients(@$_POST['getClientsList']);
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
        
    } elseif (key_exists("getUsersList", $_POST)) {  // get users list
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getUsers(@$_POST['getUsersList']);
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
        
    } elseif (key_exists("getproductsList", $_POST)) {  // get products List
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getProductsList(@$_POST['getproductsList']);
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
        
    } elseif (key_exists("getUserData", $_POST)) {  // get one user data
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getUserByID(@$_POST['getUserData']);
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
        
    } elseif (key_exists("newUser", $_POST)) {  // add new user
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = addNewUser(@$_POST['username'], @$_POST['password'], @$_POST['name'], @$_POST['printer_id'], @$_POST['isAdmin']);
            if ($data != false) {
                echo json_encode($data);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
        
    } elseif (key_exists("updateUser", $_POST)) {  // update user data
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = updateUserData(@$_POST['username'], @$_POST['password'], @$_POST['name'], @$_POST['id'], @$_POST['printer_id'], @$_POST['isAdmin']);
            if ($data != false) {
                echo json_encode($data);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getReceiptsCount", $_POST)) {
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getReceiptsCount();
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getUsersCount", $_POST)) {
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getUsersCount();
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getProductByID", $_POST)) { // get Product By ID
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getProductByID($_POST['getProductByID']);
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("addNewProduct", $_POST)) {  // add new product
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = addNewProduct($_POST);
            if ($data != false) {
                echo json_encode($data);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("updateproduct", $_POST)) {  // update product
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = updateProduct($_POST);
            if ($data != false) {
                echo json_encode($data);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("deleteItem", $_POST)) {  // delete items
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = deleteItem($_POST['deleteItem'], @$_POST['id']);
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("addNewUnit", $_POST)) {  // add New unit
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = addNewUnit($_POST['type']);
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("addNewClass", $_POST)) {  // add New class
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = addNewClass($_POST['type']);
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("updateclass", $_POST)) {  // update class
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = updateClass($_POST['type'], $_POST['id']);
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("updateunit", $_POST)) {  // update unit
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = updateUnit($_POST['type'], $_POST['id']);
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("update_defult_product_img", $_POST)) {  // update unit
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = updateSetting("defult_product_img", $_POST['update_defult_product_img']);
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("change_wholesale_password", $_POST)) {  // update unit
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = updateSetting("wholesale_password", $_POST['change_wholesale_password']);
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getunitsList", $_POST)) {  // get unit list
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getUnitsList();
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    }  elseif (key_exists("getprintersList", $_POST)) {  // get printers list
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getPrintersList();
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    }   elseif (key_exists("updateprinters", $_POST)) {  // get printers list
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            if(emptyTable("printers")){
                $data = addAvailablePrintersToDB();
            }else{
                $data = false;
            }
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getclassList", $_POST)) {  // get class list
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getClassesList();
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getnotifications", $_POST)) {  // get notifications list (log class)
        errorMsg();
        if (checkRole($_SESSION["user_id"], "admin_panel")) {
            $data = getNotifications($_POST['getnotifications']);
            if ($data != false) {
                echo json_encode(["ok" => true, $data]);
            } else {
                echo json_encode(["ok" => false, "msg" => "لاتوجد بيانات"]);
            }
        } else {
            echo json_encode(["ok" => false, "msg" => "غير مصرح"]);
        }
    } elseif (key_exists("getUnitesAndClass", $_POST)) {  // get Unites And Classes As Json
        errorMsg();
        $data = getUnitesAndClass();
        if ($data != false) {
            echo json_encode(["ok" => true, $data]);
        } else {
            echo json_encode(["ok" => false, "msg" => "حدث خطأ"]);
        }
    } else { //END
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
                    "salse_panel"       => "assets/theme/sales_panel.html",
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
                include("assets/theme/admin_panel.php");
            } else {
                include("assets/theme/search.html");
            }
        }
    } else {
        if (key_exists("getitemname", $_GET)) {
            $data = getProductByBarcode($_GET["getitemname"]);
            if ($data != false) {
                echo json_encode(["ok" => true, $data["name"]]);
            } else {
                echo $data;
            }
        } else {
            include("assets/theme/login.html");
        }
    }
}
