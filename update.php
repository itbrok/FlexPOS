<?php

define('DIR', dirname(__FILE__));

$save = [basename(__FILE__), "update.zip"];

ignore_user_abort(true);
set_time_limit(3600);

function deleteDir($dirPath) {
    if (!is_dir($dirPath)) {
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
            if (!in_array(basename($file), $GLOBALS['save'])) {
                unlink($file);
            }
        }
    }
}
function recurse_copy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir") rrmdir($dir . "/" . $object);
                else unlink($dir . "/" . $object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}
function checkUpdate($update = false) {
    require_once("object.php");
    include_once 'config.php';

    //create new progress bar object
    $po = new ProgressObj();

    //in records present, set loading text and display bar
    if ($update) {
        $po->text = "جلب معلومات التحديث";
        $po->DisplayMeter();

        $count = 10;
        $po->Calculate($count);
        str_repeat(' ', 4480);
        ob_flush();
        flush();
    }

    // From URL to get webpage contents.
    $url = $flex['update_link'];
    // Get version number and update URL
    $values = json_decode(file_get_contents($url), true);



    $control = control(['init' => 1]);


    if ($values["version"] != $flex['version']) {
        if ($update == true) {
            // assuming file.zip is in the same directory as the executing script.
            $file = "update.zip";

            $po->text = "تنزيل ملفات التحديث";
            // Download the zip file that contains the new version
            $dfile = fopen($file, 'w');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $values["zip"]);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1900);
            curl_setopt($ch, CURLOPT_FILE, $dfile);

            $result = curl_exec($ch);

            if ($result === false) {
                curl_close($ch);
                fclose($dfile);
                echo 'Error: ' . curl_error($ch);
            } else {
                curl_close($ch);
                fclose($dfile);

                str_repeat(' ', 2480);

                $po->Animate();

                //push the content out to the browser
                ob_flush();
                flush();
                // get the absolute path to $file
                $path = pathinfo(realpath($file), PATHINFO_DIRNAME);
                $zip = new ZipArchive;
                $res = $zip->open($file);

                $po->Animate();

                //push the content out to the browser
                ob_flush();
                flush();
                //push the content out to the browser
                ob_flush();
                flush();
                $po->Animate();

                //push the content out to the browser
                ob_flush();
                flush();
                $po->Animate();

                if ($res === TRUE) {
                    $po->text = "استخراج الملفات";
                    DeleteDir(DIR);
                    $po->Animate();

                    //push the content out to the browser
                    ob_flush();
                    flush();
                    $po->Animate();


                    //push the content out to the browser
                    $po->text = "جار التحديث";
                    ob_flush();
                    flush();


                    $config_data = $conn_;
                    // extract it to the path we determined above
                    $zip->extractTo($path);
                    $zip->close();
                    $po->Animate();


                    $dest = "FlexPOS-main";
                    recurse_copy($dest, ".");
                    rrmdir($dest);
                    
                    //push the content out to the browser
                    ob_flush();
                    flush();

                    $config_file = file_get_contents("config.php");
                    $config_file = str_replace(["#APPID", "#HOST", "#USERNAME", "#PASSWORD", "#DBNAME"], [json_decode($control)->key,$config_data["host"], $config_data["user"], $config_data["pass"], $config_data["dbna"]], $config_file);
                    file_put_contents("config.php", $config_file);
                    $po->Animate();

                    //push the content out to the browser
                    ob_flush();
                    flush();
                    $po->Animate();

                    //push the content out to the browser
                    ob_flush();
                    flush();
                    unlink($file);
                    $po->Animate();

                    //push the content out to the browser
                    $po->text = "اكتمل التحديث";
                    ob_flush();
                    flush();
                    usleep(50000);
                    $po->Hide();
                    ob_flush();
                    flush();
                } else {
                    $po->text = "حدث خطا اثناء استخراج البيانات";
                }
            }
        } else {
            return ["ok" => true, 3]; // "There is a new version"
        }
    } else {
        return ["ok" => false, 4]; //"There isn't a new version"
    }
}
if (@$_POST["check"] == 1) {
    echo json_encode(checkUpdate());
}

if (@$_GET["update"] == 1) {
    checkUpdate(true);
}
