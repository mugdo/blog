<?php

include('common.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $is_create = comment_create($data);
    if ($is_create){
        echo "Comment saved successfully. $is_create";
        return;
    }
    echo "Please login first";
   
}
