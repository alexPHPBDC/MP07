<?php
// Recull amb un formulari les dades de l’usuari i crea un nou compte per aquest usuari a la base de dades.

?>
<ul>
  <li><a href="../formularis/formulari_login.php">Iniciar sessió</a></li>
</ul>
<h1>FORMULARI DE REGISTRE:</h1>
<form action="../process/processa_registre.php" method="POST">
<div><label>
            NOM:
            <input type="text" name="name" value="">
        </label>
    </div>    
<div><label>
            USUARI:
            <input type="text" name="user" value="">
        </label>
    </div>
    <div>
        <label>
            CONTRASENYA:
            <input type="password" name="password" value="">
        </label>
    </div>
    <button type="submit">ENVIAR</button>

</form>

