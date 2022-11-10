<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Exemple de formulari</title>
</head>

<body>

    <div style="margin: 30px 10%;">
        <h3>Conjetura</h3>

        <form action="" method="GET">
            <input type="number" name="numero" value="">
            <button type="submit">ENVIAR</button>
        </form>

        <?php 

if(isset($_GET['numero'])){
    $numero = $_GET['numero'];
    if($numero<0){
        die("Cal posar un número superior a 0");
    }
    
$counter = 0;
$max = 0;
$sequencia = array();
$aux = $numero;
while($numero!=1){

    if($numero%2==0){
        $numero=$numero/2;
    }else{
        $numero=($numero*3)+1;
    }

    $sequencia[]=$numero;
    $counter++;
    if($numero>$max){
        $max=$numero;
    }

}

echo "la seqüència del ".$aux." és {".implode(",", $sequencia)."}, després de $counter iteracions i arribant a un màxim de $max";
}
?>


    </div>
</body>

</html>