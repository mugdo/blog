<?php
include('db/db_connection.php');

function get(){
    global $mysql;
    $sql = "SELECT * FROM blog";
    $blog = mysqli_query($mysql, $sql);
    while($row=mysqli_fetch_assoc($blog)){
        $ar[] = $row;
    }
    return $ar;
}
function create($data){
    global $mysql;
    $json = json_decode($data);
    $title = $json->title;
    $description = $json->description;
    $slug = "";
    while(true){
        $sql = "SELECT * FROM blog WHERE `title`='$title'";
        $result = $mysql->query($sql);
        if ($result->num_rows > 0) {
            $title = $title . rand(10,100); 
        }else{
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));  
            break;
        }
    }
    $sql = "INSERT INTO blog (title, slug, description) VALUES ('$title', '$slug', '$description')";
    if ($mysql->query($sql) === TRUE) {
        return true;
        } else {
        return false;
    }
}
function delete($data){
    global $mysql;
    $json = json_decode($data);
    $id =  $json->id;
    $sql = "DELETE FROM blog WHERE `id`='$id'";
    if ($mysql->query($sql) === TRUE) {
        return true;
        } else {
        return false;
    }
        
}
function update($data){
    global $mysql;
    $json = json_decode($data);
    $title = $json->title;
    $description = $json->description;
    $id =  $json->id;
    $sql = "SELECT * FROM blog WHERE `id`='$id'";
    $result = $mysql->query($sql);
    $row =  $result->fetch_assoc();
    $slug = "";
    if($title){
        while(true){
            $sql = "SELECT * FROM blog WHERE `title`='$title'";
            $result = $mysql->query($sql);
            if ($result->num_rows > 0) {
                $title = $title . rand(10,100); 
            }else{
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));  
                break;
            }
        }
        $row['title'] = $title;
        $row['slug'] = $slug;
    }
    if($description){
        $row['description'] = $description;
    }
    $title =  $row['title'];
    $slug =  $row['slug'];
    $description = $row['description'];
    
    $sql = "UPDATE blog SET description = '$description', title = '$title',slug = '$slug'  WHERE id = $id";
    if ($mysql->query($sql) === TRUE) {
        return true;
        } else {
        return false;
    }
        
}

