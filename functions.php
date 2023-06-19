<?php
function following($id, $friendId, $conn){
    $q = "SELECT * FROM `followers` 
    WHERE `id_sender` = $id AND `id_receiver` = $friendId";
    $res = $conn ->query($q);
    if ( $res -> num_rows == 0 ){
        return false;
    }else{
        return true;
    }
}

//`followers` (`id_sender`,`id_receiver`)
?>