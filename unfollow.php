<?php
//koji salje zahtev za unfollow je logovan korisnik
//koji prima zahtev  za unfollow dohvatamo iz baze pomocu url
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
    
    $q = "DELETE FROM `followers` 
    WHERE `id_sender` = $id AND `id_receiver` = $friendId
    ";
    
    $conn ->query($q);
    header("Location: followers.php");


