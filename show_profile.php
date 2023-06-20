<?php
session_start();
require_once "connection.php";
require_once "header.php";
// Kreirati novi fajl show_profile.php kojem se preko GET metode
// prosleđuje parametar id. Potrebno je na ovoj stranici prikazati meni,
// pozdraviti korisnika po imenu i prezimenu (obavezno uključivanjem
// fajla header.php ili kako se već zove kod vas), a ispod menija prikazati
// profil korisnika sa zadatim id-jem. Ukoliko se prosledi id korisnika koji
// ne postoji u bazi, ispisati odgovarajuću poruku u paragrafu. Potrebno
// je stilizovati paragraf sa 3 različita proizvoljna stila. Obavezno je
// napisati stilove u fajlu style.css (ne sme se pisati inline stil za pragraf).
// if ( empty($_SESSION['id'])){
//     header("index.php?m=niste_logovani");
// }
// $id = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
    <?php
    
    $firstName = $lastName = $bio = $date = $username = $gender = "";
    $errMsg = "";
    if ( $_SERVER['REQUEST_METHOD'] == "GET" && isset( $_GET['id'])){
        $id = $_GET['id'];
        $q = "SELECT `u`.`id`,`u`.`username`,`p`.`profile_image`,`p`.`bio`,
        `p`.`first_name`,`p`.`last_name` ,`p`.`dob`,`p`.`gender`
        FROM `users` AS `u`
        LEFT JOIN `profiles` AS `p`
        ON `u`.`id`=`p`.`id_user`
        WHERE `u`.`id` = $id";

        // $q = "SELECT * FROM `users` WHERE `id` = $id";
        $res = $conn -> query($q);
        if ( $res -> num_rows == 0 ){
            $errMsg = "No user with this id in database";
        }else{

            $r = $res -> fetch_assoc();
            $firstName =$r['first_name'];
            $lastName =$r['last_name'];
            $username = $r['username'];
            $date =$r['dob'];
            // $image = $r['profile_image'];
            $gender = $r['gender'];
            $bio =$r['bio'];
        }
    }
    createHeader(['home','profile','connections','logout'],$conn);
    ?>
    
    
<h1 class="text-dark ">Welcome <?php 
$q = "SELECT `first_name` , `last_name` FROM `profiles` WHERE `id_user` = ".$_SESSION['id']." ";
$user = $conn->query($q);
$res = $user->fetch_assoc();
$logName = $res['first_name'];
$logLastName = $res['last_name'];

echo $logName ." ". $logLastName;
?> to profile page!</h1>

<table border=1 class="<?php echo $gender ?> table-profile">
    <div>
        <p class ="err_msg">
            <?php echo $errMsg; ?>
        </p>
    </div>
    <tr>
        <td>Name: </td><td><?php echo $firstName ?></td>
    </tr>
    <tr>
        <td>Last name: </td><td><?php echo $lastName ?></td>
    </tr>
    <tr>
        <td>Username: </td><td><?php echo $username?></td>
    </tr>
    <tr>
        <td>Date of birth: </td><td><?php echo $date ?></td>
    </tr>
    <tr>
        <td>Gender: </td><td><?php echo $gender ?></td>
    </tr>
    <tr>
        <td>About me: </td><td><?php echo $bio ?></td>
    </tr>
</table>
<p class='link-profile'>Go to <a href="followers.php">fallowers</a></p>
</body>
</html>


