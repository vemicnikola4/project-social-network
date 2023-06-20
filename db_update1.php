<?php
require_once "connection.php";
// Kreirati fajl db_update1.php koji će prilikom pokretanja u tabelu
// profiles dodati novu kolonu bio tipa TEXT.
// ➢ Prepraviti fajl profile.php tako da u postojećoj formi za izmenu profila
// dodate i jedno textarea polje u kojoj će korisnik moći da menja sadržaj
// biografije. Ovo polje funkcioniše kao i prethodna polja - prilikom
// dolaska na stranicu, korisnik u tom textarea polju može da vidi svoju
// biografiju, koju potom može da izmeni. Prilikom slanja forme, u bazi
// podataka je potrebno promeniti sva polja iz tabele profiles (uključujući i
// polje bio).

// ALTER TABLE `custumers` ADD PRIMARY KEY(`id`);
$q = "ALTER TABLE `profiles`
ADD `bio` TEXT ";

// $has = false;
// foreach ( $allQ as $query ){
//     if ( $query == $q ){
//         $has = true;
//         break;
//     }
// }
// if ( $has === false ){
//     if($conn->query($q)){
//         echo "<p>Query successfull!</p>";
//     }else{
//         echo "<p>Query unsuccessfull</p>. $conn->error .</p>";
//     }
// }
// $allQ[]=$q;




?>