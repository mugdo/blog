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


function registation($data){
    global $mysql;
    $json = json_decode($data);
    $username = $json->username;
    $password = $json->password;

    $hashed_password=password_hash($password, PASSWORD_BCRYPT);
   
    $sql = "INSERT INTO user (username, password) VALUES ('$username', '$hashed_password')";
    if ($mysql->query($sql) === TRUE) {
        return true;
        } else {
        return false;
    }
}
function login($data){
    global $mysql;
    $json = json_decode($data);
    $username = $json->username;
    $password = $json->password;
    $sql = "SELECT * FROM user WHERE `username`='$username'";
    $result = $mysql->query($sql);
    if ($result->num_rows > 0) {
        $row =  $result->fetch_assoc();
        $token = bin2hex(random_bytes(64));
        if(password_verify($password ,$row['password'])){
            $sql = "SELECT * FROM auth WHERE `username`='$username'";
            $result = $mysql->query($sql);
            if ($result->num_rows > 0) {
                $sql = "UPDATE auth SET token = '$token'  WHERE `username` = '$username'";
                $update = $mysql->query($sql);
                return $token;
                } else {
                    $sql = "INSERT INTO auth (token, username) VALUES ('$token', '$username ')";
                    $create = $mysql->query($sql);
                    return $token;  
            }
              
        }
    }
}

function getAuthorizationHeader(){
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));

        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}
function getBearerToken() {
    $headers = getAuthorizationHeader();
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}

function comment_create($data){
    global $mysql;
    $json = json_decode($data);
    $blog_id = $json->id;
    $description = $json->description;
    $token = getBearerToken();
    $sql = "SELECT * FROM auth WHERE `token`='$token'";
    $result = $mysql->query($sql);
    if ($result->num_rows > 0) {
        $sql = "INSERT INTO comments (blog, description) VALUES ('$blog_id', '$description')";
        if ($mysql->query($sql) === TRUE) {
            return true;
            } else {
            return false;
        }
    }
    return false;
   
}




