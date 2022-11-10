<?php
  //connexió dins block try-catch:
  //  prova d'executar el contingut del try
  //  si falla executa el catch
  try {
    $hostname = "127.0.0.1";
    $dbname = "acces_dades";
    $username = "u_acces_dades";
    $pw = "i";
    $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname;port=3308;","$username","$pw");
  } catch (PDOException $e) {
    echo "Failed to get DB handle: " . $e->getMessage() . "\n";
    exit;
  }
  
  //preparem i executem la consulta
  $query = $pdo->prepare("select i, a FROM prova");
  $query->execute();

  $rows = $query->fetchAll();
  foreach($rows as $row){
    echo $row['i']." - " . $row['a']. "<br/>";
  }

  //eliminem els objectes per alliberar memòria 
  unset($pdo);
  unset($query);

  /*Fes la teva base de dades acces_dades i prova aquest codi php. Pots crear-la a través del PhpmyAdmin o amb comandes.
Simplifica el codi usant foreach + query
Fes una nova base de dades anomenada gringottsDB i crea un usuari amb permisos sobre aquesta base de dades. Crea una taula anomenada goblins amb estructura: goblin_name, password, last_login. Inserta uns quants goblins (fent servir SQL). Per últim fes un programet amb PDO que llisti tots els goblins de la taula.
 */
?>