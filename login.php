<?php
include 'common.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    header("Content-Type:application/json");
    $is_create = login($data);
    if ($is_create){
        $myObj = new stdClass();
        $myObj->message = "Login Successfully";
        $myObj->Token = "$is_create";
        $myJSON = json_encode($myObj);
        echo $myJSON;
        return;
    }
    echo "Can't login";
}