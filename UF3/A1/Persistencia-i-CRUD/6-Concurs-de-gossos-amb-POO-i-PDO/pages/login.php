<?php
session_start();
if(isset($_SESSION['currentUser'])){
    header("Location: admin.php", true, 302);
    exit();
}


?><!DOCTYPE html>
<html lang="ca">

<head>
    <title>Accés</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styleLogin.css" rel="stylesheet">

</head>

<body>

<?php

    //Tractament d'errors que m'arriben per GET
    if (isset($_GET['error'])) {
        $error = "";
        switch ($_GET['error']) {
            case "WrongCredentials":
                $error = "DADES INCORRECTES";
                break;
            case "BDDOffline":
                $error = "Base de dades sense connexió";
                break;
        }
    }
    ?>
    <div class="container" id="container">
        <?php
    if(isset($_GET['error'])){
        if ($_GET['error']=="WrongCredentials"){
        echo "<img style='width:190px' src=../assets/gifs/wrongCredentials.gif>";
        }else if($_GET['error']=="BDDOffline"){
            echo "<img style='width:190px' src=../assets/gifs/databaseOffline.gif>";
        }

        echo "<h4 style='color:red'>$error</h4>";
    
    }

?>
    
            <form action="../process/processLogin.php" method="post">
                <h1>Inicia la sessió</h1>
                <span>introdueix les teves credencials</span>
                <input type="text" name="username" placeholder="Usuari" required />
                <input type="password" name="password" placeholder="Contrasenya" required />
                <button>Inicia la sessió</button>
            </form>
       
        
    </div>
</body>


</html>