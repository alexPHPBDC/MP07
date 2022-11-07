<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Exemple de formulari</title>

</head>

<body>

    <div style="margin: 30px 10%;">
        <h3>Números primers</h3>

       

        <?php
        if(isset($_POST['numero'])){
            $num = $_POST['numero'];
                if ($num <=1 ) {
                    die("Cal posar un número superior a 1");
                }
            
                $divisors = array();
                
                for ( $i=1; $i<=$num; $i++ ) {
                    if ($num % $i == 0) {
                        $divisors[]=$i;
                    }
                }
                
                $es_primer = (count( $divisors ) == 2);
            
                /* ------- Mostrar resultats --------- */
                if ($es_primer) {
                    echo "El número $num és primer";
                }else{
                    echo "El número $num no és primer i els seus divisors són: ";
                    $separador = "";
                    foreach( $divisors as $d ) {
                        echo "$separador $d";
                        $separador = ",";
                    }
                }
            
            
            
            
            }else{

?>
<form action="" method="POST">
    <input type="number" name="numero" value="">
    <button type="submit">ENVIAR</button>
</form>

<?php


            }

        ?>
    </div>
</body>

</html>