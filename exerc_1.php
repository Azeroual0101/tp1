<?php
$string = '200,150.00MAD';
$cleaned_string = preg_replace('/[^0-9,\.]/', '', $string);
echo $cleaned_string ;
echo "<br>" ;



$string = 'abcde$ddfd @abcd )der]';
$cleaned_string = preg_replace('/[^a-zA-Z0-9 ]/', '', $string);
echo $cleaned_string;
?>
