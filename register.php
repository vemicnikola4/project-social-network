<?php
session_start();
if ( isset( $_SESSION['id'])){
    header( "Location: index.php?p=vec_ulogovan");
}

require_once "validation.php";
require_once "connection.php";
require_once 'header.php';
require_once 'style.css';


$username="";
$password="";
$retype="";

$usernameError = "";
$passwordError = "";
$retypeError = "";

if ( $_SERVER['REQUEST_METHOD'] == "POST"){
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $retype = $conn->real_escape_string($_POST['retype']);

    //1)Izvrsiti validaciju za username
    $usernameError = usernameValidation($username, $conn);


    //2)Izvrsiti validaciju za password
    $passwordError = passwordValidation($password);


    //3)Izvrsiti validaciju za retype
    $retypeError = passwordValidation($retype);
    if ( $password !== $retype ){
        $retypeError = "You must enter two same passwords";
    }
    

    //4.1) Ako su sva polja validna onda treba dodati novog korisnika INSERT upit nad tabelom users
    if ($usernameError == ""  && $passwordError == "" && $retypeError == ""){
        $hash = password_hash($password,PASSWORD_DEFAULT );
        //prvi parametar password a drugi nacin hesiranja(algoritam)
        //hesiranje su razni algoritmi za sofriranje pravi niz stringa i karaktera
        //cak ni mi ne mozemo nikada znati koja je sifra
        $q = "INSERT INTO `users` (`username`,`password`)
        VALUES
        ('$username','$hash'); 
        ";
        if ( $conn -> query($q)){
            //kreiran novi korisnik vodi ga na index
            header("Location: index.php?p=registrated_ok");  
        }else{
            header("Location: error.php?".http_build_query(['m' => $conn->error])); //da bi mogao u url da prosledimo poruku sa razmacima
        }
        //u bazu ne bi trebali da stavljamo password vec sifrirani password
        //da se kodira tj da se hesuje
    }

    //4.2) Ako postoji polje koje nije validno vrati na istu formu a pored odgovarajuceg polja prikazi poruke
   
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register new user</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
<div class="container-fluid content-form ">
    <?php
    createHeader(['home','register','login'],$conn);
    ?>
    <div class="container mt-5">
            <div class="row justify-content-center ">
                <div class="col-md-6 ">
                    <div class="card">
                        <div class="card-header bg-light py-2 pb-1">
                            <h4>Register to our site</h4>
                        </div>
                        <div class="card-body">
                            <form action="register.php" method="POST" >
                                <div class="mb-3">
                                    <label for="username" class="form-label mb-3">Username</label>
                                    <input type="text" name="username" class="form-control border-0 border-bottom shadow-sm" value="<?php echo $username; ?>" id="username" aria-describedby="emailHelp">
                                    <span class="text-danger">* <?php echo $usernameError; ?></span>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label mb-3">Password</label>
                                    <input type="password" name="password" class="form-control border-0 border-bottom shadow-sm" value="<?php echo $password; ?>" id="password" aria-describedby="emailHelp">
                                    <span class="text-danger">* <?php echo $passwordError; ?></span>
                                </div>
                                <div class="mb-3">
                                    <label for="retype" class="form-label mb-3">Retype password</label>
                                    <input type="password" name="retype" class="form-control border-0 border-bottom shadow-sm" value="<?php echo $retype; ?>" id="retype" aria-describedby="emailHelp">
                                    <span class="text-danger">* <?php echo $retypeError; ?></span>
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