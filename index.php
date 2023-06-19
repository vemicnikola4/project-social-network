<?php
session_start();
require_once("style.css");
require_once "connection.php";
require_once "header.php";
$poruka = "";
if ( isset($_GET['p'])  && $_GET['p'] == 'registrated_ok'){
    $poruka = "You have successfully register!
    Please login to continue!";
}
if ( isset($_GET['p'])  && $_GET['p'] == 'vec_ulogovan'){
    $poruka = "You have already loged in";
}
$username = "anonymus";

if ( isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    $id = $_SESSION['id'];
    $q = "SELECT * FROM `profiles` WHERE `id_user` = ".$id."";
    
    $res = $conn -> query($q);
    if ( $res -> num_rows > 0){
        $row = $res -> fetch_assoc();
        $username = $row['first_name']. " ". $row['last_name'];
    }
    $arrayHeader= ['home','profile','connections','logout'];
}else{
    $arrayHeader= ['home','register','login'];

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <title>Social Network</title>
</head>
<body>
<?php
    createHeader($arrayHeader,$conn);

?>
<div class="container-fluid">
    <div class="row bg bg-light">
        <div class="col-lg-8 ">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="overlay-image" style="background-image:url(images/blonde-826027_1280.jpg)"></div>
                        <div class="carousel-caption d-none d-sm-block ">
                            <h3 class="text-light h2">Stay in touch with your friends and relatives!</h3>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="overlay-image" style="background-image:url(images/img1.png)"></div>
                        <div class="carousel-caption d-none d-md-block ">
                        <h3 class="text-light h2">Find your solemate...</h3>
                    </div>
                    </div>
                    <div class="carousel-item">
                        <div class="overlay-image" style="background-image:url(images/family-7727035_1280.webp)"></div>
                        <div class="carousel-caption d-none d-md-block ">
                        <h3 class="text-light h2">Or just have fun meating new people!</h3>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            </div>
        </div>
        <div class="col-lg-4 aling-text-center index-col">
            <div class="index-text  align-items-center pb-3">
            <h1 class="text-dark ">Welcome <?php echo ucfirst($username)  ?> to our Social Network!</h1>
            <?php 
            if ( $poruka == "" && !isset( $_SESSION['username'])){
                echo "<p class='fs-3 mt-3'>New to our site?</p>";
                echo '<button type="button" class="btn p-3 pt-2  mt-1 btn-primary  fs-2 fw-3 "><a href="register.php" 
                class="text-white">Register here</a></button>';
                
                echo "<p class='fs-3 mt-3'>Already have the account? To continue to our site!</p>";  
                echo '<button type="button" class="btn p-3 pt-2 mt-1 btn-primary  fs-2 fw-3"><a href="login.php" class="text-white">Login  here</a></button>';
            }elseif(!isset( $_SESSION['username'])){
                echo "<p class='text-success fs-3 mt-5 fw-bold'>".$poruka."</p>";
                echo '<button type="button" class="btn p-3 pt-2 mt-1 btn-primary  fs-2 fw-3"><a href="login.php" class="text-white">Login here</a></button>'; 
            }else{
                
                if ( $res->num_rows == 0){
                    echo "<p class='fs-3 text-danger'> $poruka </p>";
                    echo "<p class='fs-3 mt-5 fw-bold'>See other members <a href='followers.php'>here</a></p>";
                    echo "<p class='fs-3 mt-5 fw-bold'>Create profile <a href='profile.php'>here</a></p>";
                    echo '<button type="button" class="btn p-3 pt-2 mt-5  btn-danger  fs-2 fw-3"><a href="logout.php" class="text-white">Log out</a></button>';
                }else{
                    echo "<p class='fs-3 text-danger'> $poruka </p>";
                    echo "<p class='fs-3 mt-5 fw-bold'>See other members <a href='followers.php'>here</a></p>";
                    echo "<p class='fs-3 mt-5 fw-bold'>Edit profile <a href='profile.php'>here</a></p>";
                    echo '<button type="button" class="btn p-3 pt-2 mt-5  btn-danger  fs-2 fw-3"><a href="logout.php" class="text-white">Log out</a></button>';
                }
                
            }
        ?> 
            </div>
        </div>
    </div>
</div>


</body>
</html>