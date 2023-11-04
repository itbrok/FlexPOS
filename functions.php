<?php
require_once 'config.php';
session_start();
header("Access-Control-Allow-Origin: *");




function logout() {
    session_destroy();
    header("Location:index.php");
    exit();
}

 
function keys_exists($keys, array $array){
	if(is_array($keys)){
    	$keysFound = 0;
    	foreach($keys as $i => $key){
        	if(key_exists($key, $array)){
            	$keysFound++;
            }
        }
        return $keysFound == count($keys);
    }else{
    	return key_exists($keys, $array);
    }
}
 

function checkLogin($logout = false) {
    if (!isset($_SESSION["user_id"])) {
        if ($logout) {
            logout();
        }
        return false;
    }
    return true;
}

function getProductByBarcode($barcode, $client = null) {
    $conn = conn();
    if ($client == null) {
        $stmt = $conn->prepare("SELECT * FROM product WHERE barcode REGEXP ?");
        $stmt->bind_param("s", $barcode);
    } else {
        $stmt = $conn->prepare("SELECT `product`.`id`, oldP.`prodect_barcode` AS `barcode`, `number`, `name`, `product`.`quantity`, `buy_price`, oldP.`sold_price` AS `sell_price`, `wholesale_price`, `class_id`, `unit_id`, `warehouse_name`, `corridor`, `shelf`, `box`, `note`, `img` FROM product JOIN (SELECT * FROM order_detail WHERE order_detail.order_id IN (SELECT orders.order_id FROM `orders` WHERE consumer_id = ?)) AS oldP ON oldP.product_id = product.id WHERE product.barcode REGEXP ? ORDER BY oldP.`date` DESC LIMIT 1");
        $stmt->bind_param("is", $client, $barcode);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (is_array(json_decode($row["barcode"], true))) {
                $row["barcode"] = json_decode($row["barcode"], true)[0];
            }
            return $row;
        }
    }
    if ($client != null) {
        return getProductByBarcode($barcode, null);
    }
    return false;
}

function getProductByID($id) {
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row;
        }
    }
    return false;
}

function getUnitesAndClass() {
    $u = getUnitsList();
    $c = getClassesList();
    $resp["unit"] = $u;
    $resp["class"] = $c;
    return $resp;
}

function getProductBySearch($barcode, $limit) {
    $conn = conn();
    $likeVal = str_replace(" ", "%", "%" . $barcode . "%");
    $stmt = $conn->prepare("SELECT p.* FROM (SELECT x.resCount, product.* FROM product, (select count(*) as resCount FROM product) AS x WHERE barcode REGEXP ? or `name` LIKE ? or `number` LIKE ? order by trim(? from name)) As p LIMIT ?, 50");
    $stmt->bind_param("ssssi", $barcode, $likeVal, $likeVal, $barcode, $limit);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (is_array(json_decode($row["barcode"], true))) {
                    $row["barcode"] = json_decode($row["barcode"], true)[0];
                }

                $rows[] = $row;
            }
            return $rows;
        }
    } else {
        return false;
    }
    return false;
}

function getUnitByID($id) {
    $conn = conn();
    $stmt = $conn->prepare("SELECT `type` FROM unit WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row["type"];
        }
    }
    return false;
}

function getMoneyPaid($clientId) {
    $conn = conn();
    $stmt = $conn->prepare("SELECT SUM(money_paid) AS `MoneyPaid` FROM debt WHERE consumer_id = ?");
    $stmt->bind_param("i", $clientId);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row["MoneyPaid"];
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function getPriceLeft($consumer_id) {
    $conn = conn();
    $stmt = $conn->prepare("SELECT SUM(price_left) AS `PriceLeft` FROM orders WHERE consumer_id = ?");
    $stmt->bind_param("i", $consumer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row["PriceLeft"];
        }
    }
    return false;
}


function getPricePaid($consumer_id) {
    $conn = conn();
    $stmt = $conn->prepare("SELECT SUM(price_paid) AS `PricePaid` FROM orders WHERE consumer_id = ?");
    $stmt->bind_param("i", $consumer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row["PricePaid"];
        }
    }
    return false;
}

function getDebtValue($clientId) {
    $moneyPaid = getMoneyPaid($clientId);
    if ($moneyPaid == false) {
        $moneyPaid = 0;
    }
    $priceLeft = getPriceLeft($clientId);
    if ($priceLeft == false) {
        $priceLeft = 0;
    }
    return $moneyPaid - $priceLeft;
}

function payDebt($consumer_id, $val) {
    try {
        $conn = conn();
        $stmt = $conn->prepare("INSERT INTO `debt` (`employee_id`, `consumer_id`, `money_paid`) VALUES (?, ?, ?)");
        $stmt->bind_param("iid", $_SESSION["user_id"], $consumer_id, $val);
        $stmt->execute();
        return true;
    } catch (\Throwable $th) {

        return false;
    }
}

function getClassByID($id) {
    $conn = conn();
    $stmt = $conn->prepare("SELECT `type` FROM class WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row["type"];
        }
    }
    return false;
}

function getSittings($var) {
    $conn = conn();
    $stmt = $conn->prepare("SELECT `value` FROM `sittings` WHERE variable = ?");
    $stmt->bind_param("s", $var);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row["value"];
        }
    }
    return false;
}
function getIQD($iqd = "iq") {
    $conn = conn();
    $stmt = $conn->prepare("SELECT `val` FROM currency WHERE country = ?");
    $stmt->bind_param("s", $iqd);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row;
        }
    }
    return false;
}

function setIQD($val, $country = "iq") {
    $conn = conn();
    $stmt = $conn->prepare("UPDATE currency SET `val` = ? WHERE country = ?");
    $stmt->bind_param("ss", $val, $country);
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

function getProductsCount() {
    $conn = conn();
    $stmt = $conn->prepare("SELECT COUNT(id) AS `ProductsCount` FROM `product`");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row["ProductsCount"] ?? 0;
        }
    }
    return false;
}

function getClientsCount() {
    $conn = conn();
    $stmt = $conn->prepare("SELECT COUNT(*) AS `ClientsCount` FROM `consumer`");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row["ClientsCount"] - 1 ?? 0;
        }
    }
    return false;
}

function getReceiptsCount() {
    $conn = conn();
    $stmt = $conn->prepare("SELECT COUNT(order_id) AS `ReceiptsCount` FROM `orders`");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row["ReceiptsCount"];
        }
    }
    return false;
}

function getUsersCount() {
    $conn = conn();
    $stmt = $conn->prepare("SELECT COUNT(id) AS `UsersCount` FROM `users`");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row["UsersCount"];
        }
    }
    return false;
}

function getUser($username, $password) {
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM users WHERE `username` = ? AND `password` = ? LIMIT 1");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row;
        }
    }
    return false;
}

function getUserByID($id) {
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM users WHERE `id` = ? LIMIT 1");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row["isAdmin"] = checkRole($id, "admin_panel");
            return $row;
        }
    }
    return false;
}

function getUserByUserName($un) {
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM users WHERE `username` = ? LIMIT 1");
    $stmt->bind_param("s", $un);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row;
        }
    }
    return false;
}

function getUsers($limit) {
    try {
        $limit = ($limit - 1);
        $conn = conn();
        $stmt = $conn->prepare("SELECT x.resCount, c.id, c.name, c.username, c.date, u.name as addby, u.id as addbyid FROM users AS c , (select count(*) as resCount FROM users) AS x, users AS u WHERE c.added_by=u.id AND c.id != ? LIMIT ?, 10;");
        $stmt->bind_param("ii", $_SESSION["user_id"], $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }
    } catch (\Throwable $th) {
        return false;
    }
}

function getproductsList($limit) {
    try {
        $limit = ($limit - 1);
        $conn = conn();
        $stmt = $conn->prepare("SELECT x.resCount, p.* FROM product AS p , (select count(*) as resCount FROM product) AS x LIMIT ?, 50");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }
    } catch (\Throwable $th) {
        return false;
    }
}

function getNotifications($limit) {
    try {
        $limit = ($limit - 1);
        $conn = conn();
        $stmt = $conn->prepare(
            "SELECT
            o.order_id,
            o.items_count,
            u.name AS userName,
            c.name AS clientName,
            o.total_price
            FROM `orders` AS o
            JOIN `users` AS u
            JOIN consumer AS c
            ON c.id = o.consumer_id
            AND u.id = o.employee_id
            WHERE o.date BETWEEN date_sub(now(),INTERVAL 1 DAY) and now()
            AND o.isitfinished = 1
            ORDER BY o.date ASC
            LIMIT ?, 40"
        );
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }
    } catch (\Throwable $th) {
        return false;
    }
}

function checkRole($id, $role) {
    $conn = conn();
    $stmt = $conn->prepare("SELECT `role_value` FROM `role` WHERE `user_id` = ? AND `role_name` = ? LIMIT 1");
    $stmt->bind_param("ss", $id, $role);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row["role_value"] == 1;
            }
        }
    } else {
        return false;
    }
    return false;
}

function addRole($role_name, $role_value, $id) {
    $conn = conn();
    $stmt = $conn->prepare("INSERT INTO `role` (`role_name`, `role_value`, `user_id`) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $role_name, $role_value, $id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
    return false;
}

function updateRole($role_name, $role_value, $id) {
    $conn = conn();
    $stmt = $conn->prepare("UPDATE `role` SET `role_value` = ? WHERE `user_id` = ? AND `role_name` = ?");
    $stmt->bind_param("sss", $role_value, $id, $role_name);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
    return false;
}

function getOrder($orderID) {
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM orders WHERE `order_id` = ? LIMIT 1");
    $stmt->bind_param("s", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row;
        }
    }
    return false;
}

function getUnFinishedOrders() {
    try {
        $conn = conn();
        $stmt = $conn->prepare("SELECT * FROM orders WHERE `isitfinished` = 0");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }
    } catch (\Throwable $th) {
        return false;
    }
}

function getClients($limit) {
    try {
        $limit = ($limit - 1);
        $conn = conn();
        $stmt = $conn->prepare("SELECT x.resCount, c.id, c.name, c.address, c.date, u.name as addby, u.id as addbyid FROM consumer AS c , (select count(*) -1 as resCount FROM consumer) AS x, users AS u WHERE c.added_by=u.id LIMIT ?, 10");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $moneyPaid = getMoneyPaid($row['id']);
                if ($moneyPaid == false) {
                    $moneyPaid = 0.000;
                }
                $priceLeft = getPriceLeft($row['id']);
                if ($priceLeft == false) {
                    $priceLeft = 0.000;
                }
                $pricePaid = getPricePaid($row['id']);
                if ($pricePaid == false) {
                    $pricePaid = 0.000;
                }
                $row['MoneyPaid'] = $moneyPaid + $pricePaid;
                $row['MoneyLeft'] = $priceLeft;
                $row['isRed'] = ($priceLeft > 0) ? 1 : 0;
                $rows[] = $row;
            }
            return $rows;
        }
    } catch (\Throwable $th) {
        return false;
    }
}


function getOrders($limit) {
    try {
        $limit = ($limit - 1);
        $conn = conn();
        $stmt = $conn->prepare("SELECT x.resCount, o.* FROM orders AS o , (select count(*) as resCount FROM orders) AS x ORDER BY date DESC LIMIT ?, 20");
        $stmt->bind_param("i", $limit);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                return $rows;
            }
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        return false;
    }
}

function getAllOrdersByDate($timeRang) {
    try {
        if(keys_exists(["from", "to"], $timeRang) == false){
            return false;
        }
        $conn = conn();
        $stmt = $conn->prepare("SELECT * FROM orders WHERE date >= ? AND date <= ? order by date desc");
        $stmt->bind_param("ss", $timeRang["from"], $timeRang["to"]);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                return $rows;
            }
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        return false;
    }
}

function getAllOrdersByBarcode($barcode) {
    try {
        $conn = conn();
        $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id IN (SELECT order_id FROM `order_detail` WHERE prodect_barcode = ? GROUP BY order_id) order by date desc");
        $stmt->bind_param("s", $barcode);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                return $rows;
            }
        } else {
            return false;
        }
        return false;
    } catch (\Throwable $th) {
        return false;
    }
}

function getAllOrdersByClientName($name) {
    try {
        $conn = conn();
        $stmt = $conn->prepare("SELECT * FROM orders WHERE consumer_id IN (SELECT `id` FROM `consumer` WHERE `name` REGEXP ?) ORDER BY date DESC");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                return $rows;
            }
        } else {
            return false;
        }
        return false;
    } catch (\Throwable $th) {
        return false;
    }
}

function getAllClientsItems($id) {
    try {
        $conn = conn();
        $stmt = $conn->prepare("SELECT product.name, order_detail.*, orders.price_left, orders.isitfinished FROM `order_detail` JOIN product JOIN orders ON product.id = order_detail.product_id AND orders.order_id = order_detail.order_id WHERE order_detail.order_id IN (SELECT order_id FROM orders WHERE consumer_id = ?) ORDER BY order_detail.date DESC");
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                return $rows;
            }
        } else {
            return false;
        }
        return false;
    } catch (\Throwable $th) {
        return false;
    }
}

function getAllClientsOrders($id) {
    try {
        $conn = conn();
        $stmt = $conn->prepare("SELECT * FROM `orders` WHERE consumer_id = ? ORDER BY date DESC");
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                $rows["left"] = getDebtValue($id);
                return $rows;
            }
        } else {
            return false;
        }
        return false;
    } catch (\Throwable $th) {
        return false;
    }
}

function getOrderDetails($order_id) {
    try {
        $conn = conn();
        $stmt = $conn->prepare("SELECT * FROM order_detail WHERE `order_id` = ?");
        $stmt->bind_param("s", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }
    } catch (\Throwable $th) {
        return false;
    }
}

function getLastAddedClients() {
    try {
        $conn = conn();
        $stmt = $conn->prepare("SELECT c.id, c.name, c.address, c.date, u.name as addby, u.id as addbyid FROM consumer AS c LEFT JOIN users AS u ON c.added_by=u.id WHERE c.date BETWEEN date_sub(now(),INTERVAL 1 WEEK) and now() LIMIT 6");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }
    } catch (\Throwable $th) {
        return false;
    }
}

function getConsumer($q, $equal = false, $useid = false) {
    $conn = conn();
    if ($equal) {
        $query = "SELECT * FROM consumer WHERE `name` = ? OR `phone_number` = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $q, $q);
    } elseif ($useid) {
        $query = "SELECT * FROM consumer WHERE `id` = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $q);
    } else {
        $query = "SELECT * FROM consumer WHERE `name` LIKE ? OR `phone_number` LIKE ?";
        $q = str_replace(" ", "%", "%" . $q . "%");
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $q, $q);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
    return false;
}

function getConsumerWithAddBy($q) {
    $conn = conn();

    $query = "SELECT c.id, c.name, c.address, c.date, u.name as addby, u.id as addbyid FROM consumer AS c LEFT JOIN users AS u ON c.added_by=u.id WHERE c.name LIKE ? OR c.phone_number LIKE ?";
    $q = str_replace(" ", "%", "%" . $q . "%");
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $q, $q);


    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
    return false;
}

function isPhoneNumber($phone_number) {
    $re = '/[0]{1}[7]{1}[0-9]{9}/m';
    if (preg_match($re, $phone_number)) {
        return true;
    } else {
        return false;
    }
}

function addNewProduct($product) {
    try {
        if (empty(@$product["barcodes"]) || empty(@$product["name"])) {
            return ["ok" => false, "msg" => "يجب ملء كافة الحقول", "code" => 5, "oo"=>$product];
        } else {
            if (str_contains($product["barcodes"], "\r\n")) {
                $barcode = explode("\r\n", $product["barcodes"]);
                if (is_array($barcode)) {
                    if ($barcode[count($barcode) - 1] == "\r\n") {
                        unset($barcode[count($barcode) - 1]);
                    }
                    $check_exist = getProductByBarcode($barcode[0]);
                }
                $barcodes = json_encode($barcode);
            } else {
                $barcodes = $product["barcodes"];
                $check_exist = getProductByBarcode($barcodes);
            }
            // check if exist
            if ($check_exist) {
                return ["ok" => false, "msg" => "موجود مسبقاً", "code" => 6];
            }

            $conn = conn();
            $stmt = $conn->prepare("INSERT INTO `product` (`barcode`, `number`, `name`, `quantity`, `buy_price`, `sell_price`, `wholesale_price`, `class_id`, `unit_id`, `warehouse_name`, `corridor`, `shelf`, `box`, `note`, `img`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssssssssssss", $barcodes, $product["number"], $product["name"], $product["quantity"], $product["buy_price"], $product["sell_price"], $product["wholesale_price"], $product["class_id"], $product["unit_id"], $product["warehouse_name"], $product["corridor"], $product["shelf"], $product["box"], $product["note"], $product["img"]);
            $stmt->execute();
            return ["ok" => true];
        }
    } catch (\Throwable $th) {
        return false;
    }
}

function updateProduct($product) {
    try {
        if (empty(@$product["barcodes"]) || empty(@$product["name"])) {
            return ["ok" => false, "msg" => "يجب ملء كافة الحقول", "code" => 5];
        } else {
            if (str_contains($product["barcodes"], "\r\n")) {
                $barcode = explode("\r\n", $product["barcodes"]);
                if (is_array($barcode)) {
                    if ($barcode[count($barcode) - 1] == "\r\n") {
                        unset($barcode[count($barcode) - 1]);
                    }
                    $check_exist = getProductByBarcode($barcode[0]);
                }
                $barcodes = json_encode($barcode);
            } else {
                $barcodes = $product["barcodes"];
                $check_exist = getProductByBarcode($barcodes);
            }
            // check if exist
            if (!$check_exist) {
                return ["ok" => false, "msg" => "لا يمكن تحديث منتج غير موجود بقاعدة البيانات"];
            }

            $conn = conn();
            $stmt = $conn->prepare("UPDATE `product` SET `barcode` = ?, `number` = ?, `name` = ?, `quantity` = ?, `buy_price` = ?, `sell_price` = ?, `wholesale_price` = ?, `class_id` = ?, `unit_id` = ?, `warehouse_name` = ?, `corridor` = ?, `shelf` = ?, `box` = ?, `note` = ?, `img` = ? WHERE id = ?");
            $stmt->bind_param("ssssssssssssssss", $barcodes, $product["number"], $product["name"], $product["quantity"], $product["buy_price"], $product["sell_price"], $product["wholesale_price"], $product["class_id"], $product["unit_id"], $product["warehouse_name"], $product["corridor"], $product["shelf"], $product["box"], $product["note"], $product["img"], $product["id"]);
            if ($stmt->execute()) {
                return ["ok" => true];
            } else {
                return ["ok" => false, "msg" => "خطأ داخلي"];
            }
        }
    } catch (\Throwable $th) {
        return false;
    }
}

function addNewUser($username, $password, $name, $printer_id, $isAdmin) {
    try {
        if (empty($name) || empty($username) || empty($password)) {
            return ["ok" => false, "msg" => "يجب ملء كافة الحقول", "code" => 5];
        } else {
            // check if exist
            if (getUserByUserName($username)) {
                return ["ok" => false, "msg" => "موجود مسبقاً", "code" => 6];
            }
            $conn = conn();
            $stmt = $conn->prepare("INSERT INTO `users` (`name`, `username`, `password`, `added_by`, `printer_id`) VALUES (?,?,?,?,?)");
            $stmt->bind_param("ssssi", $name, $username, $password, $_SESSION["user_id"], $printer_id);
            if ($stmt->execute()) {
                $role = addRole("admin_panel", (($isAdmin) ? 1 : 0), getUserByUserName($username)["id"]);
                if ($role) {
                    return ["ok" => true];
                }
            }
            return ["ok" => false, "msg" => "خطأ داخلي"];
        }
    } catch (\Throwable $th) {
        return false;
    }
}

function updateUserData($username, $password, $name, $id, $printer_id, $isAdmin) {
    try {
        if (empty($name) || empty($username) || empty($password) || empty($id)) {
            return ["ok" => false, "msg" => "يجب ملء كافة الحقول", "code" => 5];
        } else {
            // check if exist
            if (!getUserByID($id)) {
                return ["ok" => false, "msg" => "لايمكن تحديث معلومات هذا الموظف"];
            }
            $role = updateRole("admin_panel", (($isAdmin == 'on') ? 1 : 0), $id);
            $conn = conn();
            $stmt = $conn->prepare("UPDATE `users` SET `name` = ?, `username` = ?, `password` = ?, `printer_id` = ? WHERE id = ?");
            $stmt->bind_param("sssis", $name, $username, $password, $printer_id, $id);
            if ($stmt->execute() && $role) {
                return ["ok" => true];
            } else {
                return ["ok" => false, "msg" => "خطأ داخلي"];
            }
        }
    } catch (\Throwable $th) {
        return false;
    }
}

function addNewConsumer($name, $phone_number, $address, $notes) {
    try {
        if (empty($name) || empty($phone_number) || empty($address)) {
            return ["ok" => false, "msg" => "يجب ملء كافة الحقول", "code" => 5];
        } else {
            if (!isPhoneNumber($phone_number)) {
                return ["ok" => false, "msg" => "ادخل رقم هاتف صحيح", "code" => 5];
            }
            // check if exist
            if (getConsumer($name, true) || getConsumer($phone_number, true)) {
                return ["ok" => false, "msg" => "العميل موجود مسبقاً", "code" => 6];
            }
            $conn = conn();
            $stmt = $conn->prepare("INSERT INTO `consumer` (`name`, `phone_number`, `address`, `notes`, `added_by`) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss", $name, $phone_number, $address, $notes, $_SESSION["user_id"]);
            if ($stmt->execute()) {
                return getConsumer($phone_number, true);
            } else {
                return ["ok" => false, "msg" => "حدث خطا من السيرفر رقم الخطا 3485"];
            }
        }
    } catch (\Throwable $th) {
        return false;
    }
}

function updateConsumer($id, $name, $phone_number, $address, $notes) {
    try {
        if (empty($name) || empty($phone_number) || empty($address)) {
            return ["ok" => false, "msg" => "يجب ملء كافة الحقول", "code" => 5];
        } else {
            if (!isPhoneNumber($phone_number)) {
                return ["ok" => false, "msg" => "ادخل رقم هاتف صحيح", "code" => 5];
            }
            $conn = conn();
            $stmt = $conn->prepare("UPDATE `consumer` SET `name` = ?, `phone_number` = ?, `address` = ?, `notes` = ? WHERE id = ?");
            $stmt->bind_param("sssss", $name, $phone_number, $address, $notes, $id);
            $stmt->execute();
            return ["ok" => true];
        }
    } catch (\Throwable $th) {
        return false;
    }
}



function deleteOrder($order_id) {
    $conn = conn();
    $stmt = $conn->prepare("DELETE FROM `orders` WHERE `order_id` = ?");
    $stmt->bind_param("i", $order_id);
    if ($stmt->execute()) {
        $stmt->close();
        $stmt = $conn->prepare("DELETE FROM `order_detail` WHERE `order_id` = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
        return true;
    }
    return false;
}
function saveOrder($orderDetails) {

    try {
        $conn = conn();
        if (@$orderDetails["update"] === "true") {
            deleteOrder($orderDetails["order_id"]);
        }

        $stmt = $conn->prepare("INSERT INTO `orders` (`order_id`, `employee_id`, `consumer_id`, `items_count`, `discount`, `price_paid`, `price_left`, `isitfinished`, `total_price`) VALUES (?,?,?,?,?,?,?,?,?);");
        $stmt->bind_param("iiiiiddid", $orderDetails["order_id"], $_SESSION["user_id"], $orderDetails["consumer_id"], $orderDetails["items_count"], $orderDetails["discount"], $orderDetails["price_paid"], $orderDetails["price_left"], $orderDetails["isitfinished"], $orderDetails["total_price"]);
        $stmt->execute();
        $stmt->close();
        $items = $orderDetails["items"];
        foreach ($items as $key => $value) {
            $stmt = $conn->prepare("INSERT INTO `order_detail` (`order_id`, `product_id`, `prodect_barcode`, `quantity`, `sold_price`) VALUES (?,?,?,?,?)");
            $stmt->bind_param("iiiid", $orderDetails["order_id"], $value[3], $value[0], $value[1], $value[2]);
            $stmt->execute();
            $stmt->close();
            $stmt = $conn->prepare("UPDATE `product` SET `quantity` = `quantity` - ? WHERE `id` = ?");
            $stmt->bind_param("ii", $value[1], $value[3]);
            $stmt->execute();
            $stmt->close();
        }

        return true;
    } catch (\Throwable $th) {
        return false;
    }
}

/* ---------- Delete Items ---------- */


function deleteItem($type, $id) {
    $typesArr = [
        "product" => "product",
        "client" => "consumer",
        "user" => "users",
        "unit" => "unit",
        "class" => "class",
        "printer" => "printers",
    ];
    if (key_exists($type, $typesArr)) {
        $conn = conn();
        $stmt = $conn->prepare("DELETE FROM $typesArr[$type] WHERE `id` = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return "تم الحذف بنجاح";
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/* ---------- Delete Items ---------- */

/* ---------- Units And Classes ---------- */

#ADD NEW
function addNewUnit($type) {
    $conn = conn();
    $stmt = $conn->prepare("INSERT INTO `unit` (`type`) VALUES (?)");
    $stmt->bind_param("s", $type);
    if ($stmt->execute()) {
        return "تم اضافة الوحدة '$type' بنجاح!";
    } else {
        return false;
    }
}

function addNewClass($type) {
    $conn = conn();
    $stmt = $conn->prepare("INSERT INTO `class` (`type`) VALUES (?)");
    $stmt->bind_param("s", $type);
    if ($stmt->execute()) {
        return "تم اضافة النوع '$type' بنجاح!";
    } else {
        return false;
    }
}

# GET LIST
function getUnitsList() {
    $conn = conn();
    $stmt = $conn->prepare("SELECT u.id AS unitID, u.type AS unitType FROM unit AS u");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
    return false;
}

function getClassesList() {
    $conn = conn();
    $stmt = $conn->prepare("SELECT c.id AS classID, c.type AS classType FROM class AS c");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
    return false;
}

#UPDATE
function updateUnit($type, $id) {
    if (empty($type) || empty($id)) {
        return ["ok" => false, "msg" => "يجب ملء كافة الحقول", "code" => 5];
    } else {
        if (!getUnitByID($id)) {
            return ["ok" => false, "msg" => "لايمكن تحديث هذا العنصر"];
        }
        $conn = conn();
        $stmt = $conn->prepare("UPDATE `unit` SET `type` = ? WHERE id = ?");
        $stmt->bind_param("ss", $type, $id);
        if ($stmt->execute()) {
            return ["ok" => true];
        } else {
            return false;
        }
    }
}

function updateClass($type, $id) {
    if (empty($type) || empty($id)) {
        return ["ok" => false, "msg" => "يجب ملء كافة الحقول", "code" => 5];
    } else {
        if (!getClassByID($id)) {
            return ["ok" => false, "msg" => "لايمكن تحديث هذا العنصر"];
        }
        $conn = conn();
        $stmt = $conn->prepare("UPDATE `class` SET `type` = ? WHERE id = ?");
        $stmt->bind_param("ss", $type, $id);
        if ($stmt->execute()) {
            return ["ok" => true];
        } else {
            return false;
        }
    }
}

/* ---------- Units And Classes ---------- */



function updateSetting($var, $val) {
    $conn = conn();
    $stmt = $conn->prepare("UPDATE `sittings` SET `value` = ? WHERE `sittings`.`variable` = ?");
    $stmt->bind_param("ss", $val, $var);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function change_my_pass($newPass) {
    $conn = conn();
    $stmt = $conn->prepare("UPDATE `users` SET `password` = ? WHERE `id` = ?");
    $stmt->bind_param("si", $newPass, $_SESSION["user_id"]);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}



/* ---------- Printers ---------- */
function getPrinterProperty($key) {
    $str = shell_exec('wmic printer get ' . $key . ' /value');
    $keys = explode(', ', $key);
    $validValues = [];
    $fragments = explode(PHP_EOL, $str);
    foreach ($fragments as $fragment) {
        if ($fragment == "") {
            continue;
        }
        foreach ($keys as $keyname) {
            if (preg_match('/(' . $keyname . "=" . ')/i', $fragment)) {
                $validValues[$keyname][] = str_replace($keyname . "=", "", $fragment);
            }
        }
    }
    return $validValues;
}

function addAvailablePrintersToDB() {
    $resp = getPrinterProperty("Name");
    $result = "('" . implode("'), ('", $resp['Name']) . "')";

    $conn = conn();
    $stmt = $conn->prepare("INSERT INTO `printers` (`PrinterName`) VALUES" . $result);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


/* ---------- Back up ---------- */

function getBackUp() {
    // Get connection object and set the charset
    $conn = conn();
    $conn->set_charset("utf8");


    // Get All Table Names From the Database
    $tables = array();
    $sql = "SHOW TABLES";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }

    $sqlScript = "";
    foreach ($tables as $table) {

        // // Prepare SQLscript for creating table structure
        // $query = "SHOW CREATE TABLE $table";
        // $result = mysqli_query($conn, $query);
        // $row = mysqli_fetch_row($result);

        // $sqlScript .= "\n\n" . $row[1] . ";\n\n";


        $query = "SELECT * FROM $table";
        $result = mysqli_query($conn, $query);

        $columnCount = mysqli_num_fields($result);

        // Prepare SQLscript for dumping data for each table
        for ($i = 0; $i < $columnCount; $i++) {
            while ($row = mysqli_fetch_row($result)) {
                $sqlScript .= "INSERT IGNORE INTO $table VALUES(";
                for ($j = 0; $j < $columnCount; $j++) {
                    $row[$j] = $row[$j];
                    $row[$j] = str_replace('"', "'", $row[$j]);
                    if (isset($row[$j])) {
                        $sqlScript .= '"' . $row[$j] . '"';
                    } else {
                        $sqlScript .= '""';
                    }
                    if ($j < ($columnCount - 1)) {
                        $sqlScript .= ',';
                    }
                }
                $sqlScript .= ");\n";
            }
        }

        $sqlScript .= "\n";
    }


    // Save It As File
    // if(!empty($sqlScript))
    // {
    //     // Save the SQL script to a backup file
    //     $backup_file_name = $database_name . '_backup_' . time() . '.sql';
    //     $fileHandler = fopen($backup_file_name, 'w+');
    //     $number_of_lines = fwrite($fileHandler, $sqlScript);
    //     fclose($fileHandler); 

    //     // Download the SQL backup file to the browser
    //     header('Content-Description: File Transfer');
    //     header('Content-Type: application/octet-stream');
    //     header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
    //     header('Content-Transfer-Encoding: binary');
    //     header('Expires: 0');
    //     header('Cache-Control: must-revalidate');
    //     header('Pragma: public');
    //     header('Content-Length: ' . filesize($backup_file_name));
    //     ob_clean();
    //     flush();
    //     readfile($backup_file_name);
    //     exec('rm ' . $backup_file_name); 
    // }
    return $sqlScript;
}


/* ---------- Restore ---------- */

function restoreMysqlDB($filePath, $conn) {
    $sql = '';
    $error = '';

    if (file_exists($filePath)) {
        $lines = file($filePath);

        foreach ($lines as $line) {

            // Ignoring comments from the SQL script
            if (substr($line, 0, 2) == '--' || $line == '') {
                continue;
            }

            $sql .= $line;

            if (substr(trim($line), -1, 1) == ';') {
                $result = mysqli_query($conn, $sql);
                if (!$result) {
                    $error .= mysqli_error($conn) . "\n";
                }
                $sql = '';
            }
        } // end foreach

        if ($error) {
            trigger_error($error);
            return false;
        } else {
            return true;
        }
    }
}





# PRINTERS GET LIST
function getPrintersList() {
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM printers");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
    return false;
}


# EMPTY TABLE
function emptyTable($tableName) {
    $conn = conn();
    $stmt = $conn->prepare("TRUNCATE `$tableName`");
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

// Find nearset number for IQD
function iqdRound($n) {
    $iqdLine = [0, 250, 500, 750, 1000];

    if (strlen($n) >= 3) {
        $newVal = substr($n,-3);
        if ($newVal > $iqdLine[3]) {
            return ($n - $newVal) + $iqdLine[4];
        } else if ($newVal > $iqdLine[2]) {
            return ($n - $newVal) + $iqdLine[3];
        } else if ($newVal > $iqdLine[1]) {
            return ($n - $newVal) + $iqdLine[2];
        } else if ($newVal > $iqdLine[0]) {
            return ($n - $newVal) + $iqdLine[1];
        }
    }
    return $n;
}

class PrintOrder {
    private array $items;
    private $templatepath = "print/template/printtemplate.txt";
    private array $paper;
    private array $basic;
    private int $currentPage;
    private int $allPages;

    function __construct($basic, $items, $paper, $currentPage, $allPages){
        $this->items = $items;
        $this->paper = $paper;
        $this->basic = $basic;
        $this->currentPage = $currentPage;
        $this->allPages = $allPages;
    }
    public function getRegxResult($regexp, $str){
        preg_match_all($regexp, $str, $matches, PREG_SET_ORDER, 0);
        return $matches;
    }

    public function getHTML(){
        $items = $this->items;
        $paper = $this->paper;
        $basic = $this->basic;
        $currentPage = $this->currentPage;
        $allPages = $this->allPages;
        

        $htmlTemplate = str_replace("\n", "", file_get_contents($this->templatepath));
        
        $templateElements = ["msg", "alone_number", "client", "user", "date", "id", "itemname", "qty", "pricexqty", "priceiqd", "inventory", "sub_total", "discount", "total_price", "papernumber", "price", "logoimg"];
        foreach($templateElements as $el){
            $regexp = "/#$el.*?#$el/m";
            $elCodes = array_column($this->getRegxResult($regexp, $htmlTemplate),0);
            if($paper[$el] != "true"){
                //$htmlTemplate = str_replace($elCodes, "", $htmlTemplate);
            }else{
                $htmlTemplate = str_replace("#$el", "", $htmlTemplate);
            }
        }



        $basic["total_price"] = bcdiv($basic["total_price"],1,2);

        $re = '/@foreach start(.*)@foreach end/m'; //Html ForEach loop
        $matches = $this->getRegxResult($re, $htmlTemplate);
        
        //table template
        $tableTempleate = $matches[0][1];

        if($paper['iqd'] == "true"){
            $discount = $basic["discount"] ?? 0;
            $total_price = iqdRound(($basic["total_price"] - $discount) * getIQD()["val"]);
            $sub_total = iqdRound(($basic["total_price"]) * getIQD()["val"]);
        }else{
            $discount = (round(($basic["discount"] / getIQD()["val"]),2) ?: "0") . "$";
            $total_price = round(($basic["total_price"] - $discount),2) . "$";
            $sub_total = $basic["total_price"] . "$";
        }
        // prepare table items
        $tableItems = "";
        foreach ($items as $key => $item){
            $moredata = getProductByBarcode($item[0]);
            $itemLocation = $moredata["box"] . "-" . $moredata["shelf"]  . "-" . $moredata["corridor"]  . "-" . $moredata["warehouse_name"];
            $tableItems .= str_replace(
                [
                    "@itemLocation",
                    "@alone_number",
                    "@item_name", 
                    "@item_number", 
                    "@item_qty", 
                    "@item_price",
                    "@pricexqty",
                    "@priceiqd",
                    "@inventory"
                ], [
                    ($paper['size'] == "77") ? "<br>" . $itemLocation : "",
                    $moredata["number"] ?: "---",
                    $moredata["name"], 
                    ($paper['size'] == "77") ? "<br>" . $moredata["number"] : "",
                    $item[1], 
                    ($paper['iqd'] == "true") ? iqdRound($item[2] * getIQD()["val"]) : $item[2],
                    $item[2] * $item[1], 
                    iqdRound($item[2] * $item[1] * getIQD()["val"]),
                    $itemLocation
                ], $tableTempleate);
        }


        // add table items to html scrpit
        $htmlTemplate = str_replace($matches[0][0], $tableItems, $htmlTemplate);

        $htmlTemplate = str_replace(
            [
		"@msgfooter",
                "@order_id",
                "@user_name",
                "@date",
                "@sub_total",
                "@discount", 
                "@total_price", 
                "@logo_url", 
                "@client_name", 
                "@paper_size",
                "@logo_width",
                "@logo_height",
                "@footer_text",
                "@currentPage",
                "@allPages"
            ] ,[
		$paper["@footer_text"],
                $basic["order_id"],
                getUserByID(getOrder($basic["order_id"])["employee_id"])["name"] ?: getUserByID($_SESSION["user_id"])["name"],
                $basic["date"],
                $sub_total,
                $discount,
                $total_price,
                getSittings($paper["logo"]), 
                (getConsumer($basic["consumer_id"], false, true)[0]['name']) ?? "عميل افتراضي", 
                $paper["@paper_size"],
                $paper["@logo_width"],
                $paper["@logo_height"],
                $paper["@footer_text"],
                $currentPage,
                $allPages
            ], $htmlTemplate);
        return $htmlTemplate;
    }

}

function getPrinterByID($id) {
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM printers WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row;
            }
        }
    }
    return false;
}

function updatePrinterSize($id,$size) {
    $conn = conn();
    $stmt = $conn->prepare("UPDATE printers SET size = ? WHERE id = ?");
    $stmt->bind_param("si", $size, $id);
    if ($stmt->execute()) {
        return true;
    }
    return false;
}
