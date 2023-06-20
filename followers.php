<?php
    session_start();
    require_once "connection.php";
    require_once "header.php";
    require_once "functions.php";
    if ( empty($_SESSION['id'])){
        header("Location: index.php");
    }
    $id = $_SESSION['id'];
    
    if ( isset( $_GET['friend_id'])){
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
    }

    if ( isset( $_GET['unfriend_id'])){
        //zahtev za odpracenje korisnika
        $friendId = $conn -> real_escape_string($_GET['unfriend_id']);
    
        $q = "DELETE FROM `followers` 
        WHERE `id_sender` = $id AND `id_receiver` = $friendId
        ";
        $conn ->query($q);
    }

    //odrediti koje druge korisnike prati logovan korisnik
    $niz1 = [];
    $upit1 = "SELECT `id_receiver` FROM `followers` WHERE `id_sender` = $id";
    $res1 = $conn -> query($upit1);
    while( $row = $res1 -> fetch_array(MYSQLI_NUM)){
        $niz1[]=$row[0];
    }
    // var_dump($niz1);
    //odrediti koje drugi korisnici prate logovanog korisnika
    $niz2 = [];
    $upit2 = "SELECT `id_sender` FROM `followers` WHERE `id_receiver` = $id";
    $res2 = $conn -> query($upit2);
    while( $row = $res2 -> fetch_array(MYSQLI_NUM)){
        $niz2[]=$row[0];
    }
    // var_dump($niz2);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members Of Social Network</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
<div class="container-fluid content-form ">

    <?php

    createHeader(['home','profile','connections','logout'],$conn);
?>
    <div class="container mt-4">
        <div class="row">
            <div class="container-fluid">
                
    <?php
        $q = "SELECT `u`.`id`,`u`.`username`,`p`.`profile_image`,
        CONCAT(`p`.`first_name`, ' ', `p`.`last_name`) AS `full_name`
        FROM `users` AS `u`
        LEFT JOIN `profiles` AS `p`
        ON `u`.`id`=`p`.`id_user`
        WHERE `u`.`id` != $id
        ORDER BY `full_name`
        ";
    $res= $conn -> query($q);
    if ( $res -> num_rows == 0 ){
        ?>
                <div class="error">No other users in database :(</div>
<?php
    }else{
        ?>
                <div class="card  mb-5">
                    <div class="card-header">
                            <h4>See other members from our site</h4>
                    </div>
                    <div class="card-body">

                        
<?php
                        $profiles = $res->fetch_all(MYSQLI_ASSOC);
                        $haveProfiles = [];
                        $haveFLeter = [];
                        $dontHaveProfiles = [];
                        $dontHaveFLeter = [];
                        foreach ($profiles as $profile ){
                            if ( $profile['full_name'] !== NULL ){
                                if ( !in_array( substr($profile['full_name'],0,1),$haveFLeter)){
                                    $haveFLeter[] = substr($profile['full_name'],0,1);
                                }
                                $haveProfiles[]=$profile;    
                            }else{
                                if ( !in_array(substr($profile['username'],0,1), $dontHaveFLeter )){
                                    $dontHaveFLeter[] = substr($profile['username'],0,1);
                                }
                                $dontHaveProfiles[]=$profile;    

                            }
                         }
                            // foreach($dontHaveFLeter as $letter )
                            // echo "<p>$letter</p>";
                        }
                        foreach ( $haveFLeter as $letter ){
                            ?>
                        <table class="table table-bordered table-striped table-followers">
                            <thead>
                                <tr class="d-none d-md-table-row">
                                    <th><?php echo strtoupper($letter);  ?></th>
                                    <th>Name</th>
                                    <th>Profile Image</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                            foreach ( $haveProfiles as $profile ){
                                if ( substr($profile['full_name'],0,1) == $letter ){
                                    
                                echo "<tr>";
                                    echo "<td></td>";
                                    echo "<td><a href='show_profile.php?id=".$profile['id']."'>".$profile['full_name']."</a></td>";
                                    echo "</td>";
                                    $friendId = $profile['id'];
                                    if ( $profile['profile_image'] !== NULL ){
                                    echo "<td class='d-block d-md-table-cell d-flex justify-content-center pt-2'>
                                    <img src='images/".$profile['profile_image']."' alt='profile img' ></td>";
                                    }else{
                                    echo "<td class='d-block d-md-table-cell d-flex justify-content-center pt-2'><img src='images/o_avatar.webp' alt='profile img'></td>";
                                    }
                                    if ( !in_array($friendId,$niz1)){
                                    if (!in_array($friendId,$niz2)){
                                        $text = 'Follow';
                                    }else{
                                        $text = 'Follow back';
                                    }
                                    echo "<td class='d-block d-md-table-cell d-flex justify-content-center pt-2 pb-3'><a href='followers.php?friend_id=$friendId' class='btn btn-info me-2'>$text</a></td>";
                                    }else{
                                    echo "<td><a href='followers.php?unfriend_id=$friendId' class='btn btn-danger'>Unfollow</a></td>";
                                    }
                                echo "</tr>";
                                }
                            }
                        }
                        ?>
                            </tbody>
                        </table>
                        <?php
                        foreach ( $dontHaveFLeter as $letter ){
                            ?>
                        <table class="table table-bordered table-striped table-followers">
                            <thead>
                                <tr class="d-none d-md-table-row">
                                    <th><?php echo strtoupper($letter);  ?></th>
                                    <th>Name</th>
                                    <th>Profile Image</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                            foreach ( $dontHaveProfiles as $profile ){
                                if ( substr($profile['username'],0,1) == $letter ){
                                    
                                echo "<tr>";
                                    echo "<td></td>";
                                    echo "<td><a href='show_profile.php?id=".$profile['id']."'>".$profile['username']."</a></td>";
                                    echo "</td>";
                                    $friendId = $profile['id'];
                                    if ( $profile['profile_image'] !== NULL ){
                                    echo "<td class='d-block d-md-table-cell d-flex justify-content-center pt-2'>
                                    <img src='images/".$profile['profile_image']."' alt='profile img' ></td>";
                                    }else{
                                    echo "<td class='d-block d-md-table-cell d-flex justify-content-center pt-2'><img src='images/o_avatar.webp' alt='profile img'></td>";
                                    }
                                    if ( !in_array($friendId,$niz1)){
                                    if (!in_array($friendId,$niz2)){
                                        $text = 'Follow';
                                    }else{
                                        $text = 'Follow back';
                                    }
                                    echo "<td class='d-block d-md-table-cell d-flex justify-content-center pt-2 pb-3'><a href='followers.php?friend_id=$friendId' class='btn btn-info me-2'>$text</a></td>";
                                    }else{
                                    echo "<td><a href='followers.php?unfriend_id=$friendId' class='btn btn-danger'>Unfollow</a></td>";
                                    }
                                echo "</tr>";
                                }
                            }
                        }
                            
?>
                            </tbody>
                        </table>
                        <p>Back to <a href="index.php">Home page</a></p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    

</body>
</html>