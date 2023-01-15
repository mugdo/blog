<?php
include($_SERVER['DOCUMENT_ROOT'].'/blog/common.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');

    $is_create = registation($data);
    if ($is_create){
        echo "User Registation successfully";
        return;
    }
    echo "Can't create";
}