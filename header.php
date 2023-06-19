<?php
require_once "validation.php";

function createHeader($array,$conn){
    echo '<nav class="navbar navbar-expand-md navbar-dark">';
    echo '<div class="container-xxl">';
        echo '<a href="index.php" class="navbar-brand">';
            echo '<h1 class="fw-bold text-dark">Social Network';
                
            echo '</h1>';
        echo "</a>"; 

        echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">';
            echo '<span class="navbar-toggler-icon"></span>';
        echo '</button>';
        echo '<div class="collaps navbar-collapse justify-content-end align-center" id="main-nav">';
            echo '<ul class="navbar-nav ">';
            for( $i = 0; $i < count ($array) ; $i++ ){
                if( $array[$i] == 'home'){
                    $link = 'index';
                }elseif($array[$i] == 'connections'){
                    $link = 'followers';

                }else{
                    $link = $array[$i];
                }
                echo "<li class='nav-item '>";
                echo "<a href='$link.php' class='nav-link fs-5 text-dark fw-bold'>".ucfirst($array[$i]) ."
                        </a>";
                echo '</li>';
                
            }
            if ( !empty($_SESSION['id'])){
                $id = $_SESSION['id'];
                $row = profileExists($id, $conn);
                if ($row !== false){
                    echo "<li class='header-icon d-none d-md-block'>";
                    echo "<a href='profile.php' class='nav-link'>";
                    $img = $row['profile_image'];
                    echo "<img src='images/$img'>";
                    echo "</a>";
                    echo '</li>';
                }
            }
                
                // echo '<li class="nav-item">';
                // echo '<a href="register.php" class="nav-link fs-5">Register
                //         </a>';
                // echo '</li>';
                // echo '<li class="nav-item">';
                // echo '<a href="register.php" class="nav-link fs-5">Login
                //         </a>';
                // echo '</li>';
                
                // echo '</ul>';
        echo '</div>';
    echo '</div>';
    echo '</nav>';
}
function indexTile(){
    
}
?>