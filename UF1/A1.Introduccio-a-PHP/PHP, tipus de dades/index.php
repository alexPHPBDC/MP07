<p>PHP és un llenguatge dinàmicament tipat. Els tipus que treballarem en aquest exercici són: enters, booleans, coma flotant, cadenes de caracters i objectes.

Observa ara aquest tall de codi:
</p>

<p><b>
<?php
$i = "catorze";
$tipus_de_i = gettype( $i );
echo "La variable \$i 
      conté el valor $i 
	  i és del tipus $tipus_de_i";

      echo "La variable gettype retorna ".gettype(gettype(1));
?>
</b></p>
<p>
Executa aquest codi.
Observa el comportament de les cometes màgiques de PHP, hem posat $i dins la cadena i ens ha mostrat el seu valor.
Explica perquè hem posat la barra invertida davant del símbol $.
Digues de quin tipus és la variable $i.
Digues per a que serveix la funció gettype.
Exten el codi per tal de, a més de la variable $i, treballar igual amb variables de tipus: coma flotant, booleana i cadena de caracters.
Modifica el codi per saber de quin tipus és el valor que retorna gettype.
Aquest exercici continua amb un exercici amb variables de tipus classe

</p>