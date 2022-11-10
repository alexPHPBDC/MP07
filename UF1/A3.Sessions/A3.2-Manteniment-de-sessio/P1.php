<?php
echo "<h1>PAGINA 1</h1>";
session_start();
$_SESSION['color']  = 'negro';
$_SESSION['animal'] = 'gato';
$_SESSION['instante']   = time();
echo "enllaç: <a href='P2.php'>P2.php</a>"
?>