<?php
    session_start();
    require_once "connection.php";
    require_once "header.php";
    require_once "functions.php";
    if ( empty($_SESSION['id'])){
        header("Location: index.php");
    }
    $id = $_SESSION['id'];
    

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
                <div class="card">
                    <div class="card-header">
                            <h4>See other members from our site</h4>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped table-followers">
                            <thead>
                                <tr class="d-none d-md-table-row">
                                    <th>Name</th>
                                    <th>Profile Image</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
<?php
                        while( $row = $res -> fetch_assoc()){
                        echo "<tr class='table-success '>";
                            echo "<td class='d-block d-md-table-cell d-flex justify-content-center pt-2'>";
                            if ( $row['full_name'] !== NULL ){
                            echo $row['full_name'];
                            }else{
                            echo $row['username'];
                            }
                            echo "</td>";
                            $friendId = $row['id'];
                            if ( $row['profile_image'] !== NULL ){
                            echo "<td class='d-block d-md-table-cell d-flex justify-content-center pt-2'>
                                <img src='images/".$row['profile_image']."' alt='profile img' ></td>";
                            }else{
                            echo "<td class='d-block d-md-table-cell d-flex justify-content-center pt-2'><img src='images/o_avatar.webp' alt='profile img'></td>";
                            }
                            if ( following($id, $friendId, $conn)){
                                $buttonV = 'following';
                            }else{
                                $buttonV = 'follow';
                            }
                            echo "<td class='d-block d-md-table-cell d-flex justify-content-center pt-2 pb-3'><a href='follow.php?friend_id=$friendId' class='btn btn-info me-2'>".$buttonV."</a>
                            <a href='unfollow.php?friend_id=$friendId' class='btn btn-danger'>unfollow</a></td>";
                        echo "</tr>";

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