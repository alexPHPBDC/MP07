<?php
// Recull usuari i password amb un formulari.
?>
<ul>
  <li><a href="../formularis/formulari_registre.php">Registrar-se</a></li>
</ul>
<h1>FORMULARI DE LOGIN:</h1>
<form action="../process/processa_login.php" method="POST">    
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