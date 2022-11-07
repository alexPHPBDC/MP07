<?php
session_start();
// Formulari per crear un tweet, només usuaris autenticats.
if (!isset($_SESSION['user'])) {
    //How did u end up here? go back to login
    header("Location: ../formularis/formulari_login.php?error=Forbidden", true, 303);
}

?>
<h1>FORMULARI PER CREAR UN TWEET:</h1>
<form action="../process/processa_tweet.php" method="POST">
    <div><label>
            NOU TWEET:
            <textarea name="tweetText" rows="3" cols="55" maxlength="140"></textarea>
        </label>
    </div>
    <div>
    <button type="submit">PUBLICAR</button>
    </div>
    

</form>