<?php
//koji salje zahtev je logovan korisnik
//koji prima zahtev dohvatamo iz baze
session_start();
    require_once "connection.php";
    if ( empty($_SESSION['id'])){
        header("Location: index.php");
    }
    $id = $_SESSION['id'];

    if ( empty($_GET['friend_id'])){
        header("Location: index.php");
    }
    $friendId = $conn -> real_escape_string($_GET['friend_id']);
    
    $q = "SELECT * FROM `followers` 
    WHERE `id_sender` = $id AND `id_receiver` = $friendId 
    ";
    
    $res = $conn->query($q);
    if ( $res -> num_rows == 0 ){
        $upit = "INSERT INTO `followers` (`id_sender`,`id_receiver`)
        VALUE 
        ($id, $friendId)
        ";
        $res1 = $conn->query($upit);
    }
    header("Location: followers.php");



?>