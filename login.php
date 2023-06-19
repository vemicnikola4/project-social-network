<?php
//prvo ukljucimo sesiju.
session_start();
require_once "connection.php";
require_once "header.php";
require_once "validation.php";
require_once "style.css";

if ( isset( $_SESSION['id'])){
    header( "Location: index.php?p=vec_ulogovan");
}
$usernameError = "*";
$passwordError = "*";
$username = "";
if ( $_SERVER["REQUEST_METHOD"] =="POST" ){
    //Korisnik je posleo username i password ipokusava logovanje
    //Ne preporucuje se da se da rednost odmah u kok
    //vec se prvo tretira da smo sigurni da je to string a ne neki upit sql isl. Da ne bi doslo do zloupotrebe.
    $username = $conn-> real_escape_string( $_POST["username"]);
    $password = $conn-> real_escape_string( $_POST["password"]);

    if ( empty($username )){
        $usernameError = "Username can not be blank";
    }

    if ( empty($password )){
        $passwordError = "Password can not be blank";
    }
    if ( $usernameError == "*" && $passwordError == "*" ){
        $q = "SELECT * FROM `users` WHERE `username` = '".$username."'" ;
        $result = $conn -> query ($q);
        if ( $result -> num_rows == 0){
                $usernameError ="This username does not exists!";
            }else{
                $row = $result->fetch_assoc();
                $dbPassword = $row['password'];
                if(!password_verify( $password, $dbPassword)){
                    //poklopili su se username ali lozinka nije dobra
                    //Ovde moze da se broji br neuspelih logovanja
                    $passwordError = "Wrong password try again!";
                }else{
                    //dobr i username i password
                    //memorija zajednicka za sve php stranice izgleda kao assocniz
                    $_SESSION['id'] =$row['id'];
                    $_SESSION['username'] = $row['username'];
                    header("Location: index.php");
                }
            }
    

    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
<div class="container-fluid content-form ">
    <?php
    createHeader(['home','register','login'],$conn);
?>
<div class="container mt-5 ">
    <div class="row justify-content-center ">
            <div class="col-md-6 ">
                <div class="card">
                    <div class="card-header bg-light py-2 pb-1">
                        <h4>Please Login</h4>
                    </div>
                    <div class="card-body bg-none">
                        <form action="login.php" method="POST" >
                            <div class="mb-3">
                                <label for="username" class="form-label mb-3">Username</label>
                                <input type="text" name="username" class="form-control border-0 border-bottom shadow-sm" value="<?php echo $username; ?>" id="username" aria-describedby="emailHelp">
                                <span class="text-danger"> <?php echo $usernameError; ?></span>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label mb-3">Password</label>
                                <input type="password" name="password" class="form-control border-0 border-bottom shadow-sm" value="<?php echo $password; ?>" id="password" aria-describedby="emailHelp">
                                <span class="text-danger"> <?php echo $passwordError; ?></span>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

                </div>
            </div>
        </div>
    </div>
       
</div>    
    </div>

    
</body>
</html>