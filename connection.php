<?php

mysqli_report(MYSQLI_REPORT_OFF);
$server = "localhost";
$database = "network";
$username = "networkuser";
$password = "cocacola";//promeniti username i password za domaci

$conn = new Mysqli( $server, $username, $password, $database);
if ( $conn ->connect_error ){
    die("Neuspela konekcija ".$conn->connect_error);
    // die("Neuspela konekcija".$conn_conect_error);
}
$conn->set_charset("utf8");//utf 32 prosirenje utf8



?>