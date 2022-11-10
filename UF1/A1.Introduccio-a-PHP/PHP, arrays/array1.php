<?php

$cart = []; 
$cart[] = "banana"; 
$cart[] = "apple";
 $cart[] = "potato";
print_r($cart);
echo "<br><br>";
unset($cart[0]);
print_r($cart);
echo "<br><br>";
print_r(array_values($cart));


echo "<br><br><br>";
// creo un array amb 3 elements
$a = array( 5,7,11);
print_r( $a );
echo "<br>";

//afegeixo més elements a l'array
$a[] = 13;  
$a[] = 17;
print_r( $a );
echo "<br>";

//encara un altre
array_push ($a, 23);
print_r( $a );
echo "<br>";

//pinto elements de l'array
echo "El valor de del tercer element de l'array és " . $a[2];
echo "<br>";

unset($a[0]); 
unset($a[1]); // el valor 7 seguia a la possició 1
print_r( $a );
echo "<br>";
?>
