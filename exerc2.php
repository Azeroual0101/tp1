<?php 


$str="200,150.00MAD" ;
$long = strlen($str) ;
for ($i = 0 ; $i<$long ;$i++) {

    if (preg_match("/[A-Za-z]/", $str[$i])) {
        echo $str[$i] ;

    }

}


?>