<?php

function pintarMenuSeguir($usuari, $usuariAux)
{
    $followersCount = count($usuariAux->followers);
    $followingCount = count($usuariAux->following);
    echo
    "<form action='#'>
        <input id='self' type='hidden' name='idUsuari' value=$usuari->id>
                $usuariAux->name, te $followersCount seguidors i segueix a $followingCount";
    if (isset($usuariAux->followers[$usuari->id])) {
        echo "<button onclick='ajaxMenuFollow(event)' id='botoFollowUnfollow'  name='unfollow' value=$usuariAux->id> Deixar de seguir</button>";
    } else {
        echo "<button onclick='ajaxMenuFollow(event)' id='botoFollowUnfollow'  name='follow' value=$usuariAux->id>Seguir</button>";
    }
    echo "</form>";
}

function pintarTweets($usuari, $tweets, $frase = "",$usuariAux=null)
{

    if (empty($tweets)) {
        echo $frase;
    } else {
        foreach ($tweets as $tweet) {
            echo "
              <hr><div>
                <p>Tweet del dia $tweet->date</p>
                <p>$tweet->text
                ";

            pintarLikeButton($usuari, $tweet->id,$usuariAux);
            echo "</div><hr>";
        }
    }
}

function pintarLikeButton($usuari, $tweetId,$usuariAux=null)
{
    $heartGrey = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="20px" height="20px" viewBox="0 0 256 256" xml:space="preserve"><defs></defs><g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">	<path d="M 7.486 13.502 c 9.982 -9.982 26.165 -9.982 36.147 0 L 45 14.869 l 0 0 c 6.895 22.882 6.259 47.092 0 72.294 L 26.927 69.089 c 0 0 0 0 0 0 l -19.44 -19.44 C -2.495 39.667 -2.495 23.484 7.486 13.502 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(120, 120, 114); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>	<path d="M 82.514 13.502 c -9.982 -9.982 -26.165 -9.982 -36.147 0 L 45 14.869 l 0 0 v 72.294 l 18.073 -18.073 c 0 0 0 0 0 0 l 19.44 -19.44 C 92.495 39.667 92.495 23.484 82.514 13.502 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(120, 120, 114); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/></g></svg>';
    $heartFull = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="20px" height="20px" viewBox="0 0 256 256" xml:space="preserve"><defs></defs><g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">	<path d="M 7.486 13.502 c 9.982 -9.982 26.165 -9.982 36.147 0 L 45 14.869 l 0 0 c 6.895 22.882 6.259 47.092 0 72.294 L 26.927 69.089 c 0 0 0 0 0 0 l -19.44 -19.44 C -2.495 39.667 -2.495 23.484 7.486 13.502 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(214,73,62); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>	<path d="M 82.514 13.502 c -9.982 -9.982 -26.165 -9.982 -36.147 0 L 45 14.869 l 0 0 v 72.294 l 18.073 -18.073 c 0 0 0 0 0 0 l 19.44 -19.44 C 92.495 39.667 92.495 23.484 82.514 13.502 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(215,90,74); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/></g></svg>';

    echo  "<form action='#' >
            <input type='hidden' name='idUsuari' value=$usuari->id>";

    if($usuariAux !=null){
        echo "<input type='hidden' name='idAux' value=$usuariAux->id>";
    }
    if (isset($usuari->likes[$tweetId])) {
        echo "<button name='dislike' value=$tweetId>$heartFull</button>";
    } else {
        echo "<button name='like' value=$tweetId>$heartGrey</button>";
    }

    echo "</form>";
}

function pintarOwnTweets($usuari)
{
    if (empty($usuari->tweets)) {
        echo "<p>Encara no tens cap tweet! Fes click <a href='formulari_tweet.php' style='color:#990033;'>aqui</a> per crear-ne un 😊";
    } else {
        $svgBin = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 408.483 408.483" style="enable-background:new 0 0 408.483 408.483;" xml:space="preserve"><g><g><path d="M87.748,388.784c0.461,11.01,9.521,19.699,20.539,19.699h191.911c11.018,0,20.078-8.689,20.539-19.699l13.705-289.316    H74.043L87.748,388.784z M247.655,171.329c0-4.61,3.738-8.349,8.35-8.349h13.355c4.609,0,8.35,3.738,8.35,8.349v165.293    c0,4.611-3.738,8.349-8.35,8.349h-13.355c-4.61,0-8.35-3.736-8.35-8.349V171.329z M189.216,171.329    c0-4.61,3.738-8.349,8.349-8.349h13.355c4.609,0,8.349,3.738,8.349,8.349v165.293c0,4.611-3.737,8.349-8.349,8.349h-13.355    c-4.61,0-8.349-3.736-8.349-8.349V171.329L189.216,171.329z M130.775,171.329c0-4.61,3.738-8.349,8.349-8.349h13.356    c4.61,0,8.349,3.738,8.349,8.349v165.293c0,4.611-3.738,8.349-8.349,8.349h-13.356c-4.61,0-8.349-3.736-8.349-8.349V171.329z"/><path d="M343.567,21.043h-88.535V4.305c0-2.377-1.927-4.305-4.305-4.305h-92.971c-2.377,0-4.304,1.928-4.304,4.305v16.737H64.916    c-7.125,0-12.9,5.776-12.9,12.901V74.47h304.451V33.944C356.467,26.819,350.692,21.043,343.567,21.043z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>';
        foreach ($usuari->tweets as $tweet) {
            echo
            "<hr><div>
                <p>Tweet del dia $tweet->date</p>
                <p>$tweet->text
                <a href='process/processa_borrarTuit.php?idTweet=$tweet->id'> $svgBin</a>
              </div><hr>";
        }
    }
}

function pintarLlistatUsuaris($users)
{

    foreach ($users as $user) {
        $userIdvalor = htmlspecialchars($user->id);
        $userName = htmlspecialchars($user->name);
        echo "<option ";

        echo "value='$userIdvalor'> $userName</option>";
    }
}
?>