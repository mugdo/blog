<?php
$mysql = new mysqli("localhost", "root", "", "data");
if(!$mysql){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
