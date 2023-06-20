<?php
    session_start();


require "connection.php";
if ( empty($_SESSION['id'])){
    header("Location: index.php?m=niste_ulogovani");
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
$q = "SELECT `users`.`id`,`users`.`username`,`profiles`.`profile_image`,
CONCAT(`profiles`.`first_name`, ' ',`profiles`.`last_name`) AS `full_name`
FROM `users`
LEFT JOIN `profiles` ON `users`.`id`=`profiles`.`id_user`
WHERE `users`.`id` IN (SELECT `id_sender` FROM `followers` WHERE `id_receiver` = $id)
ORDER BY `full_name`
";
$res = $conn->query($q);
if ( $res -> num_rows == 0 ){
    $msg = "No fallowers!";
}else{
    ?>
<div class="container mt-4">
    <div class="row">
        <div class="container-fluid">
            <div class="card  mb-5">
                <div class="card-header">
                        <h4>People who folow you!</h4>
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
                            while ( $row = $res -> fetch_assoc()){
                                echo "<tr>";
                                    if ( $row['full_name'] !== Null){
                                        echo "<td>".$row['full_name']."</td>";
                                    }else{
                                        echo "<td>".$row['username']."</td>";
                                        
                                    }
                                    $friendId = $row['id'];
                                    if ( $row['profile_image'] !== NULL ){
                                    echo "<td class='d-block d-md-table-cell d-flex justify-content-center pt-2'>
                                    <img src='images/".$row['profile_image']."' alt='profile img' ></td>";
                                    }else{
                                    echo "<td class='d-block d-md-table-cell d-flex justify-content-center pt-2'><img src='images/o_avatar.webp' alt='profile img'></td>";
                                    }
                                    if ( !in_array($friendId,$niz1)){
                                    if (!in_array($friendId,$niz2)){
                                        $text = 'Follow';
                                    }else{
                                        $text = 'Follow back';
                                    }
                                    echo "<td class='d-block d-md-table-cell d-flex justify-content-center pt-2 pb-3'><a href='followers2.php?friend_id=$friendId' class='btn btn-info me-2'>$text</a></td>";
                                    }else{
                                    echo "<td><a href='followers2.php?unfriend_id=$friendId' class='btn btn-danger'>Unfollow</a></td>";
                                    }
                                echo "</tr>";
                            }

?>


        <?php
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