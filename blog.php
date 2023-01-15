<?php

include('common.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = get();
    header("Content-Type:application/json");
    echo json_encode($data);
  
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');

    $is_create = create($data);
    if ($is_create){
        echo "Blog created successfully";
        return;
    }
    echo "Can't create";
   
}
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = file_get_contents('php://input');

    $is_create = update($data);
    if ($is_create){
        echo "Blog Update successfully";
        return;
    }
    echo "Can't update";
   
}
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = file_get_contents('php://input');

    $is_create = delete($data);
    if ($is_create){
        echo "Blog Deleted successfully";
        return;
    }
    echo "Can't delete";
   
}