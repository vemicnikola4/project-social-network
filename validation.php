<?php

//prime username i conn i vraca string greske.
function usernameValidation($u, $conn){
    
    $query = "SELECT * FROM `users` WHERE `username` = '$u' ";
    $result = $conn->query($query);
    if ( empty( $u )){
        return "Username can not be blank!";
    }elseif(preg_match('/\s/',$u)){ //regularni izrazi. NA pocetku i na kraju su delimiteri. prvi parametar je neki sablon
        return "Username can not contain spaces!";
    }elseif(strlen($u) < 5 || strlen( $u ) > 25 ){
        return "Username must be between 5 and 25 characters!";
    }elseif($result->num_rows > 0){
        return "Username is reserved, please choose another one";
    }else{
        return "";
    }
}
function passwordValidation($password){
    if ( empty( $password )){
        return "Password cannot be blank!";
    }elseif(preg_match('/\s/',$password)){ 
        return "Password cannot contain spaces!";
    }elseif(strlen($password) < 5 || strlen( $password ) > 50 ){
        return "Password must be between 5 and 50 characters!";
    }else{
        return "";
    }
}

function nameValidation($n)
{
    $n = str_replace(' ', '', $n);
    if (empty($n))
    {
        return "Name can not be empty";
    }
    elseif (strlen($n) > 50)
    {
        return "Name can not contain more than 50 characters";
    }
    elseif (preg_match("/^[a-zA-ZŠšĐđŽžČčĆć]+$/", $n) == false)
    {
        return "Name must contain only letters";
    }
    else
    {
        return "";
    }
}

function genderValidation($g){
    if ( $g != 'm' && $g !='f' && $g != "o"){
        return "Unknown gender";
    }else{
        return "";
        
    }
}
function dobValidation($date){
    if ( empty($date) ){
        return "";
    }
    if ( $date < '1900-01-01'){
        return "Date not valid";
    }else{
        return "";
    }
}

function profileExists($id, $conn){
    $q = "SELECT * FROM `profiles` WHERE `id_user` = $id";
    $res = $conn -> query($q);
    if ( $res -> num_rows ==  0){
        return false;
    }else{
        $row = $res -> fetch_assoc();
        return $row;
    }

}
?>