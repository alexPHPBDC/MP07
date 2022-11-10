<?php
if(isset($_GET['data'])){
$nomGoblin = $_GET['data'];
echo "Goblin $nomGoblin inserit";
}
?>
<h1>FORMULARI:</h1>
<form action="process.php" method="POST">
    <div><label>
            GOBLIN NAME:
            <input type="text" name="goblinName" value="">
        </label>
    </div>
    <div>
        <label>
            PASSWORD:
            <input type="password" name="password" value="">
        </label>
    </div>
    <button type="submit">ENVIAR</button>

</form>