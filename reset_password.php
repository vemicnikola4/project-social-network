<?php
session_start();
if (empty($_SESSION['id'])){
    header("Location: index.php?m=niste_logovani");
}
require_once "header.php";
require_once "connection.php";
require_once "validation.php";
$id = $_SESSION['id'];

$password = $newPassword = $retype = "";
$passwordError = $newPasswordError = $retypeError = "";
$successMsg = "";
$errorMsg = "";
if ( $_SERVER["REQUEST_METHOD"] == "POST" ){
    $password = $conn->real_escape_string($_POST['password']);
    $newPassword = $conn->real_escape_string($_POST['new_password']);
    $retype = $conn->real_escape_string($_POST['retype']);

    $passwordError = passwordValidation($password);
    $newPasswordError = passwordValidation($newPassword);
    $retypeError = passwordValidation($retype);

    if ( $password == $newPassword ){
        $newPasswordError = "New password must be diferent from the old password!";
    }
    if ( $newPassword !== $retype ){
        $retypeError = "Repeat the same password";
    }
    

    if ( $passwordError == "" && $newPasswordError == "" && $retypeError == "" ){
        $q = "SELECT * FROM `users` WHERE `id` = ".$id."";
        $res = $conn -> query ( $q );
        $row = $res->fetch_assoc();
        $dbPassword = $row['password'];
        if ( !password_verify( $password, $dbPassword) ){
            $errorMsg = "Wrong password try again!";
        }else{
            $hash = password_hash($newPassword,PASSWORD_DEFAULT );
            $q = "UPDATE `users`  SET `password` = '".$hash."' WHERE `id` = $id ";
            if ( $conn->query($q) ){
                $successMsg = "Password reset successfully!";
                $password = $newPassword = $retype = "";

            }else{
                $errorMsg = "Error occured try again!";

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
    <title>Register new user</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
<div class="container-fluid content-form ">
    <?php
        createHeader(['home','profile','connections','logout'],$conn);

    ?>
    <div class="container mt-5">
            <div class="row justify-content-center ">
                <div class="col-md-6 ">
                    <div class="card">
                        <div class="card-header bg-light py-2 pb-1">
                            <h4>Register to our site</h4>
                        </div>
                        <div class="text-success fw-bold mt-1 ms-3">
                            <?php echo $successMsg; 
                            if ( $successMsg !== ""){
                                echo "<p class='fw-bold text-dark  mt-1'>
                                Go back to <a href='profile.php'>profile page</a> 
                                </p>";
                            }
                            ?>
                            
                        </div>
                        <div class="text-danger fw-bold mt-1 ms-3">
                            <?php echo $errorMsg; ?>
                        </div>
                        <div class="card-body">
                            <form action="reset_password.php" method="POST" >
                                <div class="mb-3">
                                    <label for="password" class="form-label mb-3">Password</label>
                                    <input type="password" name="password" class="form-control border-0 border-bottom shadow-sm" value="<?php echo $password?>" id="password" aria-describedby="emailHelp">
                                    <span class="text-danger">*<?php echo $passwordError ?></span>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label mb-3">New Password</label>
                                    <input type="password" name="new_password" class="form-control border-0 border-bottom shadow-sm" value="<?php echo $newPassword?>" id="username" aria-describedby="emailHelp">
                                    <span class="text-danger">*<?php echo $newPasswordError ?></span>
                                </div>
                                <div class="mb-3">
                                    <label for="retype" class="form-label mb-3">Retype password</label>
                                    <input type="password" name="retype" class="form-control border-0 border-bottom shadow-sm" value="<?php echo $retype?>" id="retype" aria-describedby="emailHelp">
                                    <span class="text-danger">*<?php echo $retypeError ?></span>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                            <p class="mt-3">
                                Go back to <a href="index.php">Home page</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
       
    </div>
</div>

</body>
</html>