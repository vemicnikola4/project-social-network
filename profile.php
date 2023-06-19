<?php
session_start();
require_once "connection.php";
require_once "validation.php";
require_once "header.php";

if ( !isset( $_SESSION['id'])){
    header( "Location: index.php");
}
$id = $_SESSION['id'];
$firstName = $lastName= $dob = $gender ="";
$firstNameError = $lastNameError= $dobError = $genderError =$profileIMage = "";
$sucMessage = "";
$errMessage = "";
$profilEditing = false;
$profileRow = profileExists( $id, $conn );
if ( $profileRow !== false ){
    $firstName = $profileRow['first_name'];
    $lastName = $profileRow['last_name'];
    $gender = $profileRow['gender'];
    $dob = $profileRow['dob'];
    $profilEditing = true;
}
if ( $profileRow === false ){
    $submitValue ="Create profile";
}else{
    $submitValue ="Edit profile";

}
if ( isset($_GET['m']) && $_GET['m'] == 'reset'){
    $firstName = $lastName= $dob = $gender ="";

}

if ( $_SERVER["REQUEST_METHOD"] == "POST"){
    $firstName =$conn->real_escape_string($_POST['first_name']);
    $lastName =$conn->real_escape_string($_POST['last_name']);
    $gender=$conn->real_escape_string($_POST['gender']);
    $dob=$conn->real_escape_string($_POST['dob']);
    $profileImage=$conn->real_escape_string($_POST['profile_image']);
    
    //validacija polja
    //ako je sve u redu kreiramo nov profil
    $firstNameError = nameValidation($firstName);
    $lastNameError = nameValidation($lastName);
    $genderError = genderValidation($gender);
    $dobError = dobValidation($dob);
    // var_dump ($dob);
    if ( $profileImage == NULL ){
        $profileImage = $gender.'_avatar.webp';
    }
    if ( $firstNameError == "" && $lastNameError == "" && $genderError == "" && $dobError == ""){
        $q = "";
        $exists;
        if ( $profileRow === false ){
            $q = "INSERT INTO  `profiles`
            (`first_name`,`last_name`,`gender`,`dob`,`id_user`,`profile_image`)
            VALUES
            ('".$firstName."','".$lastName."','".$gender."','".$dob."',$id,'".$profileImage."')";
            
        }else{
            $q = "UPDATE `profiles` SET 
            `first_name` = '".$firstName."',
            `last_name` = '".$lastName."',
            `gender` = '".$gender."',
            `dob` = '".$dob."',
            `profile_image`= '".$profileImage."'
            WHERE `id_user` = $id
            ";
        }
        
        if ( $conn -> query($q)){
            if ( $profileRow !== false ){
                $sucMessage = " Your have edited your profile";
            }else{
                $sucMessage = " Your have created your profile";
            }
        }else{
            $errMessage = "Error creating profile " .$conn->error;
        }
    }
    
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                <div class="card border border-success">
                    <div class="card-header bg-light py-2 pb-1 ">
                        <h4>Please fill the profile detailes</h4>
                    </div>
                    <div class="text-success fw-bold mt-1 ms-3">
                        <?php echo $sucMessage;  ?>
                    </div>
                    <div class="text-danger fw-bold mt-1 ms-3">
                        <?php echo $errMessage; ?>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST" class="fs-6">
                            <div class="mb-3">
                                <label for="first_name" class="form-label mb-3">First name</label>
                                <input type="text" name="first_name" class="form-control border-0 border-bottom shadow-sm" value="<?php echo $firstName; ?>" id="firstName" aria-describedby="emailHelp">
                                <span class="text-danger">* <?php echo $firstNameError; ?></span>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label mb-3">Last Name</label>
                                <input type="last_name" name="last_name" class="form-control border-0 border-bottom shadow-sm" value="<?php echo $lastName; ?>" id="last_name" aria-describedby="emailHelp">
                                <span class="text-danger">* <?php echo $lastNameError; ?></span>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="m" value='m' <?php if ( $gender == 'm'){echo 'checked';}?>>Male    
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="f" value='f' <?php if ( $gender == 'f'){echo 'checked';}?>>Female    
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="o" value='o' <?php if ( $gender == 'o' || $gender == ''){echo 'checked';}?>>Other   
                            </div>
                            <div class="form-check mt-4">
                                <label for="dob" class="form-label date-input-label">Date of birth:</label>
                                <input type="date" class="date_input" name="dob" id="dob" value = "<?php echo $dob ?>" >
                            </div>
                            
                            <div class="mt-3">
                                <label for="profile_image" class="form-label">Choose profile image</label>
                                <input class="form-control" type="file" name='profile_image' id="profile_image" value="<?php echo $profileImage?>">
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary"><?php echo $submitValue; ?></button>
                            </div>
                            <div class="mt-3">
                                <a href="profile.php?m=reset" class="btn btn-danger">Reset</a>
                                <!-- <button type="reset" class="btn btn-danger">Reset</button> -->
                            </div>
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
</div>

<!-- <h1>Please fill the profile detailes</h1>
    <form action="#" method="post" class="create_profile_form">
        <p>
            <label for="first_name">First name:</label>
            <input type="text" name="first_name" id="first_name" value="<?php echo $firstName ?>">
            <span class="error">*<?php echo $firstNameError;?> </span>
        </p>
        <p>
            <label for="last_name">Last name:</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo $lastName ?>">
            <span class="error">*<?php echo $lastNameError; ?> </span>

        </p>
        <p>
            <label for="gender">Gender</label>
            <input type="radio" name="gender" id="m" value="m" <?php if ( $gender == 'm'){echo 'checked';}?> >Male
            <input type="radio" name="gender" id="f" value="f" <?php if ( $gender == 'f'){echo 'checked';}?> >Female
            <input type="radio" name="gender" id="o" value="o" <?php if ( $gender == 'o' || $gender == ''){echo 'checked';}?>>Other
            <span><?php echo $genderError ?></span>
        </p>
        <label for="dob">Date of birth:</label>
        <input type="date" name="dob" id="dob" value = "<?php echo $dob ?>" >
        <span class="error"><?php echo $dobError ?></span>
        <p>
            <input type="submit" value="Create profile">
        </p>
    </form> -->
</body>
</html>