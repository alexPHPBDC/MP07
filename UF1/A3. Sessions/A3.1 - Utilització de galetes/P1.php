<?php
echo "<h1>PAGINA 1</h1>";
if(isset($_COOKIE["laMevaCookie"])){
    setcookie("laMevaCookie", "101");
}else{
    setcookie("laMevaCookie", "100");
}

echo "<br>enllaç: <a href='P2.php'>P2.php</a>";
echo "<br>enllaç: <a href='P3.php'>P3.php</a>";
?>